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

        // APPEL DU TABLEAUX POUR LES TESTS
        require('../include/tableau.php')
    ?>

    <!-- DEBUT DE LA PAGE TABLEAU DE BORD -->
    <div class="content">
        <!-- DEBUT DU HEADER -->
        <?php
        // APPEL DU HEADER
            include('../include/header.php');
        ?>
        <!-- FIN DU HEADER -->
        <main class="main">
            <section class="page-content-dashboard" id="page-user">
                <div class="filter-block">
                    <div class="search-block">
                        <form action="" method="post" class="form-search-key-word">
                            <h3 for="">Mot clé</h3>
                            <div class="fields-key-word">
                                <input type="search" name="" id="" placeholder="Objet, Destinataire...">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-key-word__btn-search">
                            </div>
                        </form>
                        <form action="" method="post" class="form-search-date">
                            <h3>Période de recherche</h3>
                            <div class="fields-date">
                                <input type="date" name="" id="">
                                <span>À</span>
                                <input type="date" name="" id="">
                                <input type="image" src="../images/recherche.png" alt="bouton rechercher" class="form-search-date__btn-search">
                            </div>
                        </form>
                    </div>
                    <div class="sort-filter-block">
                        <form action="" method="post" class="form-sort">
                            <h3>Option de tri</h3>
                            <div class="fields-sort">
                                <select name="" id="">
                                    <option value="">[type de tri]</option>
                                    <option value="date">Date</option>
                                    <option value="objet">Objet</option>
                                    <option value="Numéro">Numéro</option>
                                </select>
                                <select name="" id="">
                                    <option value="croissant">Croissant</option>
                                    <option value="decroissant">Décroissant</option>
                                </select>
                            </div>
                        </form>
                        <form action="" method="post" class="form-filter">
                            <h3>Option de filtre</h3>
                            <div class="fields-filter">
                                <select name="" id="">
                                    <option value="">[Origine]</option>
                                    <option value="externe">Externe</option>
                                    <option value="interne">Interne</option>
                                </select>
                                <select name="" id="">
                                    <option value="">[Niveau priorité]</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="nonUrgent">Non urgent</option>
                                </select>
                                <select name="" id="">
                                    <option value="">[type courrier]</option>
                                    <option value="arrive">Arrivé</option>
                                    <option value="depart">Départ</option>
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
                        <p class="dashboard-service">Service</p>
                        <p class="dashboard-correspondant">Correspondant</p>
                        <p class="dashboard-date">Date</p>
                        <p class="dashboard-status">Statut</p>
                        <p class="dashboard-action">Opérations</p>
                    </div>
                    <?php foreach($courriers as $courrier) { ?>
                        <div  class="element-dashboard-mail" id="element-dashboard-mail">
                            <output name="numero" class="dashboard-number"><?php echo $courrier['numéro']?></output>
                            <output name="" class="dasboard-object"><?php echo $courrier['objet']?></output>
                            <output name="" class="dashboard-file"><a href="<?php echo($courrier['fichiers'])?>" target="_blank"><img src="../../public/images/pdf.png" alt=""></a></output>
                            <output name="" class="dashboard-service"><?php echo $courrier['Service']?></output>
                            <output name="service" class="dashboard-correspondant"><?php echo $courrier['Correspondant']?></output>
                            <output name="correspondant" class="dashboard-date"><?php echo $courrier['Date']?></output>
                            <output name="status" class="dashboard-status"><?php echo $courrier['Statut']?></output>
                            <div class="dashboard-action">
                                <input type="image" src="../images/details.png" alt="" title="détails" name="details">
                                <input type="image" src="../images/modifier.png" alt="" title="modifier" name="modifier">
                                <input type="image" src="../images/historique.png" alt="" title="historique" name ="historique">
                                <input type="image" src="../images/supprimer.png" alt="" title="supprimer" name="supprimer">
                            </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>
    <!-- FIN DE LA PAGE TABLEAU DE BORD  -->
     <!-- DEBUT SCRIPT JS -->
    <script src="../js/afficher-header.js"></script>
    <!-- FIN SCRIPT JS -->
</body>
</html>