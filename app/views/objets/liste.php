<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .search-bar {
            margin-bottom: 30px;
        }
        .search-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .search-input:focus {
            outline: none;
            border-color: #28a745;
        }
        .objets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        .objet-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            position: relative;
        }
        .objet-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-color: #28a745;
        }
        .objet-id {
            color: #6c757d;
            font-size: 0.85em;
            margin-bottom: 8px;
        }
        .objet-name {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin: 0 0 10px 0;
        }
        .objet-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
            min-height: 3em;
        }
        .objet-categorie {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            margin-top: 10px;
        }
        .objet-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state svg {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><?= htmlspecialchars($title) ?></h1>
        </header>
        
        <main class="content">
            <?php if (isset($error)): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($objets)): ?>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= count($objets) ?></div>
                        <div class="stat-label">Objets totaux</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= count(array_unique(array_column($objets, 'categorie_libelle'))) ?></div>
                        <div class="stat-label">Catégories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= date('Y') ?></div>
                        <div class="stat-label">Année en cours</div>
                    </div>
                </div>
                
                <div class="search-bar">
                    <input type="text" class="search-input" placeholder="Rechercher un objet..." id="searchInput">
                </div>
                
                <div class="objets-grid" id="objetsGrid">
                    <?php foreach ($objets as $objet): ?>
                        <div class="objet-card" data-titre="<?= htmlspecialchars(strtolower($objet['titre'])) ?>" data-description="<?= htmlspecialchars(strtolower($objet['description'])) ?>">
                            <div class="objet-id">ID: #<?= htmlspecialchars($objet['id_objet']) ?></div>
                            <h3 class="objet-name"><?= htmlspecialchars($objet['titre']) ?></h3>
                            <p class="objet-description"><?= htmlspecialchars($objet['description'] ?: 'Aucune description') ?></p>
                            <div class="objet-price"><?= number_format($objet['prix'], 2, ',', ' ') ?> €</div>
                            <span class="objet-categorie"><?= htmlspecialchars($objet['categorie_libelle'] ?: 'Non catégorisé') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3>Aucun objet trouvé</h3>
                    <p>Il n'y a aucun objet à afficher pour le moment.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Fonction de recherche
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.objet-card');
            
            cards.forEach(card => {
                const titre = card.dataset.titre;
                const description = card.dataset.description;
                
                if (titre.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.objet-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>
</html>
