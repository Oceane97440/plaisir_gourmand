<?php
session_start(); 
include('../includes/connect_bdd.php');

if(isset($_SESSION['id']) AND !empty($_SESSION['id']) ){
    $sessionid = intval($_SESSION['id']); 
    
     //get actif == 1 lorsque le cuisinier va cliquer sur le lien ça va passer en désactiver
     if (isset($_GET['actif']) AND !empty($_GET['actif']) ) {
        $actif = intval($_GET['actif']);
         if( $_GET['actif'] == true){
           
            $activation = $bdd->prepare('UPDATE ateliers SET actif = 0 WHERE id = ?');
            $activation->execute(array($actif));
           
         }
    }  
    
    if (isset($_GET['actif']) AND !empty($_GET['actif']) ) {
         if( $_GET['actif'] == false){
              
                $activation = $bdd->prepare('UPDATE ateliers SET actif = 1 WHERE id = ?');
                $activation->execute(array($actif));  
                var_dump($_GET['actif']==0 );
                print_r($activation);
            }
    }    
    include('../includes/head.php');

?>


 <div class="container">
    <div class="row">
        <div class="table-responsive col-12">
               <!-- Affichage liste sous forme de tableaux -->
    <table class="table table-striped">

<tr class="d-flex">
    <th class="col-1">Titre</th> 
    <th class="col-1" >Image</th>
    <th class="col-2">Description</th>
    <th class="col-1">Date</th>
    <th class="col-1">Horaire de début</th>
    <th class="col-1">Durée</th>
    <th class="col-1">Places Dispo</th>
    <th class="col-1">Places Réservées</th>
    <th class="col-1">Prix</th>
    <th class="col-1">Action</th>
    <th class="col-1">Statut actuel</th>
</tr>

<!-- Connexion BDD, table ateliers -->

<?php $reponse = $bdd -> prepare('SELECT a.id AS id_atelier, a.titre, a.destination, a.descriptif, a.date_atelier, a.debut, a.duree, a.places_dispo, a.places_reserver, a.prix, a.actif FROM ateliers a INNER JOIN utilisateurs_ateliers au ON au.id_atelier= a.id
                                WHERE id_utilisateur=?');
$reponse->execute(array($_SESSION['id']));

while ($donnees = $reponse -> fetch())
{
    if($donnees['actif']== 1 ):
        $donnees['actif']='actif';
    
?>

    <!-- Affichage en php des données -->
    <tr class="d-flex">
        <td class="col-1"><?php echo $donnees['titre'];?></td>
        <td class="col-1"><img src="<?php echo $donnees['destination'];?>" class="img-fluid"></td>
        <td class="col-2"><?php echo $donnees['descriptif'];?></td>
        <td class="col-1"><?php echo $donnees['date_atelier'];?></td>
        <td class="col-1"><?php echo $donnees['debut'];?></td>
        <td class="col-1"><?php echo $donnees['duree'];?></td>
        <td class="col-1"><?php echo $donnees['places_dispo'];?></td>
        <td class="col-1"><?php echo $donnees['places_reserver'];?></td>
        <td class="col-1"><?php echo $donnees['prix'];?></td>
        <td class="col-1"><a href="edit_atelier.php?edit=<?php echo $donnees['id_atelier'];?>">Editer</a></td>
        <td class="col-1"><?php echo $donnees['actif'];?></td>
    </tr>

<?php
     else : 
        $donnees['actif'] ="Désactiver";
?>
     <!-- Affichage en php des données -->
     <tr class="d-flex">
        <td class="col-1"><?php echo $donnees['titre'];?></td>
        <td class="col-1"><img class="img-fluid" src="<?php echo $donnees['destination'];?>"></td>
        <td class="col-2"><?php echo $donnees['descriptif'];?></td>
        <td class="col-1"><?php echo $donnees['date_atelier'];?></td>
        <td class="col-1"><?php echo $donnees['debut'];?></td>
        <td class="col-1"><?php echo $donnees['duree'];?></td>
        <td class="col-1"><?php echo $donnees['places_dispo'];?></td>
        <td class="col-1"><?php echo $donnees['places_reserver'];?></td>
        <td class="col-1"><?php echo $donnees['prix'];?></td>
        <td class="col-1"><a href="edit_atelier.php?edit=<?php echo $donnees['id_atelier'];?>">Editer</a></td>
        <td class="col-1"><?php echo $donnees['actif'];?></td>
    </tr>
     <?php endif; } ?>
<?php } ?>
</table>
        </div>
    </div>
 </div>
  
 <?php include('../includes/footer.php');?>

</body>
</html>
