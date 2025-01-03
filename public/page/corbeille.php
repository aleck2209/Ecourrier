<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Corbeille</title>
</head>
<body>
    <?php
        // APPEL DE LA PAGE DE TRAITEMENT PHP
        require('../../Traitement/Controle/CorbeilleCourrier.php');

        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE TABLEAU DE BORD -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->

        <main class="main">
            <section class="page-content-basket" id="page-user">
                <h2>Corbeille</h2>
                <div class="title-basket-mail">
                    <p class="basket-number">Numéro</p>
                    <p class="basket-object">Objet</p>
                    <p class="basket-file">Fichiers</p>
                    <p class="basket-expediteur">Expéditeur</p>
                    <p class="basket-destinataire">Destinataire</p>
                    <p class="basket-date">Date</p>
                    <p class="basket-status">Statut</p>
                    <p class="basket-action">Opérations</p>
                </div>
                <?php foreach($tableau_courriers_de_la_corbeille_entite_ou_pole as $courrier) { ?>
                    <div class="element-basket-mail">
                        <output name="" class="basket-number"><?php echo $courrier['numero_ordre'] ?></output>
                        <output name="" class="basket-object"><?php echo $courrier['objet_du_courrier'] ?></output>
                        <output name="" class="basket-file">
                            <a href="<?php echo $courrier['lien_courrier'] ?>" target="_blank" class="lienCourrier">
                                <img src="../../public/images/pdf.png" alt="">
                            </a>
                        </output>
                        <output name="" class="basket-expediteur"><?php echo $courrier['expediteur'] ?></output>
                        <output name="" class="basket-destinataire"><?php echo $courrier['destinataire'] ?></output>
                        <output name="" class="basket-date"><?php echo $courrier['date_suppression'] ?></output>
                        <output name="" class="basket-status"><?php echo $courrier['etat_courrier'] ?></output>
                        <div class="basket-action">
                            <a href="">
                                <input type="image" src="../images/details.png" alt="" title="détails">
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE TABLEAU DE BORD  -->

     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <script src="../../public/js/gestion-corbeille.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>