<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modifier courrier</title>
</head>
<body>
    <?php
        // APPEL DE LA PAGE D'AFFICHAGE DES DETAILS DU COURRIER
        // require('../../Traitement/Controle/DetailCourrier.php');

        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE MODIFIER COURRIER -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->
        <main class="main">
            <section class="page-content-update-mail">
                <h2>Modifier courrier</h2>
                <form action="" method="post" class="form-update-mail">
                    <div class="fields-update-mail">
                        <div class="field half-space">
                            <label for="objet">Objet du courrier</label>
                            <textarea name="" id="objet" rows="2"></textarea>
                        </div>
                        <div class="field half-space">
                            <label for="reference">Réference du courrier</label>
                            <textarea name="" id="reference" rows="2"></textarea>
                        </div>
                        <div class="field">
                            <label for="destinataire">Destinataire</label>
                            <input type="text" name="" id="destinataire" oninput="completion()">
                            <div class="autocomplete-list" id="listAutocompleteDestinataire"></div>
                        </div>
                        <div class="field">
                            <label for="copie">En copie(s)</label>
                            <input type="text" name="" id="copie" oninput="completionCopie()">
                            <div class="autocomplete-list" id="listAutocompleteCopie"></div>
                        </div>
                        <div class="field">
                            <label for="typeDocument">Type de document</label>
                            <input type="text" name="" id="typeDocument">
                        </div>
                        <div class="field">
                            <label for="statut">Statut</label>
                            <input type="text" name="" id="statut">
                        </div>
                        <div class="field">
                            <label for="categorie">Catégorie</label>
                            <select name="" id="categorie">
                                <option id="optionUpdate" value=""></option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="signature">Signature</label>
                            <input type="text" name="" id="signature">
                        </div>
                    </div>
                    <div class="fields-update-mail">
                        <div class="field number-field">
                            <label for="numPieceJointe">nombre pièce jointe</label>
                            <input type="number" name="" id="numPieceJointe" min="0" max="10">
                        </div>
                        <div class="update-element not-required">
                            <p class="update-element__title">Pièce(s) Jointes(s)</p>
                            <a href="f" target="_blank" class="update-file">
                                <img src="../../public/images/pdf.png" alt="pièce(s) jointe(s)">
                            </a>
                        </div>
                        <div class="field">
                            <label for="" id="pieceJoint"></label>
                            <input type="file" name="" id="" multiple>
                        </div>
                    </div>
                    <div class="fields-update-mail">
                        <div class="update-element not-required">
                            <p class="update-element__title">fichier enregistré</p>
                            <a href="f" target="_blank" class="update-file">
                                <img src="../../public/images/pdf.png"  alt="fichier du courrier">
                            </a>
                        </div>
                        <div class="field">
                            <label for="" id="fichierEnregistre"></label>
                            <input type="file" name="" id="">
                        </div>
                    </div>
                    <div class="btn-update-mail">
                        <input type="submit" value="Modifier">
                    </div>
                </form>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE MODIFIER COURRIER  -->
     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <script src="../../public/js/gestion-modifier.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>