<?php

session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace-membres', 'root', 'root');

if (isset($_GET['id']) and $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil de <?php echo $userinfo['pseudo']; ?></title>
        <link rel="stylesheet" href="/compte/style.css">
        <link rel="stylesheet" href="/style.css">
    </head>

    <body>
        <nav>
            <div class="btn">
                <a id="a4" href="/index.html"><ion-icon name="arrow-back-outline"></ion-icon> Retour à l'accueil</a>
            </div>
        </nav>
        <div align="center" class="card1">
            <h2>Profil de <?php echo $userinfo['pseudo']; ?></h2>
            <br>
            Pseudo : <?php echo $userinfo['pseudo']; ?>
            <br>
            Mail : <?php echo $userinfo['mail']; ?>
            <br>
            <?php
            if (isset($_SESSION['id']) and $userinfo['id'] == $_SESSION['id']) {
                ?>
                <br>
                <a href="/compte/editionprofil.php">Editer mon profil <ion-icon name="construct"></ion-icon></a>
                <br>
                <a href="/compte/deconnexion.php">Se déconnecter <ion-icon name="person-remove"></ion-icon></a>

                <?php
            }
            ?>
        </div>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>

    </html>
    <?php
}

?>