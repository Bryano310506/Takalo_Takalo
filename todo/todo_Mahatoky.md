# TODO Mahatoky
## creation de la base :
    - [ok] creation base 
    - [ok] insetion table 
    - [ok] insertion data test

## [] creation classe modele :
### [] Models :
- [] objectModel
    - [] getAllObjet()
- [] echangeModel
    - [] getAllEchangeAttente(id_user)
    - [] getAllEchange(id_user, id_status)
    - [] acceptEchange(id_historique_echange)
    - [] refuserEchange(id_historique_echange)
### [] Vue :
    - [] listObjet
    - [] gestionPropostion 
### [] controleur
    - echangeControleur
        - showEchangeAttente(id_user)
        - acceptEchange(id_echange)
        - refuserEchange(id_echange)