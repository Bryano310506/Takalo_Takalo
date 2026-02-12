<?php

use app\controllers\AuthController;
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
			$profile = new ProfileController();
			$user_connected = $_SESSION['user_connected'];
			$list_objets = $profile->getAllObjetsByuser($user_connected['id_user']);
			if($user_connected) {
				$profile->rendreProfile($app, $user_connected, $list_objets);
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