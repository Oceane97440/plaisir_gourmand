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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Liste Ateliers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#"><img src="../images/logo2.png"alt="" width="50" height="50"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../utilisateurs/profil.php">Profil</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Atelier
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../ateliers/ajout_atelier.php">Ajouter un atelier</a>
                            <a class="dropdown-item" href="../ateliers/liste.php">Voir votre liste atelier</a>
                            
                        
                        </div>
                        </li>
                </ul>
                <ul class="navbar-nav ml-md-auto">
                <li class="nav-item"><a href="../includes/deco.php" class="nav-link"><span><i class="fa fa-user"></i></span> Déconnexion</a></li>
                </ul>
            </div>
        </nav>
    </header> 

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

<?php $reponse = $bdd -> prepare('SELECT a.id AS id_atelier, a.titre, a.image, a.descriptif, a.date_atelier, a.debut, a.duree, a.places_dispo, a.places_reserver, a.prix, a.actif FROM ateliers a INNER JOIN utilisateurs_ateliers au ON au.id_atelier= a.id
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
        <td class="col-1"><img src="<?php echo $donnees['image'];?>" class="img-fluid"></td>
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
        <td class="col-1"><img class="img-fluid" src="<?php echo $donnees['image'];?>"></td>
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
       
</body>
</html>
