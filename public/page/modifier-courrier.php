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
        //   require_once('../../Traitement/Controle/DetailCourrier.php');
        require("../../Traitement/Controle/modificationCourrier.php");

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
                <form action="" method="post" enctype="multipart/form-data" class="form-update-mail">
                    <input type="hidden" name="idCourrier" value="<?php echo $idCourrier; ?>">
                    <input type="hidden" name="typeCourrier" value="<?php echo $typeCourrier; ?>">
                    <input type="hidden" name="numero_ordre" value="<?php echo ($T1[0]['numero_ordre']); ?>">
                    <input type="hidden" name="plis_ferme" value="<?php echo ($T1[0]['plis_ferme']); ?>" >
                    <input type="hidden" name="origine_courrier" value="<?php echo ($T1[0]['origine_courrier']); ?>" >
                    <div class="fields-update-mail">
                        <div class="field half-space">
                            <label for="objet">Objet du courrier</label>
                            <textarea name="objet" id="objet" rows="2"><?php echo $T1[0]['Objet_du_courrier'] ?></textarea>
                        </div>
                        <div class="field half-space">
                            <label for="reference">Réference du courrier</label>
                            <textarea name="reference" id="reference" rows="2"><?php echo $T1[0]['Reference'] ?></textarea>
                        </div>
                        <div class="field">
                            <label for="destinataire">Destinataire</label>
                            <input type="text" name="destinataire" id="destinataire" value="<?php echo $T1[0]['destinataire'] ?>" oninput="completion()">
                            <div class="autocomplete-list" id="listAutocompleteDestinataire"></div>
                        </div>
                        <div class="field">
                            <label for="copie">En copie(s)</label>
                            <input type="text" name="destinataires_copies" id="copie" value="<?php echo $noms_copies ?>"  oninput="completionCopie()">
                            <div class="autocomplete-list" id="listAutocompleteCopie"></div>
                        </div>
                        <div class="field">
                            <label for="typeDocument">Type de document</label>
                            <input type="text" name="Type_document" id="typeDocument" value="<?php echo $T1[0]['Type_document'] ?>">
                        </div>
                        <div class="field">
                            <label for="statut">Statut</label>
                            <input type="text" name="etat_courrier" id="statut" value="<?php echo $T1[0]['etat_courrier'] ?>">
                        </div>
                        <div class="field">
                            <label for="categorie">Catégorie</label>
                            <select name="categorie" id="categorie">
                                <option id="optionUpdate" value="<?php echo $T1[0]['categorie'] ?>"><?php echo $T1[0]['categorie'] ?></option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="signature">Signature</label>
                            <input type="text" name="signature_gouverneur" id="signature" value="<?php echo ($T1[0]['signature_gouverneur']) ?>">
                        </div>
                    </div>
                    <div class="fields-update-mail">
                        <div class="update-element not-required">
                            <p class="update-element__title">fichier enregistré</p>
                            <a href="<?php echo $T1[0]['lien_courrier'] ?>" target="_blank" class="update-file">
                                <img src="../../public/images/pdf.png"  alt="fichier du courrier">
                            </a>
                        </div>
                        <div class="field">
                            <label for="" id="fichierEnregistre"></label>
                            <input type="file" name="fichier" id="">
                        </div>
                    </div>
                    <div class="btn-update-mail">
                        <input type="submit" value="Modifier">
                    </div>
                </form>
            </section>
        </main>
    </div>

    <?php
        // APPEL DE LA PAGE MESSAGE
        require('../../public/include/message.php');

        // APPEL DE L'ALERTE QUAND ON SORT SANS ENREGISTRER
        require('../../public/include/alerte.php');
    ?>
    <!-- FIN DE LA PAGE MODIFIER COURRIER  -->
     <!-- DEBUT SCRIPT JS -->
    <script src="../../public/js/afficher-header.js"></script>
    <script src="../../public/js/gestion-modifier.js"></script>
    <script src="../../public/js/message.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>