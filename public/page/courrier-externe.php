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

        // VARIABLE BUREAU D'ORDRE
        $bureauOrdre = "BO";
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
            <section class="page-content-save-mail page-content-save-mail-depart" id="page-save-mail">
                <h2>Enregistrer Courrier Départ</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-save-mail">
                    <input type="hidden" name="" value="externe">
                    <!-- DEBUT INFORMATION COURRIER -->
                    <div class="fields-save-mail">
                        <div class="field half-space">
                            <label for="objet">Objet du courrier<span> *</span></label>
                            <textarea name="" id="objet" rows="2" required></textarea>
                        </div>
                        <div class="field half-space">
                            <label for="reference">Réference du courrier</label>
                            <textarea name="" id="reference" rows="2"></textarea>
                        </div>
                        <div class="field">
                            <label for="numeroOrdre">Numéro d'ordre <span>*</span></label>
                            <input type="text" name="" id="numeroOrdre" required>
                        </div>
                        <div class="field">
                            <label for="dateEnregistrement">Date d'enregistrement <span>*</span></label>
                            <input type="datetime-local" name="" id="dateEnregistrement" required>
                        </div>
                        <div class="field">
                            <label for="categorie">Catégorie</label>
                            <select name="" id="categorie">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION COURRIER -->
                    <!-- DEBUT CORRESPONDANT -->
                    <div class="fields-save-mail">
                        <div class="field">
                            <label for="destinataire">Destinataire <span>*</span></label>
                            <input type="text" name="" id="destinataire">
                        </div>
                        <div class="field">
                            <label for="copie">En copie(cc)</label>
                            <input type="text" name="" id="copie">
                        </div>
                        <div class="field closed-folds">
                            <label for="plisFerme">Plis fermé</label>
                            <select name="" id="plisFerme" onchange="afficherPlis()">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN CORRESPONDANT -->
                    <!-- DEBUT INFORMATIONS SUPPLEMENTAIRES -->
                    <div class="fields-save-mail" id="infoSupp">
                    <div class="field">
                            <label for="typeDocument">Type de document <span>*</span></label>
                            <input type="text" name="" id="typeDocument" required>
                        </div>
                        <div class="field">
                            <label for="">importer courrier scanné</label>
                            <input type="file" name="" id="">
                        </div>   
                        <div class="field">
                            <label for="">importer pièces jointe</label>
                            <input type="file" name="" id="" multiple>
                        </div>   
                    </div>
                    <!-- FIN INFORMATION SUPPLEMENTAIRE -->
                    <div class="btn-save-mail">
                        <input type="submit" value="Envoyer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
            </section>
            <section class="page-content-save-mail page-content-save-mail-arrive" id="page-save-mail">
                <h2>Enregistrer Courrier Arrivé</h2>
                <!-- DEBUT DU FORMULAIRE -->
                <form action="" method="post" class="form-save-mail">
                    <input type="hidden" name="" value="externe">
                    <input type="hidden" name="" value="<?php
                    // PARAMETRE POUR RECONNAITRE QUE C'EST UN AGENT DU BUREAU D'ORDRE 
                    echo $bureauOrdre 
                    ?>" id="bureauOrdre">
                    <!-- DEBUT INFORMATION COURRIER -->
                    <div class="fields-save-mail">
                        <div class="field half-space">
                            <label for="objetArrive">Objet du courrier<span> *</span></label>
                            <textarea name="" id="objetArrive" rows="2" required></textarea>
                        </div>
                        <div class="field half-space">
                            <label for="referenceArrive">Réference du courrier</label>
                            <textarea name="" id="referenceArrive" rows="2"></textarea>
                        </div>
                        <div class="field">
                            <label for="numeroOrdreArrive">Numéro d'ordre <span>*</span></label>
                            <input type="text" name="" id="numeroOrdreArrive" required>
                        </div>
                        <div class="field">
                            <label for="dateEnregistrementArrive">Date d'enregistrement <span>*</span></label>
                            <input type="datetime-local" name="" id="dateEnregistrementArrive" required>
                        </div>
                        <div class="field">
                            <label for="categorieArrive">Catégorie</label>
                            <select name="" id="categorieArrive">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN INFORMATION COURRIER -->
                    <!-- DEBUT CORRESPONDANT -->
                    <div class="fields-save-mail">
                        <div class="field">
                            <label for="expediteurArrive">Expéditeur <span>*</span></label>
                            <input type="text" name="" id="expediteurArrive">
                        </div>
                        <div class="field">
                            <label for="destinataireArrive">Destinataire <span>*</span></label>
                            <input type="text" name="" id="destinataireArrive">
                        </div>
                        <div class="field closed-folds">
                            <label for="plisFermeArrive">Plis fermé</label>
                            <select name="" id="plisFermeArrive" onchange="afficherPlisArrive()">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIN CORRESPONDANT -->
                    <!-- DEBUT INFORMATIONS SUPPLEMENTAIRES -->
                    <div class="fields-save-mail" id="infoSuppArrive">
                    <div class="field">
                            <label for="typeDocumentArrive">Type de document <span>*</span></label>
                            <input type="text" name="" id="typeDocumentArrive" required>
                        </div>
                        <div class="field">
                            <label for="">importer courrier scanné</label>
                            <input type="file" name="" id="">
                        </div>   
                        <div class="field">
                            <label for="">importer pièces jointe</label>
                            <input type="file" name="" id="" multiple>
                        </div>   
                    </div>
                    <!-- FIN INFORMATION SUPPLEMENTAIRE -->
                    <div class="btn-save-mail">
                        <input type="submit" value="Envoyer">
                        <input type="reset" value="Annuler">
                    </div>
                </form>
            </section>
        </main>
    </div>
    <!-- FIN PAGE ENREGISTRER COURRIER -->
    <?php
        // APPEL DE LA SIDE-BAR
        require('../include/alerte.php');
    ?>

    <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-nav.js"></script>
    <script src="../js/afficher-header.js"></script>
    <script src="../js/afficher-plis.js"></script>
    <script src="../js/alert-save.js"></script>
    <script src="../js/reg-num-ordre-externe.js"></script>
    <script src="../js/cas-bo.js"></script>
    <!-- FIN SCRIPT JS -->  
</body>
</html>