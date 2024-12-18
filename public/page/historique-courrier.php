<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Historique</title>
</head>
<body>
    <?php
        // APPEL DU TRAITEMENT DE L'HISTORIQUE
        require('../../Traitement/Controle/RecupererIdCourrierTypePourHistorique.php');

        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE LISTE NOTIFICATION -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->
        <main class="main">
            <section class="page-content-historical-mail">
                <h2>Historique</h2>
                <div class="title-historical-mail">
                    <p class="historical__action">Actions Effectuées</p>
                    <p class="historical__date">Date de l'opération</p>
                    <p class="historical__service">Entité responsable</p>
                    <p class="historical__service">Matricule</p>
                    <p class="historical__service"> Nom </p>
                    <p class="historical__service"> Prenom </p>
                </div>
                <?php foreach($liste_des_infos_historique as $ligne) { ?>
                    <div class="element-historical-mail">
                        <p class="historical__action"><?php echo $ligne['action_effectuee'] ?></p>
                        <p class="historical__date"><?php echo $ligne['date_operation'] ?></p>
                        <p class="historical__service"><?php echo $ligne['entite_resoinsable'] ?></p>
                        <p class="historical__service"><?php echo $ligne['Matricule'] ?></p>
                        <p class="historical__service"><?php echo $ligne['nom_utilisateur'] ?></p>
                        <p class="historical__service"><?php echo $ligne['prenom_utilisateur'] ?></p>
                    </div>
                <?php } ?>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE LISTE PROFIL -->
    <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-nav.js"></script>
    <script src="../js/afficher-header.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>