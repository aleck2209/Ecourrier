<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Tableau de Bord</title>
</head>
<body>
    <?php
        // APPEL DE LA SIDE-BAR
        require('../include/side-bar.php');
    ?>

    <!-- DEBUT DE LA PAGE TABLEAU DE BORD -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->

        <!-- DEBUT DE LA PAGE FILTRE COURRIER -->   
        <?php
            include("../../Traitement/Controle/filtreCourrier.php");
        ?>
        <!-- FIN DE LA PAGE FILTRE COURRIER  -->

        <main class="main">
            <section class="page-content-dashboard" id="page-user">
                <div class="filter-block">
                    <div class="search-block">
                        <form action="" method="post" class="form-search-key-word">
                        <input type="hidden" name="form_type4">  
                        <h3 for="motCle">Mot clé</h3>
                            <div class="fields-key-word">
                                <input type="search" name="searchKeyword" id="motCle" placeholder="Objet, Destinataire...">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-key-word__btn-search" onclick="searchKeyWord()">
                            </div>
                        </form>
                        <form action="" method="post" class="form-search-date">
                        <input type="hidden" name="form_type3">
                            <h3>Période de recherche</h3>
                            <div class="fields-date">
                                <input type="date" name="startDate" id="startDate">
                                <span>&nbsp;À&nbsp;</span>
                                <input type="date" name="endDate" id="endDate">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-date__btn-search" onclick="searchDate()">
                            </div>
                        </form>
                    </div>
                    <div class="sort-filter-block">
                        <form action="" method="post" class="form-sort" id="form-sort">
                            <h3>Option de tri</h3>
                            <input type="hidden" name="form_type1" value="form1">
                            <div class="fields-sort">
                                <select name="sortType" id="sortType" onchange="submitSort()">
                                    <option value="">[type de tri]</option>
                                    <option value="date">Date</option> 
                                    <option value="objet">Objet</option>
                                    <option value="Numéro">Numéro</option>
                                </select>
                                <select name="sortOrder" id="sortOrder">
                                    <option value="decroissant">Décroissant</option>
                                    <option value="croissant">Croissant</option>   
                                </select>
                            </div>
                        </form>
                        <form action="" method="post" class="form-filter" id="form-filter">
                            <h3>Option de filtre</h3>
                            <div class="fields-filter">
                                <input type="hidden" name="form_type2" value="form1">
                                <select name="Origine" id="">
                                    <option value="">[Origine]</option>
                                    <option value="courrier externe">Externe</option>
                                    <option value="courrier interne">Interne</option>
                                </select>
                                <select name="typeCourrier">
                                    <option value="">[Type courrier]</option>
                                    <option value="courrier arrive">Arrivé</option>
                                    <option value="courrier départ">Départ</option>
                                    <option value="copie courrier">Copie</option>
                                </select>
                                <select name="priority">
                                    <option value="">[Catégorie]</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="normal">Normal</option>
                                </select>
                                <select name="expedition">
                                    <option value="">[Expédition]</option>
                                    <option value="oui">oui</option>
                                    <option value="non">non</option>
                                </select>
                                <select name="signature">
                                    <option value="">[Signature]</option>
                                    <option value="oui">oui</option>
                                    <option value="non">non</option>
                                </select>
                                <input type="submit" name="" value="Tout afficher" onclick="allDisplay()">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="list-mail">
                    <div class="title-dashboard-mail">
                        <p class="dashboard-number">Numéro</p>
                        <p class="dashboard-object">Objet</p>
                        <p class="dashboard-file">Fichiers</p>
                        <p class="dashboard-expediteur">Expéditeur</p>
                        <p class="dashboard-destinataire">Destinataire</p>
                        <p class="dashboard-date">Date</p>
                        <p class="dashboard-status">Statut</p>
                        <p class="dashboard-action">Opérations</p>
                    </div>
                    <?php foreach($courriers as $courrier) { ?>
                        <div class="element-dashboard-mail">
                            <input type="hidden" name="" value="<?php echo $courrier['type_courrier'] ?>">
                            <output name="" class="dashboard-number"><?php echo $courrier['numero_ordre'] ?></output>
                            <output name="" class="dashboard-object"><?php echo $courrier['objet_du_courrier'] ?></output>
                            <output name="" class="dashboard-file">
                                <a href="<?php echo ($courrier['lien_courrier']) ?>" target="_blank" class="lienCourrier">
                                    <img src="../../public/images/pdf.png" alt="">
                                </a>
                            </output>
                            <output name="" class="dashboard-expediteur"><?php echo $courrier['expediteur'] ?></output>
                            <output name="" class="dashboard-destinataire"><?php echo $courrier['destinataire'] ?></output>
                            <output name="" class="dashboard-date"><?php echo $courrier['dateEnregistrement'] ?></output>
                            <output name="" class="dashboard-status"><?php echo $courrier['etat_courrier'] ?></output>
                            <div class="dashboard-action">
                                <a href="../../public/page/details.php?$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>">
                                    <input type="image" src="../images/details.png" alt="" title="détails">
                                </a>
                                <a href="../../public/page/modifier-courrier.php?$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>" class="btn-modifier">
                                    <input type="image" src="../images/modifier.png" alt="" title="modifier">
                                </a>
                                <a href="../../public/page/creer-rappel.php">
                                    <input type="image" src="../images/rappel.png" alt="" title="créer un rappel">
                                </a>
                                <a href="../../public/page/historique-courrier.php?$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>">
                                    <input type="image" src="../images/historique.png" alt="" title="historique">
                                </a>
                                <a href="../../Traitement/Controle/suppressioCourrier.php?$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>&& $numero_ordre=<?=$courrier['numero_ordre']?>" class="supprimer">
                                    <input type="image" src="../images/supprimer.png" alt="" title="supprimer">
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>

    <?php
        // APPEL DE L'ALERTE QUAND ON VEUT SUPPRIMER UN COURRIER
        require('../include/alerte-supprimer.php');
    ?>
    <!-- FIN DE LA PAGE TABLEAU DE BORD  -->

     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <script src="../../public/js/gestion-tableau-bord.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>