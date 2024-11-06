<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cas d'un fichier</title>
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
            <section class="page-content-file-case">
                <h2>Importer fichier scanné</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-file-case">
                    <!-- DEBUT INFORMATION COURRIER -->
                    <h3>Information courrier</h3>
                    <div class="fields-file-case">
                        <div class="field">
                            <label for="">Choisi le fichier scanné</label>
                            <input type="file" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Objet du courrier</label>
                            <input type="text" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Type du document</label>
                            <select name="" id="">
                                <option value="lettre">lettre</option>
                                <option value="autres">autres</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION COURRIER -->
                    <!-- DEBUT EXPEDITEUR/DESTINATAIRES -->
                    <h3>Expéditeur/Destinataires</h3>
                    <div class="fields-file-case">
                        <div class="field">
                            <label for="">Expéditeur</label>
                            <input type="text" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Destinataires</label>
                            <input type="text" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Date d'arrivée</label>
                            <input type="date" name="" id="">
                        </div>
                    </div>
                    <!-- FIN EXPEDITEUR/DESTINATAIRE -->
                    <!-- DEBUT INFORMATIONS SUPPLEMENTAIRES -->
                    <h3>Pièce jointe</h3>
                    <div class="fields-file-case">
                        <div class="field">
                            <label for="">Pièces jointes</label>
                            <input type="file" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Reference</label>
                            <select name="" id="">
                                <option value="lettre">Arrivée</option>
                                <option value="autres">Départ</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION SUPPLEMENTAIRE -->
                    <!-- DEBUT METADONNEES -->
                    <h3>Metadonnées</h3>
                    <div class="fields-file-case">
                        <div class="field">
                            <label for="">Mots clés</label>
                            <input type="text" name="" id="">
                        </div>
                        <div class="field">
                            <label for="">Catégorie</label>
                            <select name="" id="">
                                <option value="lettre">Urgence</option>
                                <option value="autres">Départ</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN METADONNEES -->
                    <div class="btn-file-case">
                        <input type="submit" value="Envoyer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>