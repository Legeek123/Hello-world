<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace-membres', 'root', 'root');

if (isset($_POST['ok'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $mdp = sha1($_POST['mdp']);
    $mdp2 = sha1($_POST['mdp2']);

    if (!empty($_POST['pseudo']) and !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['mdp']) and !empty($_POST['mdp2'])) {
        $pseudolength = strlen($pseudo);
        if ($pseudolength <= 255) {
            if ($mail == $mail2) {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if ($mailexist == 0) {
                        if ($mdp == $mdp2) {
                            $insertmbr = $bdd->prepare("INSERT INTO membres (pseudo, mail, motdepasse) VALUES (?, ?, ?)");
                            $insertmbr->execute(array($pseudo, $mail, $mdp));
                            header("Location: /compte/connexion.php");
                        } else {
                            $erreur = "Vos mots de passe ne correspondent pas !";
                        }
                    } else {
                        $erreur = "Cette adresse mail est déjà utilisé !";
                    }
                } else {
                    $erreur = "Votre adresse mail n'est pas valide !";
                }
            } else {
                $erreur = "Vos adresses mail ne correspondent pas !";
            }
        } else {
            $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
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
    <title>S'inscrire</title>
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
            <label for="pseudo">Pseudo :</label>
            <br>
            <input id="pseudo" name="pseudo" type="text" placeholder="entrez un pseudo..." value="<?php if (isset($pseudo)) {
                echo $pseudo;
            } ?>">
            <br>
            <label for="mail">Email :</label>
            <br>
            <input type="email" name="mail" id="mail" placeholder="entrez votre email..." value="<?php if (isset($mail)) {
                echo $mail;
            } ?>">
            <br>
            <input type="email" name="mail2" id="mail" placeholder="confirmez votre email..." value="<?php if (isset($mail2)) {
                echo $mail2;
            } ?>">
            <br>
            <label for="mdp">Mot de passe :</label>
            <br>
            <input type="password" name="mdp" id="mdp" placeholder="entrez votre mot de passe">
            <br>
            <input type="password" name="mdp2" placeholder="confirmez votre mot de passe">
            <br>
            <input type="submit" name="ok" value="S'inscrire">
            <br>
            <?php

            if (isset($erreur)) {
                echo $erreur;
            }

            ?>
            <br>
            <br>
            <h5>Vous avez déjà un compte ?<br> <a href="/compte/connexion.php">connectez-vous</a></h5>
        </form>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <p>© image par Fietzfotos</p>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>