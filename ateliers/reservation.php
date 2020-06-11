<?php
session_start(); 
include('../includes/connect_bdd.php');

//ici tu dois vérifier si $_SESSION existe
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) ){
    if(isset($_GET['edit']) AND !empty($_GET['edit']) ){
        $sessionid = intval($_SESSION['id']); 

    if(!empty($_POST['form_ajout_ateliers']) )
    {
        //stock mes valeurs des $_POST
        $id_atelier = htmlspecialchars($_POST['id_atelier']);
        $reservation = intval($_POST['places_reserver']);
        
        if (!empty($id_atelier) AND !empty($reservation))                                    
        { 
            //prepare insert into pour envoyer des données dans la BDD
            $ateliers = $bdd ->prepare('UPDATE ateliers SET places_reserver=?');
            $ateliers ->execute(array($reservation + 1);
            $message ='donnees bien enregistrer!';  
            header('Location: liste.php');
        
            $reponse = $bdd->prepare('INSERT INTO utilisateurs_ateliers (id_utilisateur, id_atelier) VALUE (?,?)');
            $reponse->execute(array($sessionid, $id_atelier));

            $message ='donnees bien enregistrer!';  
            header('Location: liste.php');
        }
        else
        {
         $message ='Saisi incorrect.';
        }
    }

?>
