<?php

session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace-membres', 'root', 'root');

if (isset($_SESSION['id'])) {
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();
    if (isset($_POST['newpseudo']) and !empty($_POST['newpseudo']) and $_POST['newpseudo'] != $user['pseudo']) {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $pseudolength = strlen($newpseudo);
        if ($pseudolength <= 255) {
            $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
            $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
            header("Location: /compte/profil.php?id=" . $_SESSION['id']);
        } else {
            $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
        }
    }
    if (isset($_POST['newmail']) and !empty($_POST['newmail']) and $_POST['newmail'] != $user['mail']) {
        $newmail = htmlspecialchars($_POST['newmail']);
        $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
        $reqmail->execute(array($newmail));
        $mailexist = $reqmail->rowCount();
        if ($mailexist == 0) {
            if (filter_var($newmail, FILTER_VALIDATE_EMAIL)) {
                $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
                $insertmail->execute(array($newmail, $_SESSION['id']));
                header("Location: /compte/profil.php?id=" . $_SESSION['id']);
            } else {
                $erreur = "Votre adresse mail n'est pas valide !";
            }
        } else {
            $erreur = "Cette adresse mail est déjà utilisée !";
        }
    }
    if (isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) and isset($_POST['newmdp2']) and !empty($_POST['newmdp2'])) {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);
        if ($mdp1 == $mdp2) {
            $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['id']));
            header("Location: /compte/profil.php?id=" . $_SESSION['id']);
        } else {
            $erreur = "Vos mots de passe ne correspondent pas !";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edition de profil</title>
        <link rel="stylesheet" href="/compte/style.css">
        <link rel="stylesheet" href="/style.css">
    </head>

    <body>
        <br>
        <div align="center" class="form">
            <form action="" method="post" enctype="multipart/form-data">
                <h2>Edition de mon profil</h2><br><br>
                <label>Pseudo :</label>
                <input type="text" name="newpseudo" placeholder="votre nouveau pseudo..."
                    value="<?php echo $user['pseudo']; ?>"><br>
                <label>Email :</label>
                <input type="email" name="newmail" placeholder="votre nouvelle email..."
                    value="<?php echo $user['mail']; ?>"><br>
                <label>Mot de passe :</label>
                <input type="password" name="newmdp1" placeholder="votre nouveau mot de passe..."><br>
                <label>Confirmation du mot de passe :</label>
                <input type="password" name="newmdp2" placeholder="confirmez votre mot de passe..."><br>
                <input type="submit" name="ok" value="Valider">
                <?php

                if (isset($erreur)) {
                    echo $erreur;
                }

                ?>
        </div>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>

    </html>
    <?php
} else {
    header("Location: /compte/connexion.php");
}

?>