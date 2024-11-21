<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>utilisateur supprimer</title>
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
            <section class="page-content-deleted-user" id="page-admin">
                <div class="block-user">
                    <div class="deleted-user">
                        <div class="title-deleted-user">
                            <p>Code</p>
                            <p>Intitulé</p>
                            <p>Opération</p>
                        </div>
                        <div class="element-deleted-user">
                            <p>#njkqs</p>
                            <p>nsdi</p>
                            <form action="" method="post">
                                <input type="image" src="../images/tout-selectionner.png" alt="" class="element-deleted-user__btn-select" alt="tout selectionner">
                            </form>
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