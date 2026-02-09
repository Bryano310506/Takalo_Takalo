# Takalo Takalo

# Liste des Taches
    - [] creation base
        - [ok] table
            - [ok] role
                (id, libelle)
            - [ok] user
                (id_user, nom, mdp, id_role)
            - [ok] categorie
                (id, libelle)
            - [ok] objets
                (id_objet, titre, description id_categorie, prix)
            - [ok] photos
                (id, id_objet, nom)
            - [ok] status
                (id, libelle)
            - [ok] proprietaire_objet
                (id, id_user, id_objet, date_echange)
            - [ok] historique_echange
                (id, id_objet1, id_objet2, id_status, date_echange)
        - [] donnees

    - [] Partie 1
        - [] Back-End 

            - [] fonction                          // Sharon
                - [] login
                    - [] insertUser(id_role)
                    - [] getUser(id_user)
            - [] BackOffice (admin)                
                - [] fonction  
                    - [] login
                        - [] userExist()
                        - [] insertAdmin()
                    - [] gestion categ
                        - [] insertCategorie()
                - [] pages
                    - [] login
                        - [] login par defaut
                        - [] gestion categorie

            - [] FrontOffice                        // Bry
                - [] fonction
                    - [] insertUtilisateur()
                    - [] transactionnels
                        - [] insertObjets()
                        - [] insertImg
                    - [] getObjetsByUser()
                    - [] insertEchange()
                - [] pages
                    - [] inscription
                    - [] login
                    - [] profile
                        - [] listObjet
                        - [] formulaire insertion

                - [] fonction                       // Mahatoky
                    - [] getAllObjet()
                    - [] getAllEchangeAttente(id_user)
                    - [] getAllEchange(id_user, id_status)
                    - [] acceptEchange(id_historique_echange)
                    - [] refuserEchange(id_historique_echange)
                - [] pages
                    - [] listObjet
                    - [] proposition    



