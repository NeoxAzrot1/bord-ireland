<?php

    // Ouverture de la session et initialisation des erreurs et des includes
    session_start();

    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    include 'assets/php/connect_PDO.php';
    include 'assets/php/ctrlSaisies.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Bord'Irlande - BLOG</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f69c2bce58.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>        

    <?php

        // Check si l'user est connecté
        if(isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user'] == true) {
            $user = true;
        } else {
            $user = false;
        }

        // Affichage en fonction de si user connecté ou pas
        if($user) {
            header('Location: index.php');
        }

        // Affiche le formulaire seulement la première fois
        if($_POST) {
            // Vérifie si tous les input ont été remplis et contrôle la saisie
            if((isset($_POST['login']) && !empty($_POST['login'])) AND
            (isset($_POST['password']) && !empty($_POST['password']))) {
                $login = ctrlSaisies($_POST['login']);
                $password = ctrlSaisies($_POST['password']);

                $req = $bdd->prepare('SELECT * FROM user WHERE Login = ? AND Pass = ?');
                $req->execute(array(
                    $login,
                    $password
                ));
                $donnees = $req->fetch();

                // Vérifie si la langue existe déjà. Exemple : FRAN
                if(empty($donnees)) {
                    $_SESSION['errorLogin'] = true;

                    $_SESSION['login'] = $login;
                    $_SESSION['password'] = $password;

                    header('Location: connexion.php');
                } else {
                    $_SESSION['user'] = true;
                    $_SESSION['login'] = $login;
                    $_SESSION['errorLogin'] = false;

                    header('Location: index.php');
                }

            }

        }

    ?>

    <h1>Connexion</h1>

    <form action="" method="POST">
        <label for="login">Identifiant :</label>
        <input type="text" id="login" name="login" placeholder="Sur 30 car." value="<?php echo isset($_SESSION['errorLogin']) && $_SESSION['errorLogin'] == true ? $_SESSION['login'] : "" ?>" size="30" maxlength="30" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="Sur 15 car." value="<?php echo isset($_SESSION['errorLogin']) && $_SESSION['errorLogin'] == true ? $_SESSION['password'] : "" ?>" size="15" maxlength="15" minlength="6" required>

        <?php echo isset($_SESSION['errorLogin']) && $_SESSION['errorLogin'] == true ? "L'identifiant ou le mot de passe n'est pas valide !" : "" ?>

        <input type="submit">
    </form>

    <script src="assets/js/script.js"></script>
</body>

</html>

        <script src="assets/js/script.js"></script>
    </body>

</html>