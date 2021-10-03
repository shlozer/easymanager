<?php
    session_start();
    require_once('connexion_bdd.php');    
    if (isset($_GET['vue_jour_jour']) && isset($_GET['vue_jour_mois']) && isset($_GET['vue_jour_annee']))
        {$direct_access_reconnexion = 'jour.php?vue_jour_jour='.$_GET['vue_jour_jour'].'&vue_jour_mois='.$_GET['vue_jour_mois'].'&vue_jour_annee='.$_GET['vue_jour_annee'];}
    else 
        {$direct_access_reconnexion = 'jour.php';}
    include('check_session_open.php'); 

?>

<!DOCTYPE html>
<html>
<head>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <?php
        //print_r ($_SESSION);
        //print_r ($_GET);
        
        //echo 'session_id'.$_SESSION['id'];
        //if (empty($_SESSION['id'])){echo 'session empty';}
        //if (isset($_SESSION['id'])){echo 'session isset';}
        //if ($_SESSION['id'] == 0){echo 'session ==0';}
        //if ($_SESSION['id'] === 0){echo 'session ===0';}
        if (isset($_GET['vue_jour_jour']) && isset($_GET['vue_jour_mois']) && isset($_GET['vue_jour_annee']))
        {
            $_SESSION['vue_jour']['jour'] = $_GET['vue_jour_jour'];
            $_SESSION['vue_jour']['mois'] = $_GET['vue_jour_mois'];
            $_SESSION['vue_jour']['annee'] = $_GET['vue_jour_annee'];
            //echo 'ici';
        }


        if (!isset($_SESSION['vue_jour']))
        {
            $date_auj= getdate();
            $_SESSION['vue_jour']['jour'] = $date_auj['mday'];
            $_SESSION['vue_jour']['mois'] = $date_auj['mon'];
            $_SESSION['vue_jour']['annee'] = $date_auj['year'];

        }

        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        $mois_fr = $mois_fr_tab[date("n", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] , $_SESSION['vue_jour']['annee']))];
        $jour_fr_tab = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"); 
        $jour_fr = $jour_fr_tab[date("w", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] , $_SESSION['vue_jour']['annee']))];
        echo '<title>Vue du '. $jour_fr . ' '. $_SESSION['vue_jour']['jour'] . ' '. $mois_fr . ' '. $_SESSION['vue_jour']['annee'].'</title>';
    ?>
    <meta charset="UTF-8">
    <meta name="description" content="Visualisation des tâches du jour choisi">
    <meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
    <meta name="author" content="Chelomo ZERBIB">
    <link rel="stylesheet" href="header-style.css">
    <link rel="stylesheet" href="footer-style.css">
    <link rel="stylesheet" href="styles/jour-style.css">
</head>
<body>  
<?php
    include('header.php');
?>    
<?php 
    $jour_fr_maj_tab = array("DIMANCHE","LUNDI","MARDI","MERCREDI","JEUDI","VENDREDI","SAMEDI");
    $jour_fr_maj = $jour_fr_maj_tab[date("w", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] , $_SESSION['vue_jour']['annee']))];    
    $mois_fr_tab_maj = array("","JANVIER","FEVRIER","MARS","AVRIL","MAI","JUIN","JUILLET","AOUT","SEPTEMBRE","OCTOBRE","NOVEMBRE","DECEMBRE"); 
    $mois_fr_maj = $mois_fr_tab_maj[date("n", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] , $_SESSION['vue_jour']['annee']))];
?>
<h2 id="titre_vue_jour"><?php echo $jour_fr_maj.' '.$_SESSION['vue_jour']['jour'].' '.$mois_fr_maj . ' '. $_SESSION['vue_jour']['annee'];?></h3>
<div id="trois_objets_principaux">
<!-- boutons fleche gauche(jour precedent) -->    
<div id="objet_1">
<a id = "fleche_jour_precedent" href="jour.php?vue_jour_jour=<?php echo date("j", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] - 1, $_SESSION['vue_jour']['annee'])).'&';?>vue_jour_mois=<?php echo date("n", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] - 1, $_SESSION['vue_jour']['annee'])).'&';?>vue_jour_annee=<?php echo date("Y", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] - 1, $_SESSION['vue_jour']['annee']));?>">
<img src="/images/fleche_jour_precedent.png" alt="jour_precedent"/></a>
</div>

<?php
$debut_evt_min = date('Y-m-d H:i:s', mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'], $_SESSION['vue_jour']['annee']));
$fin_evt_max = date('Y-m-d H:i:s', mktime(23, 59, 59, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'], $_SESSION['vue_jour']['annee']));


if ($_SESSION['id'] != 0)
{   $req = $bdd->prepare('SELECT * FROM evt_jour AS e, users AS u   
                WHERE e.id = :id and e.id = u.id and e.debut_evt_jour >= :debut_evt_min_sql and e.fin_evt_jour <= :fin_evt_max_sql ORDER BY e.debut_evt_jour ASC');
    $req->execute(array(
    'id' => $_SESSION['id'],
    'debut_evt_min_sql' => $debut_evt_min,
    'fin_evt_max_sql'   => $fin_evt_max));
}    
else{
    $req = $bdd->prepare('SELECT * FROM evt_jour AS e, users AS u 
                  WHERE e.id = u.id and e.debut_evt_jour >= :debut_evt_min_sql and e.fin_evt_jour <= :fin_evt_max_sql ORDER BY e.id ASC, e.debut_evt_jour ASC');
    $req->execute(array(
        'debut_evt_min_sql' => $debut_evt_min,
        'fin_evt_max_sql'   => $fin_evt_max));
}
echo '<div id="objet_2">';
echo '<p id ="vue_jour_p_principal">';
$nb_de_taches = 0;
$id_actuel = null;
while ($req_taches_par_id = $req -> fetch(PDO::FETCH_ASSOC))
{


    if ($id_actuel != $req_taches_par_id['id'])
    {
        if ($id_actuel != null)
        {echo '<br /></div>';} 
        echo '<div class="vue_jour_fetch_id">';
        echo '<div class ="vue_jour_user_fetched">'; 
        echo $req_taches_par_id['user'].'('.$req_taches_par_id['nom'].' '.$req_taches_par_id['prenom'].')'.'</div>';
    }
    echo '<div class = "vue_jour_case_fetched">';
    echo '<a href="tache.php?cod_evt='.$req_taches_par_id['cod_evt'].'">';
    echo '<div class = "vue_jour_case_titre_fetched">';
    echo $req_taches_par_id['titre'] . '</div>';
    echo '<div class = "vue_jour_case_heures_fetched">';
    echo substr($req_taches_par_id['debut_evt_jour'], 11, 5) . ' -> ' . substr($req_taches_par_id['fin_evt_jour'], 11, 5) . '</div></a></div>'; 
    $id_actuel = $req_taches_par_id['id'];
    $nb_de_taches ++;
}
//echo '<br />'.$nb_de_taches;
if ($nb_de_taches > 0)
{
    echo '</div>';
}
else
{
    echo '<strong>Aucune tâche</strong>';
}
echo '</p></div>';




?>
<!-- bouton fleche doite (jour suivant)-->
<div id="objet_3">
<a id = "fleche_jour_suivant" href="jour.php?vue_jour_jour=<?php echo date("j", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] + 1, $_SESSION['vue_jour']['annee'])).'&';?>vue_jour_mois=<?php echo date("n", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] + 1, $_SESSION['vue_jour']['annee'])).'&';?>vue_jour_annee=<?php echo date("Y", mktime(0, 0, 0, $_SESSION['vue_jour']['mois'], $_SESSION['vue_jour']['jour'] + 1, $_SESSION['vue_jour']['annee']));?>">             
<img src="/images/fleche_jour_suivant.png" alt="jour_suivant"/></a></div></div>
<div id="deux_boutons_bas">
<div id="bouton_creation_tache"><a  href ="creation_tache.php"><img src="images/ajout_tache.png" /></a>
</div>
<!-- aller à un mois défini par l'utilisateur-->
<div id="choix_mois_annee">
<form method="get" action="mois.php">
    Aller à:
    <select name="vue_mois_mois" id="vue_mois_mois">
    <?php 
        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
           echo '<option value="';
           echo $i.'"';
           if ((isset($_SESSION['vue_jour']['mois'])) && (isset($_SESSION['vue_jour']['annee']))
            && (date("n",mktime(0,0,0,$_SESSION['vue_jour']['mois'],1,$_SESSION['vue_jour']['annee'])) == $i))
           {echo ' selected';}
           echo '>'.$mois_fr_tab[$i].'</option>';
       }
       ?>
    </select>
    <select name="vue_mois_annee" id="vue_mois_annee">
    <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'"';
            if ((isset($_SESSION['vue_jour']['mois'])) &&(isset($_SESSION['vue_jour']['annee']))
            && (date("Y",mktime(0,0,0,$_SESSION['vue_jour']['mois'],1,$_SESSION['vue_jour']['annee'])) == $i))
           {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
    ?>
    </select>
    <input type="submit" value="-->" />
</form>
</div>



</div>
<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>

<?php
    include('footer.php');
?>  
</body>          
