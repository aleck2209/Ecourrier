<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Envoyer Notification</title>
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
            <!-- DEBUT DE LA BARRE DE NAVIGATION -->
            <?php
                include('../include/nav-bar.php');
            ?>
            <!-- FIN DE LA BARRE DE NAVIGATION -->
            <section class="page-content-reminder" id="page-notification">
                <h2>Créer un rappel</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-reminder">
                    <div class="fields-reminder">
                        <div class="field">
                            <label for="nom">Objet du rappel</label>
                            <input type="text" name="" id="Nom">
                        </div>
                        <div class="field">
                            <label for="nom">Identifiant/Reference du courrier</label>
                            <input type="text" name="" id="Nom">
                        </div>
                    </div>
                    <!-- DEBUT DE LA PROGRAMMATION DE L'ENVOI -->
                    <h3>Rappel</h3>
                    <div class="fields-reminder">
                        <div class="field">
                            <label for="">Fréquence de rappel</label>
                            <select name="" id="">
                                <option value="Non">Une seule fois</option>
                                <option value="Oui">Oui</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="">Date envoi</label>
                            <input type="date" name="" id="Nom">
                        </div>
                    </div>
                    <!-- FIN DE LA PROGRAMMATION DE L'ENVOI -->
                    <div class="btn-reminder">
                        <input type="submit" value="Envoyer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
                <!-- FIN DU FORMULAIRE -->
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE ENVOYER NOTIFICATION -->
    
    <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-nav.js"></script>
    <script src="../js/afficher-header.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>