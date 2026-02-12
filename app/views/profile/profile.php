<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <?= $session['nom'] ?> <?= $session['prenom'] ?>
    <?php for($i=0; $i<count($list_objets); $i++) { ?>
        <?php if(count($list_objets) == 0) { ?>
            <h2>Aucun elements</h2>
        <?php } else { ?>
            <?= $list_objets[$i]['titre']?>
        <?php } ?>
    <?php } ?>
</body>
</html>