<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Liste notifications</title>
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
            <section class="page-content-list-notif" id="page-notification">
                <div class="block-notif ">
                    <h2>Notifications envoyées</h2>
                    <div class="list-notif">
                        <div class="title-list-notif">
                            <p>Objet notification</p>
                            <p>Identifiant</p>
                            <p>Envoyer</p>
                            <p>Date d'envoie</p>
                        </div>
                        <div class="element-notif">
                            <p>Objet</p>
                            <p>identifiant</p>
                            <p>Envoyer</p>
                            <p>Date d'envoie</p>
                        </div>
                    </div>
                </div>
                <div class="block-notif">
                    <h2>Rappels Crées</h2>
                    <div class="list-reminder">
                        <div class="title-list-reminder">
                            <p>Objet rappel</p>
                            <p>Identifiant</p>
                            <p>Fréquence rappel</p>
                            <p>Date rappel</p>
                        </div>
                        <div class="element-reminder">
                            <p>Objet</p>
                            <p>Identifiant</p>
                            <p>nombre</p>
                            <p>date</p>
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