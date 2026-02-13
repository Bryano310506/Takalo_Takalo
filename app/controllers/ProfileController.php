<?php

namespace app\controllers;

use Flight;
use app\services\ObjetService;

class ProfileController {

    function rendreProfile($app, $user_connected, $list_objets) {
        Flight::view()->set('session', $user_connected);
        Flight::view()->set('list_objets', $list_objets);
        $app->render('profile/profile');
    }

}

?>