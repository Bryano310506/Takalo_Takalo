# TODO Mahatoky
## creation de la base :
    - [ok] creation base 
    - [ok] insetion table 
    - [ok] insertion data test

## [] creation classe modele :
### [] Models :
- [ok] objectModel
    - [ok] getAllObjet()
- [ok] echangeModel
    - [ok] getAllEchangeAttente(id_user)
    - [ok] getAllEchange(id_user, id_status)
    - [ok] envoyerDemandeEchange()
    - [ok] acceptEchange(id_historique_echange)
    - [ok] refuserEchange(id_historique_echange)
- [ok] objetModel
    - getAllObjet
- HistoriqueEchamgeModele
### [] Vue :
    - [ok] listObjet
    - [ok] gestionPropostion
        - listePropositionEnAttente
        - boutonacceter et reffuser
### [] controleur
    - echangeControleur
        - showEchangeAttente(id_user)
        - acceptEchange(id_echange)
        - refuserEchange(id_echange)


### proposition
#### raison de la propostion :
- Sarotrarota le milalao anle table .
    - lasa milalao date amnle mirecuperer proprietaire ana objet ray
    - lasa sarotra fatarina oe iza no nandefa anle proposition ana echange
    - tsy stoquer le information oe ovina no n acceptena le echange
- proprietaire_objet :
    - id
    - id_user
    - id_objet
    - id_echange            // avaiamin i echange iza le objet ny azo , null ra vo nocreena
    - date_debut
    - date_fin
- historique_echange :
    - id
    - id_emeteur            // le miproposer echange
    - id_recepteur          // le miaccepte echange
    - id_status             // en atente , accepte , refuser , rejete
    - date_debut            // date nandefasana anle izy
    - date_fin              // date niacceptena , na nirefusena , na nirejetena