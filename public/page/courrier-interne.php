<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Courrier Interne</title>
</head>
<body>
    <?php
        // TRAITEMENT DE L'ENREGISTREMENT DU COURRIER
        require('../../Traitement/Controle/insertionCourrierdepart-interne.php');

        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE ENREGISTRER COURRIER -->
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
            <section class="page-content-save-mail" id="page-save-mail">
                <h2>Enregistrer Courrier</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-save-mail" id="form-save-mail" enctype="multipart/form-data">
                    <input type="hidden" name="etat_interne_externe" value="interne">
                    <!-- DEBUT INFORMATION COURRIER -->
                    <div class="fields-save-mail">
                        <div class="field half-space">
                            <label for="objet">Objet du courrier<span> *</span></label>
                            <textarea name="Objet_du_courrier" id="objet" rows="2" required></textarea>
                        </div>
                        <div class="field half-space">
                            <label for="reference">Réference du courrier</label>
                            <textarea name="Reference" id="reference" rows="2"></textarea>
                        </div>
                        <div class="field">
                            <label for="numeroOrdre">Numéro d'ordre <span>*</span></label>
                            <input type="text" name="numero_ordre" id="numeroOrdre" required>
                        </div>
                        <div class="field">
                            <label for="dateEnregistrement">Date d'enregistrement <span>*</span></label>
                            <input type="datetime-local" name="dateEnregistrement" id="dateEnregistrement" required>
                        </div>
                        <div class="field">
                            <label for="categorie">Catégorie</label>
                            <select name="categorie" id="categorie">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION COURRIER -->
                    <!-- DEBUT CORRESPONDANT -->
                    <div class="fields-save-mail">
                        <div class="field">
                            <label for="typeCourrier">Type de courrier</label>
                            <select name="type_courrier" id="typeCourrier" onchange="afficherCorrespondant()">
                                <option value="courrier départ">Départ</option>
                                <option value="courrier arrivé">Arrivé</option>
                            </select>
                        </div>
                        <div class="field" id="fieldExpediteur">
                            <label for="expediteur">Expéditeur <span>*</span></label>
                            <input type="text" name="expediteur_courrierArv" id="expediteur" oninput="completion()">
                            <div class="autocomplete-list" id="listAutocompleteExpediteur"></div>
                        </div>
                        <div class="field" id="fieldDestinataire">
                            <label for="destinataire">Destinataire <span>*</span></label>
                            <input type="text" name="destinataire" id="destinataire" oninput="completion()">
                            <div class="autocomplete-list" id="listAutocompleteDestinataire"></div>
                        </div>
                        <div class="field">
                            <label for="copie">En copie(cc)</label>
                            <input type="text" name="copie_courrier" id="copie" oninput="completionCopie()">
                            <div class="autocomplete-list" id="listAutocompleteCopie"></div>
                        </div>
                        <div class="field closed-folds">
                            <label for="plisFerme">Plis fermé</label>
                            <select name="etat_plis_ferme" id="plisFerme" onchange="afficherPlis()">
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN CORRESPONDANT -->
                    <!-- DEBUT INFORMATIONS SUPPLEMENTAIRES -->
                    <div class="fields-save-mail" id="infoSupp">
                    <div class="field">
                            <label for="typeDocument">Type de document <span>*</span></label>
                            <input type="text" name="Type_document" id="typeDocument" required>
                        </div>
                        <div class="field">
                            <label for="">importer courrier scanné</label>
                            <input type="file" name="fichier" id="">
                        </div>   
                        <div class="field">
                            <label for="numPieceJointe">nombre de pièce jointe</label>
                            <input type="number" name="nombre_joins" id="numPieceJointe" min="0" max="10">
                        </div>   
                        <div class="field">
                            <label for="">importer pièces jointe</label>
                            <input type="file" name="fichiers_joints[]" id="" multiple>
                        </div>    
                    </div>
                    <!-- FIN INFORMATION SUPPLEMENTAIRE -->
                    <div class="btn-save-mail">
                        <input type="submit" value="Enregistrer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
            </section>
        </main>
    </div>

    <?php
        // APPEL DE LA PAGE MESSAGE
        require('../../public/include/message.php');
        
        // APPEL DE L'ALERTE QU'ON SORT SANS ENREGISTRER
        require('../../public/include/alerte.php');
    ?>
    <!-- FIN PAGE ENREGISTRER COURRIER -->
    <!-- DEBUT SCRIPT JS -->
    <script src="../../public/js/message.js"></script>
    <script src="../../public/js/afficher-nav.js"></script>
    <script src="../../public/js/afficher-header.js"></script>
    <script src="../../public/js/gestion-form-interne.js"></script>
    <!-- FIN SCRIPT JS -->  
</body>
</html>