<?php
session_start(); 
include('../includes/connect_bdd.php');

if(isset($_SESSION['id']) AND !empty($_SESSION['id']) ){
    $sessionid = intval($_SESSION['id']); 
   
    //var_dump($_POST);
    //var_dump($sessionid);
     //réservation
    if(!empty($_POST['form_reservation']) )
    {
        //stock mes valeurs des $_POST
        $id_atelier = intval($_POST['id_atelier']);
        $reservation = intval($_POST['places_reserver']);

        $req = $bdd->prepare('SELECT COUNT(*) AS count FROM utilisateurs_ateliers WHERE id_utilisateur=? AND id_atelier=?');
        $req->execute(array($sessionid ,$id_atelier));
        $count=$req->fetch();

        if($count['count'] == 0){
            if (!empty($id_atelier) AND $reservation >= 0)                                 
            { 
                //prepare insert into pour envoyer des données dans la BDD
                $ateliers = $bdd ->prepare('UPDATE ateliers SET places_reserver=? WHERE id=?');
                $ateliers ->execute(array($reservation + 1, $id_atelier));
            
                $reponse = $bdd->prepare('INSERT INTO utilisateurs_ateliers (id_utilisateur, id_atelier) VALUE (?,?)');
                $reponse->execute(array($sessionid, $id_atelier));

                $message ='réversavation ok';  
            }
            else
            {
            $message ='Saisi incorrect.';
            }
        } else{
            $message ='Vous avez déjà réserver';
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

    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
   
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
                <li class="nav-item"><a class="nav-link" href="../utilisateurs/profil_particulier.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="liste_particulier.php">Réservation</a></li>
            </ul>
            <ul class="navbar-nav ml-md-auto">
              
              <li class="nav-item"><a href="../includes/deco.php" class="nav-link"><span><i class="fa fa-user"></i></span> Déconnexion</a></li>
            </ul>
          </div>
    </nav>

</header>


    <!-- Affichage liste sous forme de tableaux -->
    <div class="container">
        <div class="row">

            <div class="table-responsive col-12">
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
                    <th class="col-1">Prix €</th>
                </tr>

                    <!-- Connexion BDD, table ateliers -->
            
                <?php $reponse = $bdd -> query('SELECT * FROM ateliers');
                    while ($donnees = $reponse -> fetch())
                    { 
                    if($donnees['actif'] == 1){
                    $donnees['actif'] = "s'inscrire";
                ?>

                <tr class="d-flex">
                    <td class="col-1"><?php echo $donnees['titre'];?></td>
                    <td class="col-1"><img src="<?php echo $donnees['image'];?>" class="img-fluid"></td>
                    <td class="col-2"><?php echo $donnees['descriptif'];?></td>
                    <td class="col-1"><?php echo $donnees['date_atelier'];?></td>
                    <td class="col-1"><?php echo $donnees['debut'];?></td>
                    <td class="col-1"><?php echo $donnees['duree'];?></td>
                    <td class="col-1"><?php echo $donnees['places_dispo'];?></td>
                    <td class="col-1"><?php echo (empty($donnees['places_reserver']))?"0":$donnees['places_reserver'];?></td>
                    <td class="col-1"><?php echo $donnees['prix'];?></td>
                    <td class="col-1">
                        <form action="liste_particulier.php" method="POST">
                            <input type="hidden" name="id_atelier" value="<?php echo $donnees['id'];?>">
                            <input type="hidden" name="places_reserver" value="<?php echo (empty($reservation))?0:$reservation; ?>">
                            <button type="submit" class="btn btn-primary mb-2" name="form_reservation" value="reserver un atelier"><?php echo $donnees['actif'];?></button>
                        </form>
                    </td>
                </tr>

                <?php 
                }
             } ?>
            </table>

            </div>
        </div>
    </div>

    <?php if(isset($message)){ ?>
         
         <span class="alert alert-warning">
             <?php echo $message; ?>
         </span>
     <?php } ?>
 
</body>
</html>
<?php } else{
    include('../includes/deco.php');
}?>
