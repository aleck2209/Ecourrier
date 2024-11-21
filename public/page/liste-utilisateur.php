<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Liste des utilisateurs</title>
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
            <section class="page-content-list-user" id="page-admin">
                <div class="header-list-user">
                    <h2>Liste des utilisateurs</h2>
                    <form action="" method="post" class="form-search">
                        <input type="search" name="" id="" placeholder="rechercher...">
                        <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search__btn-search">
                    </form>
                </div> 
                <div class="list-user">
                    <div class="title-list-user">
                        <p>Login</p>
                        <p>Nom et prénom</p>
                        <p>Profil</p>
                        <p>Service</p>
                        <p>Opération</p>
                    </div>
                    <div class="element-list-user">
                        <p>#njkqs</p>
                        <p>nsdi</p>
                        <p>shblqjv</p>
                        <p>vsqj</p>
                        <form action="" method="post">
                            <input type="image" src="../images/tout-selectionner.png" alt="" class="element-list-user__btn-select" alt="tout selectionner">
                        </form>
                    </div>
                </div>
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