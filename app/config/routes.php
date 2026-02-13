<?php

use app\controllers\EchangeControleur;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * TAKALO TAKALO - ROUTES
 * Architecture Refactorisée
 * 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

    // ============================================================
    // ROUTES D'ACCUEIL
    // ============================================================

    /**
     * GET /
     * Page d'accueil
     */
    $router->get('/', function() use ($app) {
        $app->render('welcome', [ 'message' => 'Bienvenue sur Takalo Takalo!' ]);
    });


    // ============================================================
    // ROUTES D'AFFICHAGE - OBJETS
    // ============================================================

    /**
     * GET /objet/list
     * Affiche la liste des objets disponibles pour l'échange
     * Affiche aussi les objets possédés par l'utilisateur
     */
    $router->get('/objet/list', [EchangeControleur::class, 'showAllObjetEchangable']);


    // ============================================================
    // ROUTES D'AFFICHAGE - PROPOSITIONS
    // ============================================================

    /**
     * GET /echange/proposition/list
     * Affiche la liste des propositions reçues (en attente)
     */
    $router->get('/echange/proposition/list', [EchangeControleur::class, 'showEchangeAttente']);

    /**
     * GET /echange/tous
     * Affiche tous les échanges de l'utilisateur (optionnel)
     */
    $router->get('/echange/tous', [EchangeControleur::class, 'showAllExchanges']);


    // ============================================================
    // ROUTES D'ACTIONS - API JSON
    // ============================================================

    /**
     * POST /echange/api/proposer
     * Crée une proposition d'échange (API JSON)
     * 
     * Données attendues (JSON):
     * {
     *   "objet_offert_id": 1,
     *   "objet_voulu_id": 2,
     *   "user_dest": 3
     * }
     * 
     * Réponse:
     * {"success": true, "id_echange": 5}
     * ou
     * {"error": "Message d'erreur"}
     */
    $router->post('/echange/api/proposer', [EchangeControleur::class, 'proposerEchange']);


    // ============================================================
    // ROUTES D'ACTIONS - FORMULAIRES
    // ============================================================

    /**
     * POST /echange/accepter
     * Accepte une proposition d'échange
     * Effectue les transferts de propriété automatiquement
     * 
     * Données POST:
     * - id_echange: L'ID de l'échange à accepter
     */
    $router->post('/echange/accepter', [EchangeControleur::class, 'accepterEchange']);

    /**
     * POST /echange/refuser
     * Refuse une proposition d'échange
     * 
     * Données POST:
     * - id_echange: L'ID de l'échange à refuser
     */
    $router->post('/echange/refuser', [EchangeControleur::class, 'refuserEchange']);

    /**
     * POST /echange/rejeter
     * Rejette une proposition d'échange
     * 
     * Données POST:
     * - id_echange: L'ID de l'échange à rejeter
     */
    $router->post('/echange/rejeter', [EchangeControleur::class, 'rejeterEchange']);


    // ============================================================
    // ROUTES D'ACTIONS - AUTRES ACTIONS
    // ============================================================

    /**
     * POST /echange/completer
     * Complète un échange (après la livraison physique)
     * 
     * Données POST:
     * - id_echange: L'ID de l'échange à compléter
     */
    $router->post('/echange/completer', [EchangeControleur::class, 'completerEchange']);

}, [ SecurityHeadersMiddleware::class ]);


// ============================================================
// ROUTES API ADDITIONNELLES (OPTIONNEL)
// ============================================================

/**
 * Routes API pour appels AJAX/Fetch
 * Retournent du JSON
 */
$router->group('/api', function(Router $router) use ($app) {

    /**
     * GET /api/echange/:id
     * Retourne les détails d'un échange (JSON)
     */
    $router->get('/echange/@id', [EchangeControleur::class, 'getExchangeDetail']);

    /**
     * GET /api/objets/:id_user
     * Retourne les objets d'un utilisateur (JSON)
     */
    $router->get('/objets/@id_user', [EchangeControleur::class, 'getUserObjets']);

    /**
     * GET /api/propositions/:id_user
     * Retourne les propositions d'un utilisateur (JSON)
     */
    $router->get('/propositions/@id_user', [EchangeControleur::class, 'getUserPropositions']);

}, [ SecurityHeadersMiddleware::class ]);

?>