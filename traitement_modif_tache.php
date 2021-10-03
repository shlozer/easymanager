<?php
    session_start();
    require_once('connexion_bdd.php');    
    $direct_access_reconnexion = 'mois.php';
    include('check_session_open.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Traitement de modification d'une tache</title>
    <meta charset="UTF-8">
    <meta name="description" content="Traitement de modification d'une tâche">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="traitement_modif_tache-style.css">
</head>
<body>  
<?php
    include('header.php');
?> 
<?php
// verif dates valides
if (!checkdate($_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'], $_POST['modif_tache_deb_annee']))
{
    //$_SESSION['re_modif_tache_titre'] = $_POST['modif_tache_titre'];
    //$_SESSION['re_modif_tache_description'] = $_POST['modif_tache_description'];
    echo 'Date de début de tâche invalide';
    echo '<a href="modif_tache.php?re_modif_tache_titre='. $_POST['modif_tache_titre'] .'&re_modif_tache_description='. $_POST['modif_tache_description'] .'">Revenir</a> pour remodifier la tâche';
    include('footer.php');
    exit();
}
if (!checkdate($_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'], $_POST['modif_tache_fin_annee']))
{
    //$_SESSION['re_creation_tache_titre'] = $_POST['creation_tache_titre'];
    //$_SESSION['re_creation_tache_description'] = $_POST['creation_tache_description'];
    echo 'Date de fin de tâche invalide';
    echo '<a href="modif_tache.php?re_modif_tache_titre='. $_POST['modif_tache_titre'] .'&re_modif_tache_description='. $_POST['modif_tache_description'] .'">Revenir</a> pour remodifier la tâche';
    include('footer.php');
    exit();
}
// verif date de debut avant date de fin
if (mktime($_POST['modif_tache_deb_heure'], $_POST['modif_tache_deb_min'], 0, 
    $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'], $_POST['modif_tache_deb_annee'])
    > mktime($_POST['modif_tache_fin_heure'], $_POST['modif_tache_fin_min'], 0, 
    $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'], $_POST['modif_tache_fin_annee']))
{   
    //$_SESSION['re_creation_tache_titre'] = $_POST['creation_tache_titre'];
    //$_SESSION['re_creation_tache_description'] = $_POST['creation_tache_description'];
    echo 'Début de tâche après fin de tâche';
    echo '<a href="modif_tache.php?re_modif_tache_titre='. $_POST['modif_tache_titre'] .'&re_modif_tache_description='. $_POST['modif_tache_description'] .'">Revenir</a> pour remodifier la tâche';
    include('footer.php');
    exit();
}
// modif dans evt
$req = $bdd->prepare('UPDATE evt SET id=:id,
                                     titre = :titre,
                                     description_tache = :description_tache,
                                     debut_evt = :debut_evt,
                                     fin_evt = :fin_evt
                    WHERE cod_evt = :cod_evt');
$req->execute(array(
                'id' => $_POST['modif_tache_id'],
                'titre' => $_POST['modif_tache_titre'],
                'description_tache' => $_POST['modif_tache_description'],
                'debut_evt' => date("Y-m-d H:i:s", 
                mktime($_POST['modif_tache_deb_heure'], $_POST['modif_tache_deb_min'], 0,
                        $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee'])),
                'fin_evt' => date("Y-m-d H:i:s", 
                mktime($_POST['modif_tache_fin_heure'], $_POST['modif_tache_fin_min'], 0,
                        $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee'])),
                'cod_evt' => $_SESSION['cod_evt_traitement_modif_tache']        
                ));

$req =array();
// ici on commence par supprimer tout le cod_evt de evt_jour 
//et ensuite on reinsere normalement
$req2 = $bdd->prepare('DELETE FROM evt_jour WHERE cod_evt = :cod_evt');
$req2->execute(array('cod_evt' => $_SESSION['cod_evt_traitement_modif_tache']));
$req2 =array();
// insertion dans evt_jour
$req3 = $bdd->prepare('INSERT INTO evt_jour (cod_evt, id, titre, description_tache, debut_evt_jour, fin_evt_jour) 
                        VALUES (:cod_evt, :id, :titre, :description_tache, :debut_evt_jour, :fin_evt_jour)');
print_r ($req);print_r ($req2);print_r ($req3);
print_r ($_POST);print_r ($_SESSION);
//echo '<br />'.date("Y-m-d H:i:s", mktime($_POST['modif_tache_deb_heure'], $_POST['modif_tache_deb_min'], 0, $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee']));
//echo '<br />'.date("Y-m-d H:i:s", mktime($_POST['modif_tache_fin_heure'], $_POST['modif_tache_fin_min'], 0, $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee']));
// cas où la tache est sur la meme journee donc une seule insertion
if (mktime(23, 59, 0,
    $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee']) ==
    mktime(23, 59, 0, 
    $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee']))
{
    $req3->execute(array(
    'cod_evt' => $_SESSION['cod_evt_traitement_modif_tache'],
    'id' => $_POST['modif_tache_id'],
    'titre' => $_POST['modif_tache_titre'],
    'description_tache' => $_POST['modif_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['modif_tache_deb_heure'], $_POST['modif_tache_deb_min'], 0,
            $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['modif_tache_fin_heure'], $_POST['modif_tache_fin_min'], 0, 
            $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee']))));        
}
// cas ou la tache s'etale sur plus d'une journee
else
{
// premiere insertion avec heure de fin a 23:59 du premier jour    
    //$fin_evt_jour_temp = date("Y-m-d H:i:s", mktime(23, 59, 0,
    //$_POST['creation_tache_deb_mois'], $_POST['creation_tache_deb_jour'],$_POST['creation_tache_deb_annee']));
    $req3->execute(array(
    'cod_evt' => $_SESSION['cod_evt_traitement_modif_tache'],
    'id' => $_POST['modif_tache_id'],
    'titre' => $_POST['modif_tache_titre'],
    'description_tache' => $_POST['modif_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime($_POST['modif_tache_deb_heure'], $_POST['modif_tache_deb_min'], 0,
        $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", mktime(23, 59, 0,
        $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'],$_POST['modif_tache_deb_annee']))));
    $i = 1;
    
    // cas où la tache s'etale sur au moins trois jours donc insertions 0:00 23:59 jusqu'a la veille de la date de fin de tache
    while(mktime(23, 59, 0,
        $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'] + $i, $_POST['modif_tache_deb_annee']) 
        < mktime(23, 59, 0,
        $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'], $_POST['modif_tache_fin_annee']))
    {
        $req3->execute(array(
            'cod_evt' => $_SESSION['cod_evt_traitement_modif_tache'],
            'id' => $_POST['modif_tache_id'],
            'titre' => $_POST['modif_tache_titre'],
            'description_tache' => $_POST['modif_tache_description'],
            'debut_evt_jour' => date("Y-m-d H:i:s", 
            mktime(0, 0, 0,$_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'] + $i, $_POST['modif_tache_deb_annee'])),
            'fin_evt_jour' => date("Y-m-d H:i:s", 
            mktime(23, 59, 0, $_POST['modif_tache_deb_mois'], $_POST['modif_tache_deb_jour'] + $i, $_POST['modif_tache_deb_annee']))));
        $i++;
    }
    // derniere insertion avec debut a 0:00 et fin = a fin de tache    
    $req3->execute(array(
    'cod_evt' => $_SESSION['cod_evt_traitement_modif_tache'],
    'id' => $_POST['modif_tache_id'],
    'titre' => $_POST['modif_tache_titre'],
    'description_tache' => $_POST['modif_tache_description'],
    'debut_evt_jour' => date("Y-m-d H:i:s", 
    mktime(0, 0, 0, $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee'])),
    'fin_evt_jour' => date("Y-m-d H:i:s", mktime($_POST['modif_tache_fin_heure'], $_POST['modif_tache_fin_min'], 0,
        $_POST['modif_tache_fin_mois'], $_POST['modif_tache_fin_jour'],$_POST['modif_tache_fin_annee']))));
    
}


?>
<p> Tâche modifiée</p>



<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>
<a id ="retour_vue_tache" href ="tache.php?cod_evt=<?php echo $_SESSION['cod_evt_traitement_modif_tache'];?>">Retour à la vue tâche</a>
<?php
    include('footer.php');
?>  
</body>          
