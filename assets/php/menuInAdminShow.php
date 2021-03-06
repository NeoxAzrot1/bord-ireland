<?php

    // Check si l'admin est connecté
    if(isset($_SESSION['admin']) && !empty($_SESSION['admin']) && $_SESSION['admin'] == true) {
        $admin = true;
    } else {
        $admin = false;
    }

?>

<!-- Affiche une partie pour admin si il est connecté -->
<div class="menu">
    <a href="../index.php"><img src="../assets/images/logo.png" /></a>

    <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../articles.php">Articles</a></li>
        <li><a href="../mentions.php">A propos</a></li>
        <li><a href="../contact.php">Contact</a></li>
        <?php echo $admin == true ? '<li><a href="../admin.php">Administration</a></li>' : ''; ?>
    </ul>
</div>
