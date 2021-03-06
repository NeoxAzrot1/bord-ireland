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

            // Redirige si l'utilisateur est déjà connecté
            if($user) {
                header('Location: index.php');
            }

            // Affiche le formulaire de connexion seulement la première fois
            if($_POST) {
                // Vérifie si tous les input ont été remplis et contrôle la saisie
                if((isset($_POST['login']) && !empty($_POST['login'])) AND
                (isset($_POST['password']) && !empty($_POST['password']))) {
                    $login = ctrlSaisies($_POST['login']);
                    $password = ctrlSaisies($_POST['password']);

                    $req = $bdd->prepare('SELECT * FROM user WHERE Login = ?');
                    $req->execute(array(
                        $login
                    ));
                    $donnees = $req->fetch();

                    // Vérifie si l'utilisateur existe dans la base de donnée
                    if(empty($donnees)) {
                        $_SESSION['errorConnexion'] = true;

                        $_SESSION['login'] = $login;
                        $_SESSION['password'] = $password;

                        // Redirige avec un message d'erreur
                        header('Location: connexion.php');
                    } else {
                        $isPasswordCorrect = password_verify($password, $donnees['Pass']);

                        if($isPasswordCorrect) {
                            $_SESSION['user'] = true;
                            $_SESSION['login'] = $login;
                            $_SESSION['errorConnexion'] = false;
    
                            // Vérifie si c'est l'admin connecté
                            if($_SESSION['login'] == 'Admin') {
                                $_SESSION['admin'] = true;
                            }
    
                            // Redirige après la connexion
                            header('Location: index.php');
                        } else {
                            $_SESSION['errorConnexion'] = true;

                            $_SESSION['login'] = $login;
                            $_SESSION['password'] = $password;
    
                            // Redirige avec un message d'erreur
                            header('Location: connexion.php');
                        }

                    }

                }

            }
        ?>
        <div class="connexion">
            <!-- Menus -->
            <?php include 'assets/php/menu.php'; ?>

            <div class="contentConnexion">
                <h1>Connexion</h1>
                
                <!-- Formulaire de connexion avec input près remplis si erreur -->
                <div class="formulaireConnexion">
                    <form action="" method="POST">
                        <div>
                            <div class="Margin">
                                <label for="login">Identifiant :</label><br>
                                <input type="text" id="login" name="login" placeholder="Entrer votre identifiant" value="<?php echo isset($_SESSION['errorConnexion']) && $_SESSION['errorConnexion'] == true ? $_SESSION['login'] : "" ?>"  maxlength="30" required><br>
                            </div>
                            <div class="Margin">
                                <label for="password">Mot de passe :</label><br>
                                <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe" value="<?php echo isset($_SESSION['errorConnexion']) && $_SESSION['errorConnexion'] == true ? $_SESSION['password'] : "" ?>"  maxlength="255" required><br>
                            </div>
                        </div>

                        <!-- Message d'erreur de connexion -->
                        <?php echo isset($_SESSION['errorConnexion']) && $_SESSION['errorConnexion'] == true ? "<p class='errorConnexion'>L'identifiant ou le mot de passe n'est pas valide !</p>" : "" ?>
                        <div class="validerInput">
                            <input type="submit">
                        </div>
                    </form>

                    <!-- Lien pour s'inscrire -->
                    <div class="inscrireConnexion">
                        <a href="inscription.php">S'inscrire</a>
                    </div>
                    <script src="assets/js/script.js"></script>
                </div>
            </div>
        </div>
    </body>
</html>