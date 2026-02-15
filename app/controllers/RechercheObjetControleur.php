<?php

namespace app\controllers;

use app\models\HistoriqueEchangeModel;
use app\models\ProprietaireObjetModel;
use app\models\ObjetModel;
use app\models\StatusModel;
use Flight;

class RechercheObjetControleur
{
    protected $app;
    protected $objetModel;

    public function __construct($app)
    {
        $this->app = $app;
        $this->objetModel = new ObjetModel(Flight::db());
    }

    public function showObjetFiltred($id_categorie,$mot_cle) {
        
    }
}