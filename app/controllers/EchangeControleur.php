<?php

namespace app\controllers;

use app\models\HistoriqueEchangeModel;
use app\models\ProprietaireObjetModel;
use app\models\ObjetModel;
use app\models\StatusModel;
use Flight;

class EchangeControleur
{
    protected $app;
    protected $historiqueEchangeModel;
    protected $proprietaireModel;
    protected $objetModel;
    protected $statusModel;

    public function __construct($app)
    {
        $this->app = $app;
        $this->historiqueEchangeModel = new HistoriqueEchangeModel(Flight::db());
        $this->proprietaireModel = new ProprietaireObjetModel(Flight::db());
        $this->objetModel = new ObjetModel(Flight::db());
        $this->statusModel = new StatusModel(Flight::db());
    }

    /**
     * Récupérer l'ID de l'utilisateur connecté
     * @return int|null L'ID de l'utilisateur ou null
     */
    private function getUserId(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION["user"]["id"] ?? null;
    }

    /**
     * Vérifier que l'utilisateur est connecté, sinon rediriger
     * @param string $redirectUrl L'URL de redirection en cas d'erreur
     * @return int|null L'ID de l'utilisateur ou null
     */
    private function requireLogin(string $redirectUrl = '/'): ?int
    {
        $userId = $this->getUserId();
        if (!$userId) {
            Flight::redirect($redirectUrl);
            return null;
        }
        return $userId;
    }

    /**
     * Afficher la liste des propositions en attente
     */
    public function showEchangeAttente()
    {
        $userId = $this->requireLogin();
        if (!$userId) return;

        $propositions = $this->historiqueEchangeModel->getPendingExchangesDetail($userId);

        $this->app->render('list_proposition', [
            'propositions' => $propositions,
            'count' => count($propositions)
        ]);
    }

    /**
     * Afficher tous les échanges de l'utilisateur
     */
    public function showAllExchanges()
    {
        $userId = $this->requireLogin();
        if (!$userId) return;

        $echanges = $this->historiqueEchangeModel->getUserExchanges($userId);

        $this->app->render('list_exchanges', [
            'echanges' => $echanges,
            'count' => count($echanges)
        ]);
    }

    /**
     * Accepter un échange - Core Business Logic
     * Étapes:
     * 1. Vérifier que c'est le récepteur
     * 2. Vérifier que l'échange est en PENDING
     * 3. Transférer les propriétés
     * 4. Mettre à jour le statut
     * 5. Rediriger
     */
    public function accepterEchange()
    {
        $userId = $this->requireLogin('/echange/proposition/list');
        if (!$userId) return;

        $idEchange = $_POST['id_echange'] ?? null;
        if (!$idEchange) {
            $this->redirectWithError('/echange/proposition/list', 'ID d\'échange manquant');
            return;
        }

        // Récupérer les détails de l'échange
        $echange = $this->historiqueEchangeModel->getExchangeById($idEchange);
        if (!$echange) {
            $this->redirectWithError('/echange/proposition/list', 'Échange introuvable');
            return;
        }

        // Vérifier que c'est bien le récepteur
        if ((int)$echange['id_recepteur'] !== $userId) {
            $this->redirectWithError('/echange/proposition/list', 'Vous n\'êtes pas autorisé à accepter cet échange');
            return;
        }

        // Vérifier que l'échange est en PENDING
        if ($echange['status_code'] !== 'PENDING') {
            $this->redirectWithError('/echange/proposition/list', 'Cet échange n\'est plus en attente');
            return;
        }

        // Effectuer l'acceptation (transferts de propriété + changement de statut)
        if ($this->performExchangeAcceptance($echange)) {
            $this->redirectWithSuccess('/echange/proposition/list', 'Échange accepté avec succès');
        } else {
            $this->redirectWithError('/echange/proposition/list', 'Une erreur s\'est produite lors de l\'acceptation');
        }
    }

    /**
     * Refuser un échange
     * L'utilisateur ne doit pas accepter l'échange, il reste en PENDING pour plus tard
     * Ici on peut implémenter un "Ne pas intéresser" ou simplement ignorer
     */
    public function refuserEchange()
    {
        $userId = $this->requireLogin('/echange/proposition/list');
        if (!$userId) return;

        $idEchange = $_POST['id_echange'] ?? null;
        if (!$idEchange) {
            $this->redirectWithError('/echange/proposition/list', 'ID d\'échange manquant');
            return;
        }

        $echange = $this->historiqueEchangeModel->getExchangeById($idEchange);
        if (!$echange) {
            $this->redirectWithError('/echange/proposition/list', 'Échange introuvable');
            return;
        }

        // Vérifier que c'est le récepteur
        if ((int)$echange['id_recepteur'] !== $userId) {
            $this->redirectWithError('/echange/proposition/list', 'Vous n\'êtes pas autorisé');
            return;
        }

        // Changer le statut à REFUSED
        if ($this->historiqueEchangeModel->updateStatusByCode($idEchange, 'REFUSED', $this->statusModel)) {
            $this->redirectWithSuccess('/echange/proposition/list', 'Proposition refusée');
        } else {
            $this->redirectWithError('/echange/proposition/list', 'Une erreur s\'est produite');
        }
    }

    /**
     * Rejeter un échange - Action du propriétaire pour rejeter une tentative d'échange en cours
     * (Si on veut une distinction entre refuser et rejeter)
     */
    public function rejeterEchange()
    {
        $userId = $this->requireLogin('/echange/proposition/list');
        if (!$userId) return;

        $idEchange = $_POST['id_echange'] ?? null;
        if (!$idEchange) {
            $this->redirectWithError('/echange/proposition/list', 'ID d\'échange manquant');
            return;
        }

        $echange = $this->historiqueEchangeModel->getExchangeById($idEchange);
        if (!$echange) {
            $this->redirectWithError('/echange/proposition/list', 'Échange introuvable');
            return;
        }

        // Vérifier que c'est le récepteur
        if ((int)$echange['id_recepteur'] !== $userId) {
            $this->redirectWithError('/echange/proposition/list', 'Vous n\'êtes pas autorisé');
            return;
        }

        // Changer le statut à REJECTED
        if ($this->historiqueEchangeModel->updateStatusByCode($idEchange, 'REJECTED', $this->statusModel)) {
            $this->redirectWithSuccess('/echange/proposition/list', 'Proposition rejetée');
        } else {
            $this->redirectWithError('/echange/proposition/list', 'Une erreur s\'est produite');
        }
    }

    /**
     * Proposer un nouvel échange - API JSON
     * Données attendues en JSON:
     * {
     *   "objet_offert_id": 1,
     *   "objet_voulu_id": 2,
     *   "user_dest": 3
     * }
     */
    public function proposerEchange()
    {
        $userId = $this->requireLogin("/");
        if (!$userId) {
            Flight::json(['error' => 'Non connecté'], 401);
            return;
        }

        // Récupérer les données JSON
        $data = json_decode(file_get_contents('php://input'), true);

        $idObjetOffert = $data['objet_offert_id'] ?? null;
        $idObjetVoulu = $data['objet_voulu_id'] ?? null;
        $idUserDest = $data['user_dest'] ?? null;

        // Validation basique
        if (!$idObjetOffert || !$idObjetVoulu || !$idUserDest) {
            Flight::json(['error' => 'Données manquantes'], 400);
            return;
        }

        // Validation de la proposition
        $validation = $this->historiqueEchangeModel->validateExchange(
            $userId,
            $idUserDest,
            $idObjetOffert,
            $idObjetVoulu,
            $this->proprietaireModel
        );

        if (!$validation['valid']) {
            Flight::json(['error' => $validation['message']], 400);
            return;
        }

        // Obtenir l'ID du statut PENDING
        $statusId = $this->statusModel->getStatusIdByCode('PENDING');
        if (!$statusId) {
            Flight::json(['error' => 'Statut PENDING non trouvé'], 500);
            return;
        }

        // Créer l'échange
        $idEchange = $this->historiqueEchangeModel->createExchange(
            $userId,
            $idUserDest,
            $idObjetOffert,
            $idObjetVoulu,
            $statusId
        );

        if ($idEchange) {
            Flight::json([
                'success' => true,
                'message' => 'Proposition envoyée',
                'id_echange' => $idEchange
            ]);
        } else {
            Flight::json(['error' => 'Erreur lors de la création de la proposition'], 500);
        }
    }

    /**
     * Afficher la liste des objets échangeables
     */
    public function showAllObjetEchangable()
    {
        $userId = $this->requireLogin();
        if (!$userId) return;

        $mesobjects = $this->objetModel->getObjetsActuels($userId);
        $objetsDisponibles = $this->objetModel->getObjetsNonPossedes($userId);

        $this->app->render('list_objet', [
            'mes_objets' => $mesobjects,
            'objets' => $objetsDisponibles
        ]);
    }

    /**
     * ============================================================
     * FONCTIONS UTILITAIRES PRIVÉES
     * ============================================================
     */

    /**
     * Effectuer l'acceptation d'un échange avec transfert de propriété
     * @param array $echange Les détails de l'échange
     * @return bool true si succès, false sinon
     */
    private function performExchangeAcceptance(array $echange): bool
    {
        try {
            // Transférer l'objet proposé au récepteur
            if (!$this->proprietaireModel->transferer(
                $echange['id_recepteur'],
                $echange['id_objet_propose'],
                $echange['id_echange']
            )) {
                return false;
            }

            // Transférer l'objet demandé à l'émetteur
            if (!$this->proprietaireModel->transferer(
                $echange['id_emetteur'],
                $echange['id_objet_demande'],
                $echange['id_echange']
            )) {
                return false;
            }

            // Mettre à jour le statut à ACCEPTED
            return $this->historiqueEchangeModel->updateStatusByCode(
                $echange['id_echange'],
                'ACCEPTED',
                $this->statusModel
            );
        } catch (\Exception $e) {
            error_log("Erreur lors de l'acceptation de l'échange: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Redirection avec message de succès (à implémenter selon votre système de flash messages)
     * @param string $url L'URL de redirection
     * @param string $message Le message de succès
     */
    private function redirectWithSuccess(string $url, string $message)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['success'] = $message;
        Flight::redirect($url);
    }

    /**
     * Redirection avec message d'erreur
     * @param string $url L'URL de redirection
     * @param string $message Le message d'erreur
     */
    private function redirectWithError(string $url, string $message)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error'] = $message;
        Flight::redirect($url);
    }
}
?>
