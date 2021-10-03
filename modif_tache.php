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
<title>Modification de tâche</title>
<meta charset="UTF-8">
<meta name="description" content="Modification de la tâche choisi">
<meta name="keywords" content="easymanager, gestion, gestion des taches, management, admin, users">
<meta name="author" content="Chelomo ZERBIB">
<link rel="stylesheet" href="header-style.css">
<link rel="stylesheet" href="footer-style.css">
<link rel="stylesheet" href="/styles/modif_tache-style.css">
</head>
<body>
<?php
    include('header.php');
?>  
<form method="post" action="traitement_modif_tache.php">
   <p>
   <?php
    // cette variable va permettre de transporter le cod_evt dans la page traitement
    // (le cod_evt n'est pas dans le formulaire)
    //echo '->'.$_SESSION['cod_evt_traitement_modif_tache'];
    if (isset($_GET['cod_evt']))
    {
        $_SESSION['cod_evt_traitement_modif_tache'] = $_GET['cod_evt'];
    }
    
    $req = $bdd->prepare('SELECT * 
                            , DATE_FORMAT(debut_evt, "%e") AS debut_evt_j_format 
                            , DATE_FORMAT(debut_evt, "%c") AS debut_evt_m_format
                            , DATE_FORMAT(debut_evt, "%Y") AS debut_evt_a_format
                            , DATE_FORMAT(debut_evt, "%H") AS debut_evt_h_format
                            , DATE_FORMAT(debut_evt, "%i") AS debut_evt_min_format
                            , DATE_FORMAT(fin_evt, "%e") AS fin_evt_j_format 
                            , DATE_FORMAT(fin_evt, "%c") AS fin_evt_m_format
                            , DATE_FORMAT(fin_evt, "%Y") AS fin_evt_a_format
                            , DATE_FORMAT(fin_evt, "%H") AS fin_evt_h_format
                            , DATE_FORMAT(fin_evt, "%i") AS fin_evt_min_format
                from evt where cod_evt =:cod_evt');
    $req->execute(array('cod_evt' => $_SESSION['cod_evt_traitement_modif_tache']));
    $req_l = $req -> fetch();
    if ($_SESSION['id'] == 0)
    {$req_user = $bdd->prepare('SELECT * FROM users');
        $req_user->execute();
    
    echo '<fieldset id="form-id"><legend>User</legend>';
    //echo '<label for="modif_tache_id">User</label>';
    echo '<select name="modif_tache_id" id="modif_tache_id">';
    while ($req_tous_user_l = $req_user -> fetch())
        {
            echo '<option value="' . $req_tous_user_l['id'].'"';
            if ($req_tous_user_l['id'] == $req_l['id']) {echo ' selected';}
            echo '>'.$req_tous_user_l['user'].'</option>';
        }
    echo '</select><br /></fieldset>';
    }
    else{
        echo '<fieldset id="form-id"><legend>User</legend>';
        //echo '<label for="modif_tache_id">User</label>';
        echo '<select name="modif_tache_id" id="modif_tache_id">';
        echo '<option value="'.$req_l['id']. '" selected></option>';
        echo '</select><br /></fieldset>';
    
    }
    
    ?>
    <fieldset id="form-contenu"><legend>Contenu</legend>
    <label for="modif_tache_titre">Titre</label> : <input type="text" name="modif_tache_titre" 
             value="<?php if (isset($_GET['re_modif_tache_titre']) && ($_GET['re_modif_tache_titre'] != ' ')) 
             {echo $_GET['re_modif_tache_titre'];} else {echo $req_l['titre'];}?>" 
             id="modif_tache_titre" size="90"/><br />
    <label for="modif_tache_description">Description</label> : <textarea name="modif_tache_description" 
             value="" id="modif_tache_description" cols ="90" rows="10"><?php if (isset($_GET['re_modif_tache_description']) && ($_GET['re_modif_tache_description'] != ' ')) {echo $_GET['re_modif_tache_description'];} else {echo $req_l['description_tache'];}?></textarea><br />
    </fieldset>
    <fieldset id="form-deb"><legend>Début</legend>
    <label for="modif_tache_deb_jour">Jour</label>
    <select name="modif_tache_deb_jour" id="modif_tache_deb_jour">
        <?php 
        for ($i = 1; $i <= 31; $i++) 
       {
           echo '<option value="';
           if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
           echo $i.'"';      
           if ($j == $req_l['debut_evt_j_format']) {echo ' selected';}
           echo '>'.$i.'</option>';
       }?>
    </select>
    <label for="modif_tache_deb_mois">Mois</label>
    <select name="modif_tache_deb_mois" id="modif_tache_deb_mois">
       <?php 
        $mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
           echo '<option value="';
           echo $i.'"';
           if ($i == $req_l['debut_evt_m_format']) {echo ' selected';}
           echo '>'.$mois_fr_tab[$i].'</option>';
       }
       ?>
    </select>
    <label for="modif_tache_deb_annee">Année</label>
    <select name="modif_tache_deb_annee" id="modif_tache_deb_annee">
    <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'"';
            if ($i ==$req_l['debut_evt_a_format']) {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
    ?>
    </select>
    <label for="modif_tache_deb_heure">Heure</label>
        <select name="modif_tache_deb_heure" id="modif_tache_deb_heure">
        <?php 
        for ($i = 0; $i <= 23; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
            echo $i.'"';
            if ($j == $req_l['debut_evt_h_format']) {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
        ?>
    </select>
    <label for="modif_tache_deb_min">Minute</label>
        <select name="modif_tache_deb_min" id="modif_tache_deb_min">
        <?php 
        for ($i = 0; $i <= 59; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
            echo $i.'"';
            if ($j == $req_l['debut_evt_min_format']) {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
        ?>
    </select><br />
    </fieldset>
    <fieldset  id="form-deb"><legend>Fin</legend>
    <label for="modif_tache_fin_jour">Jour</label>
    <select name="modif_tache_fin_jour" id="modif_tache_fin_jour">
        <?php 
        for ($i = 1; $i <= 31; $i++) 
       {
        echo '<option value="';
        if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
        echo $i.'"';      
        if ($j == $req_l['fin_evt_j_format']) {echo ' selected';}
        echo '>'.$i.'</option>';
    }?>
    </select>
    <label for="modif_tache_fin_mois">Mois</label>
    <select name="modif_tache_fin_mois" id="modif_tache_fin_mois">
        <?php 
        //$mois_fr_tab = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"); 
        for ($i = 1; $i <= 12; $i++) 
       {
        echo '<option value="';
        echo $i.'"';
        if ($i == $req_l['fin_evt_m_format']) {echo ' selected';}
        echo '>'.$mois_fr_tab[$i].'</option>';
    }
       ?>
    </select>
    <label for="modif_tache_fin_annee">Année</label>
    <select name="modif_tache_fin_annee" id="modif_tache_fin_annee">
        <?php 
        for ($i = 2010; $i <= 2030; $i++) 
        {
            echo '<option value="';
            echo $i.'"';
            if ($i == $req_l['fin_evt_a_format']) {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
        ?>
    </select>
    <label for="modif_tache_fin_heure">Heure</label>
        <select name="modif_tache_fin_heure" id="modif_tache_fin_heure">
        <?php 
        for ($i = 0; $i <= 23; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
            echo $i.'"';
            if ($j == $req_l['fin_evt_h_format']) {echo ' selected';}
            echo '>'.$i.'</option>';        } 
        ?>
    </select>
    <label for="modif_tache_fin_min">Minute</label>
        <select name="modif_tache_fin_min" id="modif_tache_fin_min">
        <?php 
        for ($i = 0; $i <= 59; $i++) 
        {
            echo '<option value="';
            if ($i <= 9) {echo '0'; $j = '0' . strval($i);} else {$j = strval($i);}
            echo $i.'"';
            if ($j == $req_l['fin_evt_min_format']) {echo ' selected';}
            echo '>'.$i.'</option>';
        } 
        ?>
    </select><br /> 
    </fieldset>

   </p>
   <input type="submit" value="Modifier" />
</form>    
 


  

<a id ="retour_vue_mois" href ="mois.php">Retour à la vue mois</a>
<a id ="retour_vue_jour" href ="jour.php">Retour à la vue jour</a>
<a id ="retour_vue_tache" href ="tache.php?cod_evt=
<?php if (isset($_GET['cod_evt'])) {echo $_GET['cod_evt'];} 
    else { echo $_SESSION['cod_evt_traitement_modif_tache'];}?>">Retour à la vue tâche</a>
<?php
    include('footer.php');
?>  
</body>    
