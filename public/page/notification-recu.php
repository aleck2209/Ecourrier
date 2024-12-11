<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Notification reçue</title>
</head>
<body>
    <?php
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
            <!-- DEBUT DE LA BARRE DE NAVIGATION -->
            <?php
                // APPEL DE LA BARRE DE NAVIGATION
                include('../include/nav-bar.php');
            ?>
            <!-- FIN DE LA BARRE DE NAVIGATION -->
            <section class="page-content-received-notif" id="page-notification">
                <div class="block-notif ">
                    <h2>Notifications reçues</h2>
                    <div class="received-notif">
                        <div class="title-received-notif">
                            <p>Objet notification</p>
                            <p>Identifiant</p>
                            <p>Destinataire</p>
                            <p>Date d'envoie</p>
                        </div>
                        <div class="element-receveid-notif">
                            <form action="" method="post">
                                <input type="image" alt="tout selectionner" src="../images/tout-selectionner.png" class="element-notif__btn-select"/>
                            </form>
                            <p>Objet</p>
                            <p>identifiant</p>
                            <p>destinataire</p>
                            <p>Date d'envoie</p>
                        </div>
                    </div>
                </div> 
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE LISTE NOTIFICATION -->
    <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-nav.js"></script>
    <script src="../js/afficher-header.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>