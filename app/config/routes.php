<?php

use app\controllers\AuthController;
use app\controllers\CategorieController;
use app\controllers\ObjetController;
use app\controllers\ProfileController;
use app\controllers\UserController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	// traitement d' Inscription
	$authController = new AuthController();

	$router->get('/', [$authController, 'showRegister']);

	$router->post('/register', [$authController, 'postRegister']);

	$router->post('/api/validate/register', [$authController, 'validateRegisterAjax']);

	// traitement de Login
	$router->group('/auth', function() use ($router, $app) {
		$router->get('/login', function() use ($app) {
			$app->render('auth/login', null);

		});
		$router->post('/login', function() use ($app) {
			$req = $app->request();

			$email = $req->data->email;
			$motDePasse = $req->data->password;
		
			$authController = new AuthController();

			if($authController->verificationUser($email, $motDePasse)) {
				$user = $authController->getUser($email, $motDePasse);
		
				// Simuler la connexion de l'utilisateur
				$_SESSION['user_connected'] = $user;

				$app->redirect('/profile');
			} else {
				$app->render('auth/login', [
					'error' => 'Email ou mot de passe incorrect.'
				]);
			}
		});
	});

   // Routes d'authentification
	$router->group('/login', function() use ($router, $app) {
		$router->get('', function() use ($app) {
			$authController = new AuthController($app);
			$authController->showLogin();
		});
		$router->post('', function() use ($app) {
			$authController = new AuthController($app);
			$authController->login();
		});
	});

	//logout
	$router->get('/logout', function() use ($app) {
		$authController = new AuthController($app);
		$authController->logout();
	});

	// Routes catégories (public)
    $router->get('/categories', function() use ($app) {
        $categorieController = new CategorieController($app);
        $categorieController->showListeCategories();
    });

    // Routes objets (public)
    $router->get('/objets', function() use ($app) {
        $objetController = new ObjetController($app);
        $objetController->showListeObjets();
    });
    
    $router->get('/objets/categorie/@id', function($id) use ($app) {
        $objetController = new ObjetController($app);
        $objetController->showObjetsByCategorie($id);
    });

	// Routes admin catégories
	$router->group('/admin', function() use ($router, $app) {
		$router->get('/categories', function() use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->showGestionCategories();
		});
		
		$router->get('/categories/ajout', function() use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->showAjoutCategorie();
		});
		
		$router->post('/admin/categories/ajout', function() use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->insertCategorie();
		});
		
		$router->get('/categories/modifier/@id', function($id) use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->showModifierCategorie($id);
		});
		
		$router->post('/categories/modifier', function() use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->updateCategorie();
		});

		// Routes admin objets
		$router->get('/objets', function() use ($app) {
			$objetController = new ObjetController($app);
			$objetController->showGestionObjets();
		});
		
		$router->get('/objets/ajout', function() use ($app) {
			$objetController = new ObjetController($app);
			$objetController->showAjoutObjet();
		});
		
		$router->post('/objets/ajout', function() use ($app) {
			$req = $app->request();

			$titre = $req->data->titre;
			$description = $req->data->description;
			$id_categorie = $req->data->id_categorie;
			$prix = $req->data->prix;
			$photos = $_FILES['photos'] ?? null;

			$user_connected = $_SESSION['user_connected'];
			$objetController = new ObjetController($app);
			$objetController->insertObjet($titre, $description, $id_categorie, $prix, $photos, $user_connected['id_user']);
		});
		
		$router->get('/objets/modifier/@id', function($id) use ($app) {
			$objetController = new ObjetController($app);
			$objetController->showModifierObjet($id);
		});
		
		$router->post('/objets/modifier/@id', function($id) use ($app) {
			$req = $app->request(); 
			$titre = $req->data->titre; 
			$description = $req->data->description;
			$id_categorie = $req->data->id_categorie; 
			$prix = $req->data->prix; 
			$files = $_FILES['photos'] ?? null;
			
			$objetController = new ObjetController($app);
			$objetController->updateObjet($id, $titre, $description, $id_categorie, $prix, $files);
		});
		
		$router->delete('/objets/@id', function($id) use ($app) {
			$objetController = new ObjetController($app);
			$objetController->deleteObjet($id);
		});

		// Routes admin utilisateurs
		$router->get('/users', function() use ($app) {
			$userController = new UserController($app);
			$userController->showGestionUsers();
		});
		
		$router->get('/users/ajout', function() use ($app) {
			$userController = new UserController($app);
			$userController->showAjoutUser();
		});
		
		$router->post('/users/ajout', function() use ($app) {
			$userController = new UserController($app);
			$userController->insertUser();
		});
		
		$router->get('/users/modifier/@id', function($id) use ($app) {
			$userController = new UserController($app);
			$userController->showModifierUser($id);
		});
		
		$router->post('/users/modifier', function() use ($app) {
			$userController = new UserController($app);
			$userController->updateUser();
		});
		
		$router->delete('/users/@id', function($id) use ($app) {
			$userController = new UserController($app);
			$userController->deleteUser($id);
		});

		// Tableau de bord
		$router->get('/dashboard', function() use ($app) {
			$userController = new UserController($app);
			$userController->showDashboard();
		});

		$router->delete('/categories/@id', function($id) use ($app) {
			$categorieController = new CategorieController($app);
			$categorieController->deleteCategorie($id);
		});
	});

	$router->group('/profile', function() use ($router, $app) {
		$router->get('', function() use ($app) {
			$objetController = new ObjetController();
			$user_connected = $_SESSION['user_connected'];
			$list_objets = $objetController->getAllObjetsByuser($user_connected['id_user']);
			if($user_connected) {
				$profile = new ProfileController();
				$profile->rendreProfile($app, $user_connected, $list_objets);
			} 
		});
		
	});

	$router->group('/produit', function() use ($router, $app) {
		$router->get('/show/@id:[0-9]+', function($id) use ($app) {
			$objetController = new ObjetController();
			$objets = $objetController->getObjetById($id);
			if($objets) {
				$app->render('produits/produit', ['objet' => $objets]);
			} else {
				$app->redirect('/profile');
			}
		});
		$router->get('/create', function() use ($app) {
			$app->render('produits/create', null);
		});
		$router->post('/create', function() use ($app) {
			$req = $app->request();

			$titre = $req->data->titre;
			$description = $req->data->description;
			$id_categorie = $req->data->id_categorie;
			$prix = $req->data->prix;
			$photos = $_FILES['photos'] ?? null;

			$user_connected = $_SESSION['user_connected'];
			
			$objetController = new ObjetController();
			if($objetController->insertObjet($user_connected['id_user'], $titre, $description, $id_categorie, $prix, $photos)) {
				$app->redirect('/profile');
			} else {
				$app->render('produits/create', ['error' => 'Erreur lors de la création du produit.']);
			}
		});
		$router->post('/edit/@id:[0-9]+', function($id) use ($app) { 
			$req = $app->request(); 
			$titre = $req->data->titre; 
			$description = $req->data->description;
			$id_categorie = $req->data->id_categorie; 
			$prix = $req->data->prix; 
			$files = $_FILES['photos'] ?? null;
			$objetController = new ObjetController(); 

			if($objetController->updateObjet($id, $titre, $description, $id_categorie, $prix, $files)) { 
				$app->redirect('/profile'); 
			} else { 
				$app->render('produits/produit', ['error' => 'Erreur lors de la mise à jour du produit.']); 
			} 
		});
		$router->get('/delete/@id:[0-9]+', function($id) use ($app) { 
			$objetController = new ObjetController(); 
			if($objetController->deleteObjet($id)) { 
				$app->redirect('/profile'); 
			} else { 
				$app->redirect('/profile'); 
			}
		});
	});

	
}, [ SecurityHeadersMiddleware::class ]);