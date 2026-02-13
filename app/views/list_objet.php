<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Troc - Objets Disponibles</title>

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
            max-width: 1200px;
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

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Section d'onglets */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .tab-btn.active {
            background: white;
            color: #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Messages d'alerte */
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

        /* Grille de cartes */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 10px;
        }

        .card-propriétaire {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .card-badge {
            background: rgba(255, 255, 255, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-titre {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .card-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .card-categorie {
            display: inline-block;
            background: #f0f0f0;
            color: #667eea;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: auto;
        }

        .card-prix {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2ecc71;
        }

        .card-date {
            font-size: 0.85rem;
            color: #999;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .btn-proposer {
            flex: 1;
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-proposer:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-proposer:active {
            transform: translateY(0);
        }

        /* Section vide */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            opacity: 0.9;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 450px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .form-group .objet-voulu {
            background: #f9f9f9;
            border: 2px solid #667eea;
            padding: 12px;
            border-radius: 6px;
            font-weight: 600;
            color: #667eea;
        }

        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            padding: 20px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .btn-modal {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-modal-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-modal-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-modal-secondary:hover {
            background: #e8e8e8;
        }

        .btn-modal:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .modal-content {
                width: 95%;
                max-width: 100%;
            }
        }

        /* Section mes objets (en haut) */
        .mes-objets-section {
            margin-bottom: 40px;
        }

        .section-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>🔄 Plateforme d'Échange</h1>
            <p>Échangez vos objets avec d'autres utilisateurs</p>
        </div>

        <!-- MESSAGES -->
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

        <!-- MES OBJETS -->
        <?php if (!empty($mes_objets)): ?>
            <div class="mes-objets-section">
                <div class="section-title">
                    📦 Mes Objets (<?php echo count($mes_objets); ?>)
                </div>

                <div class="grid">
                    <?php foreach ($mes_objets as $objet): ?>
                        <div class="card" style="opacity: 0.8; pointer-events: none;">
                            <div class="card-header">
                                <div>
                                    <div class="card-titre" style="color: white; margin: 0;">
                                        <?php echo htmlspecialchars($objet['titre']); ?>
                                    </div>
                                </div>
                                <div class="card-badge">Mes Objets</div>
                            </div>

                            <div class="card-body">
                                <div class="card-description">
                                    <?php echo htmlspecialchars($objet['description']); ?>
                                </div>

                                <span class="card-categorie">
                                    <?php echo htmlspecialchars($objet['categorie'] ?? 'Non catégorisé'); ?>
                                </span>

                                <div class="card-meta">
                                    <span class="card-prix">
                                        <?php echo number_format($objet['prix'], 2, ',', ' '); ?> €
                                    </span>
                                    <span class="card-date">
                                        <?php echo date('d/m/Y', strtotime($objet['date_creation'] ?? 'now')); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr style="border: none; height: 2px; background: rgba(255,255,255,0.2); margin: 40px 0;">
            </div>
        <?php endif; ?>

        <!-- OBJETS DISPONIBLES -->
        <div class="section-title">
            🌟 Objets Disponibles pour l'Échange (<?php echo count($objets); ?>)
        </div>

        <?php if (empty($objets)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h2>Aucun objet disponible</h2>
                <p>Revenez plus tard pour découvrir des objets à échanger</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($objets as $objet): ?>
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($objet['proprietaire_nom'] ?? 'Utilisateur'); ?>
                                </div>
                                <div class="card-propriétaire">
                                    ID User: <?php echo htmlspecialchars($objet['id_user']); ?>
                                </div>
                            </div>
                            <div class="card-badge">Disponible</div>
                        </div>

                        <div class="card-body">
                            <div class="card-titre">
                                <?php echo htmlspecialchars($objet['titre']); ?>
                            </div>

                            <div class="card-description">
                                <?php echo htmlspecialchars($objet['description']); ?>
                            </div>

                            <span class="card-categorie">
                                📂 <?php echo htmlspecialchars($objet['categorie'] ?? 'Non catégorisé'); ?>
                            </span>

                            <div class="card-meta">
                                <span class="card-prix">
                                    <?php echo number_format($objet['prix'], 2, ',', ' '); ?> €
                                </span>
                                <span class="card-date">
                                    <?php echo date('d/m/Y', strtotime($objet['date_creation'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-actions">
                            <button class="btn-proposer"
                                onclick="ouvrirPopup(
                                    <?php echo $objet['id_objet']; ?>,
                                    <?php echo $objet['id_user']; ?>,
                                    '<?php echo htmlspecialchars($objet['titre'], ENT_QUOTES); ?>'
                                )">
                                💼 Proposer un échange
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ========================= MODAL ========================= -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>💼 Proposer un Échange</span>
                <button class="modal-close" onclick="fermerPopup()">✕</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Objet que vous cherchez :</label>
                    <div class="objet-voulu" id="objetVouluTitre">
                        (Sélectionnez un objet)
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectOffert">Objet que vous proposez :</label>

                    <?php if (empty($mes_objets)): ?>
                        <div style="color: #e74c3c; padding: 10px; background: #fef5f5; border-radius: 6px;">
                            ⚠️ Vous n'avez aucun objet à proposer
                        </div>
                    <?php else: ?>
                        <select id="selectOffert">
                            <option value="">-- Choisir un objet --</option>
                            <?php foreach ($mes_objets as $m): ?>
                                <option value="<?php echo $m['id_objet']; ?>">
                                    <?php echo htmlspecialchars($m['titre']); ?> (<?php echo number_format($m['prix'], 2, ',', ' '); ?> €)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="fermerPopup()">Annuler</button>
                <button class="btn-modal btn-modal-primary" 
                    onclick="envoyerEchange()"
                    <?php echo empty($mes_objets) ? 'disabled' : ''; ?>>
                    Envoyer la Proposition
                </button>
            </div>
        </div>
    </div>

    <!-- ========================= JAVASCRIPT ========================= -->
    <script>
        let objetVouluId = null;
        let objetDestUser = null;

        function ouvrirPopup(idObjet, idUserDest, titre) {
            objetVouluId = idObjet;
            objetDestUser = idUserDest;

            document.getElementById("objetVouluTitre").innerText = titre;
            document.getElementById("modal").classList.add("show");
        }

        function fermerPopup() {
            document.getElementById("modal").classList.remove("show");
            document.getElementById("selectOffert").value = "";
        }

        function envoyerEchange() {
            const offertId = document.getElementById("selectOffert").value;

            if (!offertId) {
                alert("❌ Veuillez sélectionner un objet à proposer");
                return;
            }

            if (offertId == objetVouluId) {
                alert("❌ Vous ne pouvez pas échanger un objet contre lui-même");
                return;
            }

            const payload = {
                objet_offert_id: parseInt(offertId),
                objet_voulu_id: objetVouluId,
                user_dest: objetDestUser
            };

            console.log("📤 Proposition envoyée:", payload);

            // Afficher le loader
            const btnEnvoyer = event.target;
            const textOriginal = btnEnvoyer.innerText;
            btnEnvoyer.disabled = true;
            btnEnvoyer.innerText = "⏳ Envoi...";

            fetch("/echange/api/proposer", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
                .then(r => r.json())
                .then(rep => {
                    if (rep.success) {
                        alert("✓ Proposition envoyée avec succès!");
                        fermerPopup();
                        location.reload();
                    } else {
                        alert("❌ Erreur: " + (rep.error || "Erreur inconnue"));
                    }
                })
                .catch(err => {
                    console.error("Erreur:", err);
                    alert("❌ Erreur de connexion");
                })
                .finally(() => {
                    btnEnvoyer.disabled = false;
                    btnEnvoyer.innerText = textOriginal;
                });
        }

        // Fermer modal au clic extérieur
        document.getElementById("modal").addEventListener("click", function(e) {
            if (e.target === this) {
                fermerPopup();
            }
        });

        // Fermer modal avec Échap
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                fermerPopup();
            }
        });
    </script>

</body>

</html>