<?php

use app\controllers\AuthController;
use app\controllers\ObjetController;
use app\controllers\ProfileController;
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

	// Logout
	$router->get('/logout', function() use ($app) {
		session_destroy();

		$app->render('auth/register', ['errors' => '']);
	});	

	// $router->get('/hello-world/@name', function($name) {
	// 	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
	// });

	// $router->get('/route-iray', function() {
	// 	echo '<h1>route-iray ve</h1>';
	// });

	// $router->group('/api', function() use ($router) {
	// 	$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
	// 	$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
	// 	$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
	// });
	
}, [ SecurityHeadersMiddleware::class ]);