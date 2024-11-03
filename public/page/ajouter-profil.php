<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Ajouter Profil</title>
</head>
<body>
    <?php
        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>
    <!-- DEBUT DE LA PAGE AJOUTER PROFIL -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php 
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->
        <main class="main">
            <!-- DEBUT DE LA BARRE DE NAVIGATION -->
            <?php
                include('../include/nav-bar.php');
            ?>
            <!-- FIN DE LA BARRE DE NAVIGATION -->
            <section class="page-content-add-profil">
                <h2>Ajouter un profil</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-add-profil">
                    <h3>Informations profil</h3>
                    <div class="fields-add-profil">
                        <div class="field">
                            <label for="code">Code*</label>
                            <input type="text" name="" id="code" required>
                        </div>
                        <div class="field">
                            <label for="nom">Nom*</label>
                            <input type="text" name="" id="nom" required>
                        </div>
                    </div>
                    <div class="btn-add-profil">
                        <input type="submit" value="Enregistrer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
                <!-- FIN DU FORMULAIRE -->
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE AJOUTER PROFIL -->
</body>
</html>