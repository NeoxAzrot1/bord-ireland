<?php

    // Ouverture de la session et initialisation des erreurs et des includes
    session_start();

    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    include '../assets/php/connect_PDO.php';
    include '../assets/php/ctrlSaisies.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bord'Irlande - ADMIN</title>
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/style.css" />
        <script src="https://kit.fontawesome.com/f69c2bce58.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700,900&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php

            // Affiche le formulaire seulement la première fois
            if($_POST) {
                // Vérifie si tous les input ont été remplis et contrôle la saisie
                if((isset($_POST['lib_court']) && !empty($_POST['lib_court'])) AND
                (isset($_POST['lib_long']) && !empty($_POST['lib_long'])) AND
                (isset($_POST['pays']) && !empty($_POST['pays']))) {
                    $lib_court = ctrlSaisies($_POST['lib_court']);
                    $lib_long = ctrlSaisies($_POST['lib_long']);
                    $pays = ctrlSaisies($_POST['pays']);
                    $pays = strtoupper($pays);

                    $req = $bdd->query('SELECT * FROM langue WHERE NumLang LIKE "' . $pays . '%"');
                    $donnees = $req->fetch();
        
                    // Vérifie si la langue existe déjà. Exemple : FRAN
                    if(empty($donnees)) {
                        $req = $bdd->prepare('INSERT INTO langue(NumLang, Lib1Lang, Lib2Lang, NumPays) VALUES(:NumLang, :Lib1Lang, :Lib2Lang, :NumPays)');
                        $req->execute(array(
                            'NumLang' => $pays . "01",
                            'Lib1Lang' => $lib_court,
                            'Lib2Lang' => $lib_long,
                            'NumPays' => $pays
                            ));

                        $_SESSION['answer'] = "<b>" . $pays . "01" . "</b> vient d'être ajouté à la table !";
                    } else {
                        // Récupère la clé primaire maximale de la langue et lui ajoute 1
                        $req = $bdd->query('SELECT MAX(NumLang) AS NumLangMax FROM langue WHERE NumLang LIKE "' . $pays . '%"');
                        $donnees = $req->fetch();

                        $pays_split = str_split($donnees['NumLangMax'], 4);
                        $pays_next_id = (int) $pays_split[1] + 1;
                        
                        // Rajoute un 0 devant si on est entre 1 et 9 car sinon on aurait par exemple : FRAN2 et non FRAN02
                        if($pays_next_id < 10) {
                            $pays_next_id = "0" . $pays_next_id;
                        }

                        // Ajoute la langue
                        $req = $bdd->prepare('INSERT INTO langue(NumLang, Lib1Lang, Lib2Lang, NumPays) VALUES(:NumLang, :Lib1Lang, :Lib2Lang, :NumPays)');
                        $req->execute(array(
                            'NumLang' => $pays_split[0] . $pays_next_id,
                            'Lib1Lang' => $lib_court,
                            'Lib2Lang' => $lib_long,
                            'NumPays' => $pays
                            ));

                        $_SESSION['answer'] = "<b>" . $pays_split[0] . $pays_next_id  . "</b> vient d'être ajouté à la table !";

                        $req->closeCursor();
                    }

                }

                // Redirection avec un message personnalisé
                header('Location: index.php');
            }

        ?>
        
        
        <?php include '../assets/php/menuInAdminShow.php'; ?>
        <div class="thematiques">
            <?php include '../assets/php/menuAdmin.php'; ?>
            <div class="Update">
                <h1>Ajouter une langue.</h1>
                <div class="UpdateContent">
                    <form action="new.php" method="POST">
                        <div class="Margin">
                            <label for="lib_court">Libellé court :</label>
                            <input type="text" id="lib_court" name="lib_court" placeholder="Entrer le nom de la langue" size="25" maxlength="25" autofocus="autofocus" required><br>
                        </div>
                        <div class="Margin">
                            <label for="lib_long">Libellé long :</label>
                            <input type="text" id="lib_long" name="lib_long" placeholder="Entrer le nom de la langue" size="45" maxlength="45" required><br>
                        </div>
                        <div class="Margin">
                            <label for="pays">Pays :</label>
                            <select name="pays" id="pays" required>
                                <option value="" disabled selected>-- Choisir un pays --</option>
                                <?php 
                                
                                    $req = $bdd->query('SELECT * FROM pays ORDER BY numPays');

                                    while($donnees = $req->fetch()) {
                                ?>

                                        <option value="<?php echo $donnees['numPays']; ?>"><?php echo $donnees['frPays']; ?></option>
                                
                                <?php
                                    }

                                    $req->closeCursor();

                                ?>
                            </select><br>
                        </div>
                        <div class="validerInput">
                            <input type="submit"><br>
                        </div>
                    </form>
                    <div class="Margin validerInput">
                        <a href="index.php" class="back"><i class="fas fa-arrow-left"></i> Revenir au tableau</a>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>