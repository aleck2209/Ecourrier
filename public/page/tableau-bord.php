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



      <!-- DEBUT DE LA PAGE filtrer courrier -->   
       <?php
        include("../../Traitement/Controle/filtreCourrier.php");

?>
<!-- FIN DE LA PAGE filtrer courrier  -->

        <main class="main">
            <section class="page-content-dashboard" id="page-user">
                <div class="filter-block">
                    <div class="search-block">
                        <form action="" method="post" class="form-search-key-word" id="">
                        <input type="hidden" name="form_type4" value="form1">  
                        <h3 for="">Mot clé</h3>
                            <div class="fields-key-word">
                                <input type="search" name="searchKeyword" id="" placeholder="Objet, Destinataire...">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-key-word__btn-search" name="">
                            </div>
                        </form>
                        <form action="" method="post" class="form-search-date">
                        <input type="hidden" name="form_type3" value="form1">
                            <h3>Période de recherche</h3>
                            <div class="fields-date">
                                <input type="date" name="startDate" id="">
                                <span>À</span>
                                <input type="date" name="endDate" id="">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-date__btn-search" name="">
                            </div>
                        </form>
                    </div>
                    <div class="sort-filter-block">
                        <form action="" method="post" class="form-sort" id="form-sort">
                            <h3>Option de tri</h3>
                            <input type="hidden" name="form_type1" value="form1">
                            <div class="fields-sort">
                                <select name="sortType" id="" onchange="submitSort()">
                                    <option value="">[type de tri]</option>
                                    <option value="date">Date</option> 
                                    <option value="objet">Objet</option>
                                    <option value="Numéro">Numéro</option>
                                </select>
                                <select name="sortOrder" id="">
                                    <option value="decroissant">Décroissant</option>
                                    <option value="croissant">Croissant</option>   
                                </select>
                            </div>
                        </form>
                        <form action="" method="post" class="form-filter" id="form-filter">
                            <h3>Option de filtre</h3>
                            <div class="fields-filter">
                            <input type="hidden" name="form_type2" value="form1">
                                <select name="Origine" id="" onchange="submitFilter()" >
                                    <option value="">[Origine]</option>
                                    <option value="courrier externe">Externe</option>
                                    <option value="courrier interne">Interne</option>
                                </select>
                                <select name="priority" id="" onchange="submitFilter()">
                                    <option value="">[Niveau priorité]</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="normal">Non urgent</option>
                                </select>
                                <select name="typeCourrier" id="" onchange="submitFilter()">
                                    <option value="">[type courrier]</option>
                                    <option value="courrier arrive">Arrivé</option>
                                    <option value="courrier départ">Départ</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="list-mail">
                    <div class="title-dashboard-mail">
                        <p class="dashboard-number">Numéro</p>
                        <p class="dasboard-object">Objet</p>
                        <p class="dashboard-file">Fichiers</p>
                        <p class="dashboard-service">Destinataire</p>
                        <p class="dashboard-correspondant">Correspondant</p>
                        <p class="dashboard-date">Date</p>
                        <p class="dashboard-status">Statut</p>
                        <p class="dashboard-action">Opérations</p>
                    </div>
                    <?php foreach($courriers as $courrier) { ?>
                        <div class="element-dashboard-mail">
                            <output name="" class="dashboard-number"><?php echo $courrier['numero_ordre'] ?></output>
                            <output name="" class="dasboard-object"><?php echo $courrier['objet_du_courrier'] ?></output>
                            <output name="" class="dashboard-file"><a href="<?php echo ($courrier['lien_courrier']) ?>" target="_blank"><img src="../../public/images/pdf.png" alt=""></a></output>
                            <output name="" class="dashboard-service"><?php echo $courrier['destinataire'] ?></output>
                            <output name="" class="dashboard-correspondant"><?php echo $courrier['type_courrier'] ?></output>
                            <output name="" class="dashboard-date"><?php echo $courrier['dateEnregistrement'] ?></output>
                            <output name="" class="dashboard-status"><?php echo $courrier['etat_courrier'] ?></output>
                            <div class="dashboard-action">
                                <a href="../../Traitement/Controle/DetailCourrier.php?$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>">
                                <input type="image" src="../images/details.png" alt="">
                                </a>
                                <a href="../../Publique2/page/formulaireUpdate.php$idCourrier=<?=$courrier['idCourrier']?>&& $typeCourrier=<?=$courrier['type_courrier']?>">
                                <input type="image" src="../images/modifier.png" alt="">
                                </a>
                                <input type="image" src="../images/historique.png" alt="">
                                <input type="image" src="../images/supprimer.png" alt="" title="supprimer">
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE TABLEAU DE BORD  -->
    

<!-- inclusion de la page filtrer courrier -->




     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <script src="../../public/js/gestion-tableau-bord.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>