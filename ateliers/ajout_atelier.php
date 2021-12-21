<?php
session_start(); 
//connexion bdd
include('../includes/connect_bdd.php');

//ici tu dois vérifier si $_SESSION existe
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) ){
$sessionid = intval($_SESSION['id']); 
var_dump($sessionid);
$req=$bdd->query('SELECT * FROM ateliers');
//traitement formulaire


if(!empty($_POST['form_ajout_ateliers']) )
    {

        //stock mes valeurs des $_POST
        $titres = htmlspecialchars($_POST['ajout_titres']);
        $descriptif = htmlspecialchars($_POST['ajout_descriptif']);
        $date = htmlspecialchars(date("Y-m-d", strtotime($_POST['ajout_date'])) );
        $times = htmlspecialchars($_POST['ajout_times']); //null; 
        $duree = htmlspecialchars($_POST['ajout_duree']); //null; 
        $dispo = htmlspecialchars($_POST['ajout_dispo']);
        $reserver = 0 ;
        $prix = htmlspecialchars($_POST['ajout_prix']);
        $nom_img = htmlspecialchars($_POST['ajout_nom_image']);
        $actif = intval($_POST['ajout_radio']);

        if(!empty($_FILES)){ 
        //indentifie le fichier et son nom
        $file_name=$_FILES['fichier']['name'];
        //Extention du fhier ex:.png
        $file_extension=strrchr($file_name,".");

        $file_tmp_name=$_FILES['fichier']['tmp_name'];
        $file_dest ='../images/'.$file_name; //envoie les img dans le dossier files
        //Fichier autoriser à etre un envoyer
        $extension_auto=array('.PNG','.png','.jpg');
        
        //Vérifier existence et si non vide
        
        if (!empty($titres) AND !empty($descriptif) AND !empty($date) AND !empty($times) AND !empty($duree) AND !empty($dispo) AND isset($reserver) AND !empty($prix) AND isset($actif) )                                    
        { //in_array verif si la valeur fait parti du tableau  
         

            if(in_array($file_extension,$extension_auto))
            {
                if(move_uploaded_file($file_tmp_name,$file_dest))
                {
                    //prepare insert into pour envoyer des données dans la BDD
                    $ateliers = $bdd ->prepare('INSERT INTO ateliers (titre, descriptif, date_atelier, debut, duree, places_dispo, places_reserver, prix ,destination,nom_image, actif, id_cuisinier,utilisateurs_ateliers_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
                    $ateliers ->execute(array($titres, $descriptif,$date,$times,$duree ,$dispo,$reserver,$prix,$file_dest,$nom_img,$actif, $sessionid,NULL) );
                    var_dump($ateliers);
                    $id_atelier = $bdd->lastInsertId();

                    $reponse = $bdd->prepare('INSERT INTO utilisateurs_ateliers (id_utilisateur, id_atelier) VALUE (?,?)');
                    $reponse->execute(array($_SESSION['id'], $id_atelier));
                    var_dump($reponse);

                    $message ='donnees bien enregistrer!';  
                    header('Location: liste.php');
                }else{
                    echo'Error';
                }
            } else{echo 'Seuls les fichiers img sont autorisés';}
        }
        else
        {
         $message ='Saisi incorrect.';
        }
        }else{$message="Pas fichier"; }   

    }
   
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ateliers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    
</head>
<body>
   <!--NAV-->
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
    <!--FIN NAV-->

  <div class="col-md-4 col-sm-12 mx-auto">
        <h4>Ajouter Ateliers</h4>
        

        <form action="ajout_atelier.php" method="POST" enctype="multipart/form-data">
            <!-- Titre -->
            <div class="form-group">
                <label>Titre : </label>
                <input class="form-control"  type="text" name="ajout_titres" placeholder="titres">
            </div>
            <!-- nom image -->
            
            <div class="form-group">
                <label >Nom image : </label>
                <input class="form-control" type="text" name="ajout_nom_image" placeholder="Nom image">
            </div>
    
            <!-- image -->
            <div class="form-group">
                <label >Image : </label>
                <input class="form-control"  type="file" name="fichier" >
            </div>

            <!-- Description -->
            <div class="form-group">
                <label >descriptif : </label>
                <input class="form-control"    type="text" name="ajout_descriptif" placeholder="descriptif">
            </div>

            <!-- Date -->
            <div class="form-group">
                <label >Date : </label>
                <input class="form-control"  type="date" name="ajout_date" placeholder="date" >
            </div>

            <!-- Horaire de début -->
            <div class="form-group">
                <!-- to do : à rectifier le type de la date dans la BDD -->
                <label for="ajout_times">Horaire de debut : </label>
                <input class="form-control"  type="text" name="ajout_times" placeholder="01:00"  >
            </div>

            <!-- Durée -->
            <div class="form-group">
                <!-- to do : à rectifier le type de la date dans la BDD -->
                <label >Durée : </label>
                <input class="form-control"  type="text" name="ajout_duree" placeholder="01:00" >
            </div>

            <!-- Place Disponible -->
            <div class="form-group">
                <label>Place Dispo. : </label>
                <input class="form-control"  type="number" name="ajout_dispo" placeholder="place dispo">
            </div>

            <!-- Prix -->
            <div class="form-group">
                <label>Prix en € : </label>
                <input class="form-control"  type="number" name="ajout_prix" placeholder="prix" >
            </div>
            <!--actif -->
            <div class="form-row">
                <span class="mr-2">Statut atelier : </span>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="ajout_radio" value="1" id="actif1" required>
                    <label class="form-check-label" for="actif1"> actif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="ajout_radio" value="0"  id="actif2" required>
                    <label class="form-check-label"for="actif2"> désactif</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="form_ajout_ateliers" value="ajouter un ateliers">ajouter</button>
        </form>
    </div>
                
            
        
    
    <?php if(isset($message)){ ?>
         
        <span class="alert">
            <?php echo $message; ?>
        </span>
    <?php } ?>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php }else{
    include('../includes/deco.php');
}?>