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
                            <p class="detail-element__content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate fugit, ea quo eveniet magni similique ipsa facilis voluptatem totam nemo, .</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Référence</p>
                            <p class="detail-element__content"></p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Numéro d'ordre</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Date d'enregistrement</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Date d'émission</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Type de document</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">fichier du courrier</p>
                            <a href="" target="_blank" class="file"><img src="../../public/images/pdf.png" alt="fichier du courrier" title=""></a>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Nombre de pièce jointe</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Pièce(s) Jointe(s)</p>
                            <a href=" f" target="_blank" class="file"><img src="../../public/images/pdf.png" alt="pièce(s) jointe(s)" title=""></a>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Fichier réponse</p>
                            <a href="" target="_blank" class="file"><img src="../../public/images/pdf.png" alt="fichier reponse" title=""></a>
                        </div>
                    </div>
                </div>
                <div class="correspondence-mail">
                    <h3>Correspondance</h3>
                    <div class="container-detail">
                        <div class="detail-element">
                            <p class="detail-element__title">Destinataire</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Expéditeur</p>
                            <p class="detail-element__content">Copie</p>
                        </div>
                        <div class="detail-element not-required">
                            <p class="detail-element__title">Copie(s)</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Utilisateur</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                    </div>
                </div>
                <div class="property">
                    <h3>Propriétés</h3>
                    <div class="container-detail">
                        <div class="detail-element">
                            <p class="detail-element__title">Origine</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Type de Courrier</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Catégorie</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Plis fermé</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
                        </div>
                        <div class="detail-element">
                            <p class="detail-element__title">Statut</p>
                            <p class="detail-element__content">Lorem ipsum dolo</p>
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