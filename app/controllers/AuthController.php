<?php

namespace app\controllers;

use Flight;
use Throwable;
use app\model\UserModel;
use app\services\Validator;
use app\services\UserService;

class AuthController {

	public function showRegister() {
		Flight::render('auth/register', [
		'values' => ['nom'=>'','prenom'=>'','email'=>'','telephone'=>''],
		'errors' => ['nom'=>'','prenom'=>'','email'=>'','password'=>'','confirm_password'=>'','telephone'=>''],
		'success' => false
		]);
	}

	public function verificationUser($email, $password) {
		$pdo = Flight::db();
		$model = new UserModel($pdo);

		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user = $model->getUserByEmailAndMdp($email,$hash);

		if ($user) {
			return true;
		}
		return false;
	}

	public function getUser($email, $password) {
		$pdo = Flight::db();
		$model = new UserModel($pdo);

		$hash = password_hash($password, PASSWORD_BCRYPT);
		return $model->getUserByEmailAndMdp($email,$hash);
	}

    public function validateRegisterAjax() {
		header('Content-Type: application/json; charset=utf-8');
		try {
			$req = Flight::request();

			$input = [
				'nom' => $req->data->nom,
				'prenom' => $req->data->prenom,
				'email' => $req->data->email,
				'password' => $req->data->password,
				'confirm_password' => $req->data->confirm_password,
				'telephone' => $req->data->telephone,
			];

			// Essayer de créer le repo pour vérifier si l'email existe déjà
			$model = null;
			try {
				$pdo = Flight::db();
				$model = new UserModel($pdo);
			} catch (Throwable $dbError) {
				// Base de données non disponible, on continue sans vérification d'email
			}

			$res = Validator::validateRegister($input, $model);

			Flight::json([
				'ok' => $res['ok'],
				'errors' => $res['errors'],
				'values' => $res['values'],
			]);

		} catch (Throwable $e) {
			http_response_code(500);
			Flight::json([
				'ok' => false,
				'errors' => ['_global' => 'Erreur serveur lors de la validation: ' . $e->getMessage()],
				'values' => []
			]);
		}
	}

    public function postRegister() {
		$pdo  = Flight::db();
		$model = new UserModel($pdo);
		$svc = new UserService($model);

		$req = Flight::request();

		$input = [
		'nom' => $req->data->nom,
		'prenom' => $req->data->prenom,
		'email' => $req->data->email,
		'password' => $req->data->password,
		'confirm_password' => $req->data->confirm_password,
		'telephone' => $req->data->telephone,
		];

		$res = Validator::validateRegister($input);

		if ($res['ok']) {
		$svc->register($res['values'], (string)$input['password']);
		Flight::render('auth/register', [
			'values' => ['nom'=>'','prenom'=>'','email'=>'','telephone'=>''],
			'errors' => ['nom'=>'','prenom'=>'','email'=>'','password'=>'','confirm_password'=>'','telephone'=>''],
			'success' => true
		]);
		return;
		}

		Flight::render('auth/register', [
		'values' => $res['values'],
		'errors' => $res['errors'],
		'success' => false
		]);
		
	}

	}

?>