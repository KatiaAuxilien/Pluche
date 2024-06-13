<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pagetitle; ?></title>
    <link rel="icon" href="../ressources/img/ours.png"/>
    <link href="../ressources/css/style.css" rel="stylesheet" />
     <link href="../ressources/css/messageFlashStyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">Pluche</a>
        <div class="nav-links">
            <ul>
                <li>
                    <a href="controleurFrontal.php?action=panier"><img alt='Mon panier' title="Mon panier" src='/~auxilienk/projetdevweb/ressources/img/panier.png''/><div class='title'>Mon panier</div></a>
                </li>
                <li>
                    <a href="controleurFrontal.php?action=afficherListe&controleur=produit"><img alt='Produits' title="Produits" src='/~auxilienk/projetdevweb/ressources/img/item.png''/><div class='title'>Produits</div></a>
                </li>

                <?php
                use App\Pluche\Lib\ConnexionUtilisateur;
                if(!ConnexionUtilisateur::estConnecte()){
                    echo "
                <li>
                    <a href=\"controleurFrontal.php?action=afficherFormulaireCreation&controleur=utilisateur\"><img alt='Inscription' title='Inscription' src='/~auxilienk/projetdevweb/ressources/img/adduser.png''/><div class='title'> Inscription</div></a>
                </li>
                <li>
                    <a href=\"controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur\"><img alt='Connexion' title='Connexion' src='/~auxilienk/projetdevweb/ressources/img/login.png'/><div class='title'> Connexion</div></a>
                </li>";
                }
                if(ConnexionUtilisateur::estConnecte()){
                    $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                    $url = htmlentities($login);
                    if(ConnexionUtilisateur::estAdministrateur()){
                        echo "   
                    <li>
                    <a href=\"controleurFrontal.php?action=afficherListe&controleur=utilisateur\"><img alt='Gestion des utilisateurs' title='Gestion des utilisateurs' src='/~auxilienk/projetdevweb/ressources/img/listuser.png'/><div class='title'>  Gestion des utilisateurs</div></a>
                    </li>";
                        echo"
                    <li>
                    <a href=\"controleurFrontal.php?action=afficherFormulaireCreation&controleur=utilisateur\"><img alt='Nouvel utilisateur'' title='Nouvel utilisateur' src='/~auxilienk/projetdevweb/ressources/img/adduser.png'/><div class='title'> Nouvel utilisateur</div></a>
                    </li>";
                    }
                    echo "<li>
                    <a href=\"controleurFrontal.php?action=afficherListe&controleur=commande\"><img alt='Commandes' title='Commandes' src='/~auxilienk/projetdevweb/ressources/img/past.png'/><div class='title'> Commandes</div></a>
                    </li>";
                    echo "<li><a href=\"controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login={$url}\"\"><img alt='Mon profil'  title='Mon profil' src='/~auxilienk/projetdevweb/ressources/img/user.png'/><div class='title'> Mon profil</div></a>
                </li>";
                    echo "<li><a href=\"controleurFrontal.php?action=deconnecter&controleur=utilisateur&login={$url}\"\"><img alt='Deconnexion' title='Deconnexion' src='/~auxilienk/projetdevweb/ressources/img/logout.png'/><div class='title'> Déconnexion</div></a>
                </li>";
                }
                ?>
            </ul>
        </div>
        <img src="/~auxilienk/projetdevweb/ressources/img/menu.png" alt="menu hamburger" title="menu hamburger" class="menu-hamburger">
    </nav>
    <div>
        <!-- Ce qui suit parcours et affiche tout les messages flash -->
        <?php use App\Pluche\Lib\MessageFlash;
        $messagesFlash = MessageFlash::lireTousMessages();
        foreach($messagesFlash as $type => $messagesFlashPourUnType) {
            foreach ($messagesFlashPourUnType as $messageFlash) {
                echo "<div class=\"alert alert-{$type}\">$messageFlash</div>";
            }
        }
        ?>
    </div>
</header>
<script>
    const menuHamburger = document.querySelector(".menu-hamburger")
    const navLinks = document.querySelector(".nav-links")

    menuHamburger.addEventListener('click',()=>{
        navLinks.classList.toggle('mobile-menu')
    })
</script>
<main>

    <?php
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
    <footer>
        <div class="footer-content">
            <p>Site de e-commerce développé par Rémi VACHALDE, Mickael COULON et Katia AUXILIEN dans le cadre d'un projet étudiant.</p>
        </div>
        <ul class="socials">
            <li><a href="https://gitlabinfo.iutmontp.univ-montp2.fr/coulonm/projetdevweb"><img src="/~auxilienk/projetdevweb/ressources/img/GitLab.png" alt="lien gitlab"/></a></li>
        </ul>
        <div class="footer-bottom">
            <p>Crédits</p>
            <p>Icône lapin et panier de <span><a href="https://www.flaticon.com/fr/icone-gratuite/peluche_1238830?term=peluche&page=1&position=24&origin=tag&related_id=1238830">Freepik</a></span></p>
            <p>Icône ourson de <span><a href="https://www.flaticon.com/fr/icone-gratuite/ours-en-peluche_2729991">Surang</a></span></p>
            <p>Icône hamburger menu de <span><a href="https://www.flaticon.com/free-icon/menu_5259008">SeyfDesigner</a></span></p>
            <p>Icône panier de <span><a href="https://www.flaticon.com/fr/icone-gratuite/ajouter-un-panier_4175027">Uicon</a></span></p>
            <p>Icônes Ui par <span><a href="https://www.flaticon.com/uicons">Flaticon</a></span></p>
        </div>
    </footer>
</body>
</html>

