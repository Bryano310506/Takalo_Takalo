<?php
namespace app\services;

use app\model\UserModel;

class UserService {
	private $model;

	public function __construct(UserModel $model) { 
		$this->model = $model; 
	}

	public function register(array $values, $plainPassword) {
		$hash = password_hash((string)$plainPassword, PASSWORD_DEFAULT);

		return $this->model->createUser($values['nom'], $values['prenom'], $values['email'], $hash, $values['telephone'], $values['id_role']);
	}

}

?>

