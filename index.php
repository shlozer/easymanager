<?php
    session_start();
    require_once('connexion_bdd.php');  
?>
<!DOCTYPE html>
<html>
<head>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Accueil EasyManager</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gestion des tâches">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="/styles/header-index-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/accueil-style.css">

</head>
<body>
<?php
    include('header-index.php');
?>
<div id='milieu_page'>
<h2>Bienvenue sur votre outil EasyManager</h2> 
<div id='partie_texte'>
<?php
    /*echo 'user_comp'.$_SESSION['user_comp'].'</br>';
    echo 'pass_comp'.$_SESSION['pass_comp'].'</br>';*/
    print_r($_SESSION);
    echo '<br />';
    /*if ((isset($_SESSION['user']) || isset($_SESSION['pass'])))*/ 
    //echo  '<br />' . $_SESSION['bad_pass'];
    //if (($_SESSION['bad_pass'] = 1) && isset($_SESSION['bad_pass']))
    if (isset($_SESSION['bad_pass']) && ($_SESSION['bad_pass'] == 1))
    {
        echo 'Mauvaise combinaison user/mot de passe'.'</br>';
    }
// on vide la session pour éviter les résidus de variable qui vont buguer
    $_SESSION = array();
    //$_SESSION['premiere_connexion'] = 'ok';
    $_SESSION['direct_access_reconnexion'] = 'mois.php';

?>    
<form action="mois.php" method="post" >
    <p class='formulaire_entree'>
    Veuillez entrez vos identifiants de connexion</p></br>
    <fieldset>
    <p class='formulaire_entree'>
    <label for="user_comp" >User:</label>
    <input type="text" name="user_comp" required/></br>
    <label for="pass_comp" >Pass:</label>
    <input type="password" name="pass_comp"  required/></br>
    <input type="submit" value="Valider" id='bouton_valider'/>
    </p>
    </fieldset>
</form>
</div>
</div>
<?php
    include('footer.php');
?>
</body>

</html>
