<html>
<!--    <p id="titre_header">Outil EasyManager
    <span id = "bouton_deconnexion"><a href="index.php">Déconnexion</a></span>
    </p>-->
<div id="header_total">

<div id="header1">
<div><a id="bouton_gestion_users"href ="gestion_users.php">Gestion des users</a></div>

<div id="header_partie_droite">
<div id="bienvenue_user">
Bienvenue <?php if (isset($_SESSION['prenom']) && isset($_SESSION['nom']) && isset($_SESSION['user'])) 
    {echo $_SESSION['prenom'] . ' '. $_SESSION['prenom']. ' ('.$_SESSION['user'].')';} ?></div>
<div id="bouton_deconnexion"><a href="index.php">Déconnexion</a></div></div>
</div>

<div id="titre_header">Outil EasyManager</div>

</div>
        
</html>