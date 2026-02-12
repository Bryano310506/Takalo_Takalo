<?php include 'includes/header.php'; ?>
<style>
        /* Profile Card */
        .profile-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }

        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .profile-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
            padding: 1.25rem;
        }

        .profile-card .card-header i {
            color: #6c757d;
        }

        /* Product Cards */
        .product-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .product-card img {
            transition: transform 0.3s ease;
            background-color: #f8f9fa;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        /* Prix en ROUGE */
        .price-tag {
            color: #dc3545 !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }

        /* Section Title */
        .section-title {
            color: #212529;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        /* Card body styling */
        .card-body {
            padding: 1.25rem;
        }

        .card-text {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .card-title {
            color: #212529;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }

        /* Category badge */
        .category-badge {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            display: inline-block;
        }

        /* Empty state */
        .empty-state {
            background-color: #ffffff;
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 3rem 2rem;
        }

        .empty-state i {
            color: #adb5bd;
        }

        /* Profile info styling */
        .profile-info-item {
            padding: 0.5rem 0;
            color: #495057;
        }

        .profile-info-item i {
            color: #6c757d;
            width: 20px;
        }

        .profile-info-item strong {
            color: #212529;
            min-width: 100px;
            display: inline-block;
        }
        
    </style>
</head>
<body>
    <!-- Profile Section -->
    <div class="container my-5">
        <div class="row">
            <!-- Products Section -->
            <div class="col-lg-8 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">
                        <i class="bi bi-box-seam"></i> Mes Produits
                    </h2>
                    <a href="/produit/create" class="btn btn-dark btn-custom">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>

                <?php if(count($list_objets) == 0) { ?>
                    <div class="empty-state text-center">
                        <i class="bi bi-inbox display-4 mb-3 d-block"></i>
                        <h3 class="text-muted mb-2">Aucun produit</h3>
                        <p class="text-muted mb-4">Vous n'avez pas encore ajouté de produits.</p>
                        <a href="/produit/create" class="btn btn-dark btn-custom">Ajouter votre premier produit</a>
                    </div>
                <?php } else { ?>
                    <div class="row g-4">
                        <?php for($i=0; $i<count($list_objets); $i++) { ?>
                            <div class="col-sm-6 col-lg-4">
                              <a href="/produit/show/<?= $list_objets[$i]['id_objet'] ?>" class="text-decoration-none">
                                 <div class="card product-card h-100">
                                    <div class="position-relative overflow-hidden">
                                        <img src="/assets/img/<?= $list_objets[$i]['photos'][0]['nom'] ?? 'default.png' ?>" 
                                             class="card-img-top" 
                                             alt="<?= $list_objets[$i]['titre'] ?>" 
                                             style="height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= $list_objets[$i]['titre'] ?></h5>
                                        <span class="category-badge mb-2">
                                            <?= $list_objets[$i]['libelle_categorie'] ?? 'Non catégorisé' ?>
                                        </span>
                                        <p class="card-text flex-grow-1">
                                            <?= substr($list_objets[$i]['description'] ?? 'Description non disponible', 0, 80) ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="price-tag mb-0">$ <?= $list_objets[$i]['prix'] ?> </span>
                                            <div>
                                                <a href="#" class="btn btn-outline-secondary btn-sm me-1" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="#" class="btn btn-dark btn-sm" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <!-- Profile Card -->
            <div class="col-lg-4">
                <div class="card profile-card">
                    <div class="card-header text-center">
                        <i class="bi bi-person-circle display-5 d-block mb-2"></i>
                        <h5 class="card-title mb-0 fw-bold">Mon Profil</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="profile-info-item">
                                <i class="bi bi-person-fill"></i>
                                <strong>Nom :</strong> <?= $session['nom'] ?? 'N/A' ?>
                            </div>
                            <div class="profile-info-item">
                                <i class="bi bi-person"></i>
                                <strong>Prénom :</strong> <?= $session['prenom'] ?? 'N/A' ?>
                            </div>
                            <div class="profile-info-item">
                                <i class="bi bi-envelope"></i>
                                <strong>Email :</strong> <?= $session['email'] ?? 'N/A' ?>
                            </div>
                            <div class="profile-info-item">
                                <i class="bi bi-telephone"></i>
                                <strong>Téléphone :</strong> <?= $session['telephone'] ?? 'N/A' ?>
                            </div>
                            <div class="profile-info-item">
                                <i class="bi bi-shield-check"></i>
                                <strong>Rôle :</strong> <?= $session['role'] ?? 'Utilisateur' ?>
                            </div>
                        </div>
                        <hr style="border-color: #e9ecef;">
                        <div class="text-center">
                            <p class="text-muted small mb-3">
                                <i class="bi bi-box"></i> 
                                <strong><?= count($list_objets) ?></strong> produit(s)
                            </p>
                            <a href="#" class="btn btn-outline-secondary btn-custom w-100 mb-2">
                                <i class="bi bi-pencil"></i> Modifier Profil
                            </a>
                            <a href="/logout" class="btn btn-outline-danger btn-custom w-100">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>