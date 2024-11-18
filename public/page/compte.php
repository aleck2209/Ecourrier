<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>compte</title>
</head>
<body>
    <?php
        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE ENVOYER NOTIFICATION -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->
        <main class="main">
            <section class="page-content-account">
                <h2>Informations Compte</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-account">
                    <!-- DEBUT MODIFIER INFOS PERSONNELLES -->
                    <h3>Modifier les informations personnelles</h3>
                    <div class="fields-account">
                        <div class="field">
                            <label for="nom">Nom d'utilisateur</label>
                            <input type="text" name="" id="Nom" value="nom utilisateur">
                        </div>
                        <div class="field">
                            <label for="nom">Prenom</label>
                            <input type="text" name="" id="Nom" value="prenom">
                        </div>
                    </div>
                    <div class="btn-account">
                        <input type="submit" value="Enregistrer">
                    </div>
                    <!-- FIN MODIFIER INFOS PERSONNELLES -->
                </form>
                <form action="" method="post" class="form-account">
                    <!-- DEBUT MODIFIER MOT DE PASSE -->
                    <h3>Modifier votre mot de passe</h3>
                    <div class="fields-account">
                        <div class="field">
                            <label for="">Ancien mot de passe</label>
                            <input type="password" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Nouveau mot de passe</label>
                            <input type="password" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Confirmer mot de passe</label>
                            <input type="password" name="" id="">
                        </div>
                    </div>
                    <div class="btn-account">
                        <input type="submit" value="Modifier">
                    </div>
                    <!-- FIN MODIFIER MOT DE PASSE -->
                </form>
                <!-- FIN DU FORMULAIRE -->
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE ENVOYER NOTIFICATION -->
</body>
</html>