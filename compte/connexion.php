<?php

session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace-membres', 'root', 'root');

if (isset($_POST['ok'])) {
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $mdpconnect = sha1($_POST['mdpconnect']);
    if (!empty($mailconnect) and !empty($mdpconnect)) {
        $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
        $requser->execute(array($mailconnect, $mdpconnect));
        $userexist = $requser->rowCount();
        if ($userexist == 1) {
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("Location: /compte/profil.php?id=" . $_SESSION['id']);
        } else {
            $erreur = "Mauvais mail ou mot de passe !";
        }
    } else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="/compte/style.css">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <nav>
        <div class="btn">
            <a id="a4" href="/index.html"><ion-icon name="arrow-back-outline"></ion-icon> Retour à l'accueil</a>
        </div>
    </nav>
    <br>
    <div class="form">
        <form action="" method="POST">
            <label for="mail">Mail :</label>
            <br>
            <input id="mail" name="mailconnect" type="email" placeholder="entrez un mail...">
            <br>
            <label for="mdp">Mot de passe :</label>
            <br>
            <input type="password" name="mdpconnect" id="mdp" placeholder="entrez votre mot de passe...">
            <br>
            <br>
            <input type="submit" name="ok" value="Se connecter">
            <?php

            if (isset($erreur)) {
                echo $erreur;
            }

            ?>
            <br>
            <br>
            <h5>Vous n'avez pas encore de compte ?<br> <a href="/compte/inscription.php">inscrivez-vous</a></h5>
        </form>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <p>© image par Fietzfotos</p>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>