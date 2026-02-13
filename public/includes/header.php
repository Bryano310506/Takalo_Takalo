<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <title>TakaloTakalo</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar plus compacte avec bordures arrondies */
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0.5rem 0;
            border-radius: 20px;
            margin: 15px auto;
            max-width: 1200px;
        }

        .navbar-custom .container-fluid {
            padding: 0 1.5rem;
        }

        .navbar-brand-custom {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057 !important;
            padding: 0.5rem 1rem;
        }

        .navbar-brand-custom i {
            color: #6c757d;
            margin-right: 0.5rem;
        }

        .navbar-nav .nav-link {
            color: #6c757d;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.2s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #212529;
        }

        .navbar-nav .nav-link i {
            margin-right: 0.3rem;
        }

        /* Hero Section - blanc et gris */
        .hero-section {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            color: #495057;
            padding: 3rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .hero-section h1 {
            color: #212529;
            font-size: 2.5rem;
        }

        .hero-section p {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navbar Compacte avec Bordures Arrondies -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand navbar-brand-custom" href="#">
                <i class="bi bi-shop"></i> TakaloTakalo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-grid"></i> Produits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-cart"></i> Panier
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="fw-bold">Bienvenue sur <span class="display-5 fw-bold">TakaloTakalo</span></h1>
            <p class="lead mb-0">Votre plateforme d'échange et de vente en ligne</p>
        </div>
    </section>