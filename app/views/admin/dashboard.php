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
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 2.8em;
            font-weight: 300;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.1em;
            opacity: 0.9;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-card.users {
            border-top: 4px solid #667eea;
        }
        .stat-card.categories {
            border-top: 4px solid #28a745;
        }
        .stat-card.objets {
            border-top: 4px solid #ffc107;
        }
        .stat-card.general {
            border-top: 4px solid #dc3545;
        }
        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        .stat-icon.users {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        .stat-icon.categories {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        .stat-icon.objets {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        .stat-icon.general {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        .stat-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #333;
        }
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .stat-number.users {
            color: #667eea;
        }
        .stat-number.categories {
            color: #28a745;
        }
        .stat-number.objets {
            color: #ffc107;
        }
        .stat-number.general {
            color: #dc3545;
        }
        .stat-details {
            color: #666;
            line-height: 1.6;
        }
        .stat-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        .stat-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-list li:last-child {
            border-bottom: none;
        }
        .stat-list .label {
            color: #666;
        }
        .stat-list .value {
            font-weight: 600;
            color: #333;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
        .nav-links {
            text-align: center;
            margin-bottom: 30px;
        }
        .nav-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .chart-title {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .mini-chart {
            display: flex;
            align-items: flex-end;
            height: 100px;
            gap: 10px;
        }
        .mini-chart-bar {
            flex: 1;
            background: linear-gradient(180deg, #667eea, #764ba2);
            border-radius: 4px 4px 0 0;
            position: relative;
            transition: opacity 0.3s ease;
        }
        .mini-chart-bar:hover {
            opacity: 0.8;
        }
        .mini-chart-label {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8em;
            color: #666;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>📊 Tableau de bord</h1>
            <p><?= htmlspecialchars($generalStats['date']) ?></p>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="nav-links">
            <a href="/admin/users">👥 Gérer les utilisateurs</a>
            <a href="/admin/categories">📁 Gérer les catégories</a>
            <a href="/admin/objets">📦 Gérer les objets</a>
        </div>
        
        <div class="stats-grid">
            <!-- Statistiques Utilisateurs -->
            <div class="stat-card users">
                <div class="stat-header">
                    <div class="stat-icon users">👥</div>
                    <div class="stat-title">Utilisateurs</div>
                </div>
                <div class="stat-number users"><?= $userStats['total_users'] ?? 0 ?></div>
                <div class="stat-details">
                    <ul class="stat-list">
                        <?php if (!empty($userStats['users_by_role'])): ?>
                            <?php foreach ($userStats['users_by_role'] as $role): ?>
                                <li>
                                    <span class="label"><?= htmlspecialchars($role['libelle']) ?></span>
                                    <span class="value"><?= $role['count'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Statistiques Catégories -->
            <div class="stat-card categories">
                <div class="stat-header">
                    <div class="stat-icon categories">📁</div>
                    <div class="stat-title">Catégories</div>
                </div>
                <div class="stat-number categories"><?= $categorieStats['total_categories'] ?? 0 ?></div>
                <div class="stat-details">
                    <p>Total des catégories disponibles dans le système</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= min(100, ($categorieStats['total_categories'] ?? 0) * 10) ?>%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques Objets -->
            <div class="stat-card objets">
                <div class="stat-header">
                    <div class="stat-icon objets">📦</div>
                    <div class="stat-title">Objets</div>
                </div>
                <div class="stat-number objets"><?= $objetStats['total_objets'] ?? 0 ?></div>
                <div class="stat-details">
                    <ul class="stat-list">
                        <?php if (!empty($objetStats['objets_by_categorie'])): ?>
                            <?php foreach (array_slice($objetStats['objets_by_categorie'], 0, 3) as $categorie => $count): ?>
                                <li>
                                    <span class="label"><?= htmlspecialchars($categorie) ?></span>
                                    <span class="value"><?= $count ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Statistiques Générales -->
            <div class="stat-card general">
                <div class="stat-header">
                    <div class="stat-icon general">📈</div>
                    <div class="stat-title">Général</div>
                </div>
                <div class="stat-number general"><?= $generalStats['total_items'] ?? 0 ?></div>
                <div class="stat-details">
                    <p>Total des éléments dans le système</p>
                    <ul class="stat-list">
                        <li>
                            <span class="label">Année</span>
                            <span class="value"><?= $generalStats['year'] ?? date('Y') ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Graphiques -->
        <?php if (!empty($objetStats['objets_by_categorie'])): ?>
        <div class="chart-container">
            <div class="chart-title">📊 Répartition des objets par catégorie</div>
            <div class="mini-chart">
                <?php 
                $maxCount = max($objetStats['objets_by_categorie']);
                foreach (array_slice($objetStats['objets_by_categorie'], 0, 8) as $categorie => $count): 
                    $height = ($count / $maxCount) * 100;
                ?>
                    <div class="mini-chart-bar" style="height: <?= $height ?>%; background: linear-gradient(180deg, #28a745, #20c997);">
                        <span class="mini-chart-label"><?= htmlspecialchars(substr($categorie, 0, 8)) ?> (<?= $count ?>)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Animation des barres de progression
            setTimeout(() => {
                const progressFills = document.querySelectorAll('.progress-fill');
                progressFills.forEach(fill => {
                    const width = fill.style.width;
                    fill.style.width = '0%';
                    setTimeout(() => {
                        fill.style.width = width;
                    }, 500);
                });
            }, 800);
        });
    </script>
</body>
</html>
