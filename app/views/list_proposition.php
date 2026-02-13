<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Propositions Reçues</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header-subtitle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .badge-count {
            background: rgba(255, 255, 255, 0.3);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* État vide */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            margin: 40px 0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .empty-state-action {
            margin-top: 30px;
        }

        .btn-link {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Cartes de propositions */
        .proposition-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            animation: slideIn 0.4s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .proposition-card:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .proposition-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .proposition-from {
            flex: 1;
            min-width: 200px;
        }

        .proposition-from-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .proposition-from-date {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .proposition-status {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            min-width: 120px;
        }

        .proposition-body {
            padding: 30px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 20px;
            align-items: center;
        }

        .objet-box {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .objet-box:hover {
            border-color: #667eea;
            background: #f5f5ff;
        }

        .objet-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .objet-label.propose {
            color: #27ae60;
        }

        .objet-label.demande {
            color: #e67e22;
        }

        .objet-titre {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #333;
        }

        .objet-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .objet-price {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .objet-categorie {
            display: inline-block;
            background: #f0f0f0;
            color: #667eea;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-right: 8px;
            margin-top: 8px;
        }

        .exchange-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #bbb;
            flex-direction: column;
            gap: 10px;
        }

        .exchange-arrow-text {
            font-size: 0.7rem;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Actions */
        .proposition-actions {
            padding: 20px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-accept {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-accept:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .btn-refuse {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-refuse:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        /* Modal de confirmation */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 30px;
            text-align: center;
        }

        .modal-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .modal-message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn-cancel {
            background: #f0f0f0;
            color: #333;
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .proposition-body {
                grid-template-columns: 1fr;
            }

            .exchange-arrow {
                transform: rotate(90deg);
                margin: 10px 0;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .proposition-header {
                flex-direction: column;
                text-align: center;
            }

            .proposition-from {
                width: 100%;
            }

            .proposition-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>📬 Mes Propositions Reçues</h1>
            <div class="header-subtitle">
                <span>Vous avez</span>
                <span class="badge-count"><?php echo isset($count) ? $count : count($propositions ?? []); ?> proposition<?php echo (isset($count) ? $count : count($propositions ?? [])) > 1 ? 's' : ''; ?></span>
            </div>
        </div>

        <!-- MESSAGES SESSION -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ✗ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info">
                ℹ️ <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
            </div>
        <?php endif; ?>

        <!-- LISTE VIDE -->
        <?php if (empty($propositions)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h2>Aucune proposition en attente</h2>
                <p>Vous n'avez pas encore reçu de propositions d'échange.</p>
                <p style="margin-top: 10px; opacity: 0.8;">Explorez les objets disponibles et proposez des échanges!</p>

                <div class="empty-state-action">
                    <a href="/echange/objets" class="btn-link">Voir les Objets Disponibles</a>
                </div>
            </div>
        <?php else: ?>

            <!-- PROPOSITIONS -->
            <?php foreach ($propositions as $prop): ?>
                <div class="proposition-card">
                    <!-- HEADER -->
                    <div class="proposition-header">
                        <div class="proposition-from">
                            <div class="proposition-from-name">
                                <?php echo htmlspecialchars($prop['nom_emetteur'] ?? 'Utilisateur'); ?>
                            </div>
                            <div class="proposition-from-date">
                                Proposé le <?php echo isset($prop['date_proposition']) 
                                    ? date('d/m/Y à H:i', strtotime($prop['date_proposition'])) 
                                    : 'Date inconnue'; ?>
                            </div>
                        </div>

                        <div class="proposition-status">
                            <span style="text-transform: uppercase;">
                                <?php echo htmlspecialchars($prop['status_libelle'] ?? 'En attente'); ?>
                            </span>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div class="proposition-body">
                        <!-- OBJET PROPOSÉ -->
                        <div class="objet-box">
                            <div class="objet-label propose">
                                ✓ Il vous propose
                            </div>
                            <div class="objet-titre">
                                <?php echo htmlspecialchars($prop['titre_objet_propose'] ?? 'Objet'); ?>
                            </div>
                            <div class="objet-description">
                                <?php echo htmlspecialchars($prop['description_objet_propose'] ?? 'Pas de description'); ?>
                            </div>

                            <div style="margin-top: 12px;">
                                <?php if (isset($prop['categorie_objet_propose'])): ?>
                                    <span class="objet-categorie">
                                        <?php echo htmlspecialchars($prop['categorie_objet_propose']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div style="margin-top: 12px;">
                                <span class="objet-price">
                                    💰 <?php echo number_format($prop['prix_objet_propose'] ?? 0, 2, ',', ' '); ?> €
                                </span>
                            </div>
                        </div>

                        <!-- FLÈCHE D'ÉCHANGE -->
                        <div class="exchange-arrow">
                            <span>⇄</span>
                            <span class="exchange-arrow-text">Échange</span>
                        </div>

                        <!-- OBJET DEMANDÉ -->
                        <div class="objet-box">
                            <div class="objet-label demande">
                                ✓ En échange de votre
                            </div>
                            <div class="objet-titre">
                                <?php echo htmlspecialchars($prop['titre_objet_demande'] ?? 'Objet'); ?>
                            </div>
                            <div class="objet-description">
                                <?php echo htmlspecialchars($prop['description_objet_demande'] ?? 'Pas de description'); ?>
                            </div>

                            <div style="margin-top: 12px;">
                                <?php if (isset($prop['categorie_objet_demande'])): ?>
                                    <span class="objet-categorie">
                                        <?php echo htmlspecialchars($prop['categorie_objet_demande']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div style="margin-top: 12px;">
                                <span class="objet-price">
                                    💰 <?php echo number_format($prop['prix_objet_demande'] ?? 0, 2, ',', ' '); ?> €
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="proposition-actions">
                        <!-- BOUTON REFUSER -->
                        <form method="POST" action="/echange/refuser" style="display: inline;">
                            <input type="hidden" name="id_echange" value="<?php echo $prop['id_echange']; ?>">
                            <button type="submit" class="btn btn-refuse" onclick="return confirmerAction(event, 'Refuser cette proposition ?')">
                                👎 Refuser
                            </button>
                        </form>

                        <!-- BOUTON ACCEPTER -->
                        <form method="POST" action="/echange/accepter" style="display: inline;">
                            <input type="hidden" name="id_echange" value="<?php echo $prop['id_echange']; ?>">
                            <button type="submit" class="btn btn-accept" onclick="return confirmerAction(event, 'Accepter cet échange ? Cette action est irrévocable.')">
                                ✓ Accepter l'Échange
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- MODAL DE CONFIRMATION -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon" id="modalIcon">❓</div>
            <div class="modal-title" id="modalTitle">Confirmation</div>
            <div class="modal-message" id="modalMessage">Êtes-vous sûr?</div>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="annulerAction()">Annuler</button>
                <button class="modal-btn modal-btn-confirm" onclick="confirmerActuelleAction()">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        let formPourActionne = null;
        let messageAction = "";

        function confirmerAction(event, message) {
            event.preventDefault();
            formPourActionne = event.target.closest("form");
            messageAction = message;

            // Déterminer l'action
            const action = formPourActionne.action;
            if (action.includes("accepter")) {
                document.getElementById("modalIcon").textContent = "✓";
                document.getElementById("modalTitle").textContent = "Accepter l'Échange?";
            } else if (action.includes("refuser")) {
                document.getElementById("modalIcon").textContent = "👎";
                document.getElementById("modalTitle").textContent = "Refuser la Proposition?";
            }

            document.getElementById("modalMessage").textContent = message;
            document.getElementById("confirmModal").classList.add("show");

            return false;
        }

        function confirmerActuelleAction() {
            if (formPourActionne) {
                formPourActionne.submit();
            }
        }

        function annulerAction() {
            document.getElementById("confirmModal").classList.remove("show");
            formPourActionne = null;
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById("confirmModal").addEventListener("click", function(e) {
            if (e.target === this) {
                annulerAction();
            }
        });

        // Fermer avec Échap
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                annulerAction();
            }
        });
    </script>

</body>

</html>