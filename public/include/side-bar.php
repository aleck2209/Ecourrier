<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <!-- DEBUT SIDE-BAR -->
    <nav class="side-bar">
        <div>
            <div class="logo">
                <figcaption>
                    <img src="../images/logo.png" alt="log de la BEAC">
                </figcaption>
                <p>BEAC</p>
            </div>
            <div class="gestion-courrier">
                <ul>
                    <li>
                        <a class="sidebar" href="tableau-bord.php">Tableau de Bord</a>
                    </li>
                    <li>
                        <a href="courrier-interne.php">Enregistrer Courrier</a>
                    </li>
                    <li>
                        <a href="#">Courrier Enregistrer</a>
                        <!-- DEBUT DROPDOWN -->
                        <ul class="gestion-courrier__dropdown">
                            <li>
                                <a class="sidebar" href="#">Courrier Départ</a>
                            </li>
                            <li>
                                <a class="sidebar" href="#">Courrier Arrivé</a>
                            </li>
                        </ul>
                        <!-- FIN DROPDOWN -->
                    </li>
                    <li>
                        <a href="notification-recu.php">Notifications</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="gestion-utilisateur">
            <ul>
                <li>
                    <a class="sidebar" href="compte.php">Compte</a>
                </li>
                <li>
                    <a class="sidebar" href="#">Déconnexion</a>
                </li>
                <li>
                    <a class="sidebar" href="corbeille.php">Corbeille</a>
                </li>
                <li>
                    <a href="liste-utilisateur.php">Administration</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- FIN SIDEBAR -->

    <!-- DEBUT DE SCRIPT JS -->
    <script src="../js/sidebar-active.js"></script>
    <!-- FIN DE SCRIPT JS -->
</body>
</html>