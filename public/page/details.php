<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Details</title>
</head>
<body>
    <?php
        // APPEL DE LA PAGE D'AFFICHAGE DES DETAILS DU COURRIER
        require('../../Traitement/Controle/DetailCourrier.php');

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
            <section class="page-content-detail">
                <h2>Détails du courrier</h2>
                <div class="information-mail">
                    <h3>Information courrier</h3>
                    <div class="container-detail">
                        <div class="detail-element">
                            <p class="detail-element__title">Objet</p>
                            <p class="detail-element__content"><?php echo $T1[0]['Objet_du_courrier'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Référence</p>
                            <p class="detail-element__content"><?php echo $T1[0]['Reference'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Numéro d'ordre</p>
                            <p class="detail-element__content"><?php echo $T1[0]['numero_ordre'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Date d'enregistrement</p>
                            <p class="detail-element__content"><?php echo $T1[0]['dateEnregistrement'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Date d'émission</p>
                            <p class="detail-element__content"><?php echo $T1[0]['date_mise_circulation'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Date dernière modification</p>
                            <p class="detail-element__content"><?php echo $T1[0]['date_derniere_modification'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Type de document</p>
                            <p class="detail-element__content"><?php echo $T1[0]['Type_document'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">fichier du courrier</p>
                            <a href="<?php echo $T1[0]['lien_courrier'] ?>" target="_blank" class="file"><img src="../../public/images/pdf.png" alt="fichier du courrier" title=""></a>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Nombre de pièce jointe</p>
                            <p class="detail-element__content">
                            <?php 
                                if ($T1[0]['nombre_de_fichiers_joins']) {
                                    echo $T1[0]['nombre_de_fichiers_joins'];
                                } else {
                                    echo '';
                                }
                            ?>
                            </p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Pièce(s) Jointe(s)</p>
                            <!-- VERIFICATION SI UN FICHIER JOINT A ETE AJOUTE OU PAS -->
                            <?php 
                            if ($T3) {  
                            foreach($tableau_lien as $lien) { ?>
                            <a href="
                            <?php echo $lien
                            ?>
                            " target="_blank" class="file"><img src="../../public/images/pdf.png" alt="pièce(s) jointe(s)" title=""></a>
                            <?php } 
                            } else { ?>
                            <a href="" class="file"></a>
                            <?php } ?>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Fichier réponse</p>
                            <a href="
                            <?php 
                                if ($T4) {
                                    echo $T4[0]['lienFichierReponse'];
                                } else {
                                    echo '';
                                } 
                            ?>
                            " target="_blank" class="file"><img src="../../public/images/pdf.png" alt="fichier reponse" title=""></a>
                        </div>
                    </div>
                </div>
                <div class="correspondence-mail">
                    <h3>Correspondance</h3>
                    <div class="container-detail">
                        <div class="detail-element">
                            <p class="detail-element__title">Destinataire</p>
                            <p class="detail-element__content"><?php echo $T1[0]['destinataire'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Expéditeur</p>
                            <p class="detail-element__content"><?php echo $T1[0]['expediteur'] ?></p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">En copie(s)</p>
                            <p class="detail-element__content"><?php echo $noms_copies ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Matricule enregistreur</p>
                            <p class="detail-element__content"><?php echo $T1[0]['Matricule'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Nom enregistreur</p>
                            <p class="detail-element__content"><?php echo $T1[0]['nom_enregistreur'].' '.$T1[0]['prenom_enregistreur'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="property">
                    <h3>Propriétés</h3>
                    <div class="container-detail">
                        <div class="detail-element">
                            <p class="detail-element__title">Origine</p>
                            <p class="detail-element__content"><?php echo $T1[0]['origine_courrier'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Type de Courrier</p>
                            <p class="detail-element__content"><?php echo $T1[0]['type_courrier'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Catégorie</p>
                            <p class="detail-element__content"><?php echo $T1[0]['categorie'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Plis fermé</p>
                            <p class="detail-element__content"><?php echo $T1[0]['plis_ferme'] ?></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Statut</p>
                            <p class="detail-element__content"><?php echo $T1[0]['etat_courrier'] ?></p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE TABLEAU DE BORD  -->
     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <script src="../../public/js/gestion-details.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>