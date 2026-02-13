SELECT
    id_echange,
    id_emetteur,
    id_recepteur,
    id_objet_propose,
    id_objet_demande,
    id_status,
    date_debut,
    date_fin,
    status_code,
    status_libelle
FROM
    v_historique_echange_status
WHERE
    status_code = :status_code
    AND id_recepteur = :id_recepteur;

-- insertre une propostion d echange
INSERT INTO historique_echange he (
        date_debut ,
        date_fin,
        id_emetteur,
        id_objet_demande,
        id_objet_propose,
        id_recepteur,
        id_status
    )
    VALUES (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )


-- recuperer la liste des objet non possede
SELECT 
    o.*,
    u.id_user,
    u.nom
FROM objets o
JOIN v_current_objet_user vcou
JOIN user u on vcou.id_user = u.id_user
on o.id_objet = vcou.id_objet
WHERE o.id_objet NOT IN (
    SELECT id_objet
    FROM v_current_objet_user
    WHERE id_user = 1
)

-- recuperer les echange en atente
SELECT * FROM historique_echange he JOIN objets od = he