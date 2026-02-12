<?php

namespace app\controllers;

use Flight;
use Throwable;
use app\model\UserModel;
use app\services\Validator;
use app\services\ObjetService;

class ProfileController {

    function rendreProfile($app, $user_connected, $list_objets) {
        Flight::view()->set('session', $user_connected);
        Flight::view()->set('list_objets', $list_objets);
        $app->render('profile/profile');
    }

    function getAllObjetsByuser($id_user) {
        $objService = new ObjetService(Flight::db());
        return $objService->getObjetByUserId($id_user);
    }

}

?>