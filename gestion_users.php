<?php
    session_start();
    require_once('connexion_bdd.php');  
    $direct_access_reconnexion = 'gestion_users.php';
    include('check_session_open.php'); 

?>
<!DOCTYPE html>
<html>
<head>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Gestion des users</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gestion des users">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="/styles/header_gestion_users-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="/styles/gestion_users-style.css">

</head>
<body>
<?php
    include('header.php');
?>
<div id='milieu_page'>
<h2>Gestion des utilisateurs</h2> 
<div id='partie_centrale'>

    
<?php
if (isset($_SESSION['id']) && ($_SESSION['id'] >= 0))
{
if ($_SESSION['id'] == 0)
{?>
    <form method="post" action="traitement_creation_user.php">
    <fieldset id="form-creation"><legend>Création de user</legend>
    <label for="creation_user_user">User</label>
    <input type="text" name="creation_user_user" id="creation_user_user" size="25" required/>
    <label for="creation_user_pass">Pass</label>
    <input type="password" name="creation_user_pass" id="creation_user_pass" size="25" required/><br />
    <label for="creation_user_nom">Nom</label> 
    <input type="text" name="creation_user_nom" id="creation_user_nom" size="25" required/>
    <label for="creation_user_prenom">Prénom</label>
    <input type="text" name="creation_user_prenom" id="creation_user_prenom" size="25" required/><br />
    <label for="creation_user_mail">Mail</label>
    <input type="email" name="creation_user_mail" id="creation_user_mail" size="50" required/>
    <input type="submit" value="Créer" />
    </fieldset></form>
    
    <form method="post" action="traitement_supp_user.php">    
    <fieldset id="form-supp"><legend>Suppression de user</legend>
    <label for="supp_user_id">User</label>
    <select name="supp_user_id" id="supp_user_id">
    <?php
        $req = $bdd->prepare('SELECT * FROM users');
        $req->execute();
        while ($req_tous_user_l = $req -> fetch())
        {
            // on intersit de supprimer le user id = 0 qui est l'admin
            if ($req_tous_user_l['id'] != 0){
            echo '<option value="'.$req_tous_user_l['id'].'">'.$req_tous_user_l['user'].' ('.$req_tous_user_l['nom'].' '.$req_tous_user_l['prenom'].')'.'</option>';}
        }
    ?>
    </select>
    Supprimer toutes les tâches de ce user ?
    <input type="radio" name="supp_toutes_taches" value="oui" id="oui" /> <label for="oui">Oui</label>
    <input type="radio" name="supp_toutes_taches" value="non" id="non" /> <label for="non">Non</label>                     
    <input type="submit" value="Supprimer" /></fieldset></form>
    
    <form method="post" action="traitement_modif_user.php">
    <fieldset id="form-modif"><legend>Modification de mail user</legend>
    <label for="modif_user_id">User</label>
    <select name="modif_user_id" id="modif_user_id">
    <?php
        $req = $bdd->prepare('SELECT * FROM users');
        $req->execute();
        while ($req_tous_user_l = $req -> fetch())
        {
            echo '<option value="'.$req_tous_user_l['id'].'">'.$req_tous_user_l['user'].' ('.$req_tous_user_l['nom'].' '.$req_tous_user_l['prenom'].')'.'</option>';
        }
    ?>
    </select>
    <label for="modif_mail_user">Nouveau mail</label>
    <input type="email" name="modif_mail_user" id="modif_mail_user" size="50" required/>
    <input type="submit" value="Modifier" /></fieldset></form> 
<?php
}
else
{?>
    <form method="post" action="traitement_modif_user.php">
    <fieldset id="form-modif"><legend>Modification de mail user</legend>
    <label for="modif_mail_user">Nouveau mail</label>
    <input type="email" name="modif_mail_user" id="modif_mail_user" size="150" required/>
    <input type="submit" value="Modifier" /></fieldset></form> 
<?php
}
}?>
</div>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>
</div>
<?php
    include('footer.php');
?>
</body>

</html>