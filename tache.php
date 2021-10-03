<?php
    session_start();
    require_once('connexion_bdd.php');  
    if (isset($_GET['cod_evt']))
        {$direct_access_reconnexion = 'tache.php?cod_evt='.$_GET['cod_evt'];}
    else 
        {$direct_access_reconnexion = 'mois.php';}
    include('check_session_open.php'); 

?>

<!DOCTYPE html>
<html>
<head>
<title>Tâche</title>
<meta charset="UTF-8">
<meta name="description" content="Visualisation de la tâche choisi">
<meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
<meta name="author" content="Chelomo ZERBIB">
<link rel="stylesheet" href="header-style.css">
<link rel="stylesheet" href="footer-style.css">
<link rel="stylesheet" href="styles/tache-style.css">
</head>
<body>
<?php
    include('header.php');
?>    
<?php

    $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
    $jour_fr_tab = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"); 
    $req = $bdd->prepare('SELECT *
                            , DATE_FORMAT(debut_evt, "%w") AS debut_evt_jsem_format
                            , DATE_FORMAT(debut_evt, "%e") AS debut_evt_j_format 
                            , DATE_FORMAT(debut_evt, "%c") AS debut_evt_m_format
                            , DATE_FORMAT(debut_evt, "%Y") AS debut_evt_a_format
                            , DATE_FORMAT(debut_evt, "%H") AS debut_evt_h_format
                            , DATE_FORMAT(debut_evt, "%i") AS debut_evt_min_format
                            , DATE_FORMAT(fin_evt, "%w") AS fin_evt_jsem_format
                            , DATE_FORMAT(fin_evt, "%e") AS fin_evt_j_format 
                            , DATE_FORMAT(fin_evt, "%c") AS fin_evt_m_format
                            , DATE_FORMAT(fin_evt, "%Y") AS fin_evt_a_format
                            , DATE_FORMAT(fin_evt, "%H") AS fin_evt_h_format
                            , DATE_FORMAT(fin_evt, "%i") AS fin_evt_min_format
                            FROM evt WHERE cod_evt = :cod_evt ');
    $req->execute(array(
        'cod_evt' => $_GET['cod_evt']));
    $req_evt_format = $req-> fetch();        
    $req2 = $bdd->prepare('SELECT * FROM users WHERE id = :id ');
    $req2->execute(array(
        'id' => $req_evt_format['id']));
    $req_user_format = $req2-> fetch();
?>    

<div id="corps_tache">
<h3 id="titre_tache"><?php echo $req_evt_format['titre'].'</h3>';?> 
<p> attribué à<?php echo ' <strong>'.$req_user_format['user'].'</strong> ('.$req_user_format['prenom'].' '.$req_user_format['nom'].')</p>';?> 
<p> Début:<?php echo ''.$jour_fr_tab[$req_evt_format['debut_evt_jsem_format']].' '.$req_evt_format['debut_evt_j_format'].' '.$mois_fr_tab[$req_evt_format['debut_evt_m_format']].' '.$req_evt_format['debut_evt_a_format'].'   '.$req_evt_format['debut_evt_h_format'].':'.$req_evt_format['debut_evt_min_format'];?>
<?php echo ' =>  ';?>Fin:<?php echo ''.$jour_fr_tab[$req_evt_format['fin_evt_jsem_format']].' '.$req_evt_format['fin_evt_j_format'].' '.$mois_fr_tab[$req_evt_format['fin_evt_m_format']].' '.$req_evt_format['fin_evt_a_format'].'   '.$req_evt_format['fin_evt_h_format'].':'.$req_evt_format['fin_evt_min_format'];?></p>
<p> Description </p>
<p><?php echo $req_evt_format['description_tache'];?></p></div>


<div id ="boutons_modif_supp_creation">
<!-- modif tache-->
<a id="bouton_modif_tache" href ="modif_tache.php?cod_evt=<?php echo $_GET['cod_evt'];?>">Modifier cette tâche</a>
<!-- supp tache-->
<a id="bouton_supp_tache" href ="supp_tache.php?cod_evt=<?php echo $_GET['cod_evt'];?>&amp;confirmation_supp=0">Supprimer cette tache</a>
<!-- nouvelle tache-->
<a id="bouton_creation_tache" href ="creation_tache.php">Créer une nouvelle tâche</a>
</div>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>
<?php
    include('footer.php');
?>  
</body>          