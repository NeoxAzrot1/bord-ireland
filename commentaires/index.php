<?php

    // Ouverture de la session et initialisation des erreurs et des includes
    session_start();

    ini_set('display_errors', 'on');
    ini_set('display_startup_errors', 'on');
    error_reporting(E_ALL);

    include '../assets/php/connect_PDO.php';
    include '../assets/php/dateChangeFormat.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bord'Irlande - ADMIN</title>
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/style.css" />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700,900&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/f69c2bce58.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>

    <body>
        
        <?php include '../assets/php/menuInAdminShow.php'; ?>
        <div class="thematiques">
            <?php include '../assets/php/menuAdmin.php'; ?>
            
            <h1>Tout les commentaires.</h1>
            
            <?php 

                // Affichage du message personnalisé lors de la redirection
                if(isset($_SESSION['answer']) && !empty($_SESSION['answer'])) {
                    echo "<p class='answer'>" . $_SESSION['answer'] . "</p>";
                    $_SESSION['answer'] = "";
                }

            ?>

            <table>
                <thead>
                    <tr>
                        <th>NumCom :</th>
                        <th>Date :</th>
                        <th>Pseudo :</th>
                        <th>Email :</th>
                        <th>Titre :</th>
                        <th>Commentaire</th>
                        <th>Numéro d'article :</th>
                    </tr>
                </thead>
                <tbody>

                <?php

                    $req = $bdd->query('SELECT * FROM comment ORDER BY NumCom');

                    // Affichage de tout les commentaires dans un tableau
                    while ($donnees = $req->fetch())
                    {

                ?>
                    <tr>
                        <td><?php echo $donnees['NumCom'];?></td>
                        <td><?php echo dateChangeFormat($donnees['DtCreC'], "Y-m-d H:i:s", "d/m/Y H:i:s");?></td>
                        <td><?php echo $donnees['PseudoAuteur'];?></td>
                        <td><?php echo $donnees['EmailAuteur'];?></td>
                        <td><?php echo $donnees['TitrCom'];?></td>
                        <td><?php echo $donnees['LibCom'];?></td>
                        <td><a href="../articles/index.php"><?php echo $donnees['NumArt'];?></a></td>
                        <td><a href="update.php?id=<?php echo $donnees['NumCom'];?>" class="modified_link"><i class="fas fa-edit"></i> Modifier</a></td>
                        <td><a href="delete.php?id=<?php echo $donnees['NumCom'];?>" class="delete_link" data-id="<?php echo $donnees['NumCom']; ?>"><i class="fas fa-trash-alt"></i> Supprimer</a></td>
                    </tr>

                    <?php 

                        }

                        $req->closeCursor();

                    ?>
                </tbody>
            </table>

            <a href="new.php" class="add"><i class="fas fa-plus"></i> Ajouter un nouveau commentaire</a>
                            
            <script src="../assets/js/script.js"></script>
        </div>
    </body>

</html>