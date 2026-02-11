SELECT
    *
FROM
    historique_echange he
    JOIN status s on he.id_status = s.id
WHERE s.code = ?;