<?php
session_start(); 
include('../includes/connect_bdd.php');

//var_dump($_POST);
//ici tu dois vérifier si $_SESSION existe
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) )
{
    if(isset($_GET['edit']) AND !empty($_GET['edit']) )
    {
        $sessionid = intval($_SESSION['id']); 

        $req=$bdd->query('SELECT * FROM ateliers WHERE id ='.$_GET['edit']);
        $edit = $req->fetch();
        //traitement formulaire

        if(!empty($_POST['form_ajout_ateliers']) )
        {
            //stock mes valeurs des $_POST
            $id = htmlspecialchars($_POST['id']);
            $titres = htmlspecialchars($_POST['ajout_titres']);
            $descriptif = htmlspecialchars($_POST['ajout_descriptif']);
            $date = htmlspecialchars(date("Y-m-d", strtotime($_POST['ajout_date'])) );
            $times = htmlspecialchars($_POST['ajout_times']); //null; 
            $duree = htmlspecialchars($_POST['ajout_duree']); //null; 
            $dispo = htmlspecialchars($_POST['ajout_dispo']); 
            $prix = htmlspecialchars($_POST['ajout_prix']);
            $nom_img = htmlspecialchars($_POST['ajout_nom_image']);
            $actif = intval($_POST['ajout_radio']);

        
            
            if (!empty($id) AND !empty($titres) AND !empty($descriptif) AND !empty($date) AND !empty($times) AND !empty($duree) AND !empty($dispo) AND !empty($prix) AND isset($actif) )                                    
            { 
                        //prepare insert into pour envoyer des données dans la BDD
                        $ateliers = $bdd ->prepare('UPDATE ateliers SET titre=?, descriptif =?, date_atelier=?, debut=?, duree=?, places_dispo=?, prix=?, nom_image=?, actif=? WHERE id=?');
                        $ateliers ->execute(array($titres, $descriptif, $date, $times, $duree, $dispo, $prix,$nom_img, $actif, $id) );
                        $message ='donnees bien enregistrer!';  
                        header('Location: liste.php');
                    
            }
            else
            {
            $message ='Saisi incorrect.';
            }
        }
        if(!empty($_FILES) )
        {
            //indentifie le fichier et son nom
            $file_name=$_FILES['fichier']['name'];
            //Extention du fhier ex:.png
            $file_extension=strrchr($file_name,".");

            $file_tmp_name=$_FILES['fichier']['tmp_name'];
            $file_dest ='../images/'.$file_name; //envoie les img dans le dossier files
            //Fichier autoriser à etre un envoyer
            $extension_auto=array('.PNG','.png','.jpg');
            
            //Vérifier existence et si non vide
            //in_array verif si la valeur fait parti du tableau   
            if(in_array($file_extension,$extension_auto))
            {
                if(move_uploaded_file($file_tmp_name,$file_dest))
                {
                    //prepare insert into pour envoyer des données dans la BDD
                    $ateliers = $bdd ->prepare('UPDATE ateliers SET image = ?  WHERE id=?');
                    $ateliers ->execute(array($file_dest, $id) );
                    $message ='donnees bien enregistrer!';  
                    header('Location: liste.php');
                }else{
                    echo'Error';
                }
            } else{echo 'Seuls les fichiers img sont autorisés';}  

        }
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

    <div class="col-md-4 col-sm-12 mx-auto">
        <form action="edit_atelier.php?edit=<?php echo $edit['id']; ?>" method="POST"  enctype="multipart/form-data">
            <input class="form-control"  type="hidden" name="id" value="<?php echo $edit['id']; ?>">

            <!-- Titre -->
            <div class="form-group">
                <label>Titre : </label>
                <input class="form-control"  type="text" name="ajout_titres" placeholder="titres" value="<?php echo $edit['titre']; ?>">
            </div>
            <!-- nom image -->
            <div class="form-row">
                <div class="col-4 mb-2">
                    <img src="<?php echo $edit['destination']; ?>" alt="" class="img-fluid">
                </div>
                <div class="form-group col">
                    <label >Nom image : </label>
                    <input class="form-control"  type="text" name="ajout_nom_image"  value="<?php echo $edit['nom_image']; ?>">
                </div>
            </div>
            

            <!-- image -->
            <div class="form-group">
                <label >Image : </label>
                <input class="form-control"  type="file" name="fichier" >
            </div>

            <!-- Description -->
            <div class="form-group">
                <label >descriptif : </label>
                <input class="form-control"    type="text" name="ajout_descriptif" placeholder="descriptif" value="<?php echo $edit['descriptif']; ?>">
            </div>

            <!-- Date -->
            <div class="form-group">
                <label >Date : </label>
                <input class="form-control"  type="date" name="ajout_date" placeholder="date" value="<?php echo $edit['date_atelier']; ?>">
            </div>

            <!-- Horaire de début -->
            <div class="form-group">
                <!-- to do : à rectifier le type de la date dans la BDD -->
                <label for="ajout_times">Horaire de debut : </label>
                <input class="form-control"  type="text" name="ajout_times" placeholder="01:00"  value="<?php echo $edit['debut']; ?>">
            </div>

            <!-- Durée -->
            <div class="form-group">
                <!-- to do : à rectifier le type de la date dans la BDD -->
                <label >Durée : </label>
                <input class="form-control"  type="text" name="ajout_duree" placeholder="01:00" value="<?php echo $edit['duree']; ?>">
            </div>

            <!-- Place Disponible -->
            <div class="form-group">
                <label>Place Dispo. : </label>
                <input class="form-control"  type="number" name="ajout_dispo" placeholder="place dispo" value="<?php echo $edit['places_dispo']; ?>">
            </div>

            <!-- Prix -->
            <div class="form-group">
                <label>Prix en € : </label>
                <input class="form-control"  type="number" name="ajout_prix" placeholder="prix" value="<?php echo $edit['prix']; ?>">
            </div>
            <!--actif -->
            <div class="form-row">
                <span class="mr-2">Statut atelier : </span>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="ajout_radio" <?php echo ($edit['actif'] == 1)?'checked':''; ?> value="1" id="actif1" required>
                    <label class="form-check-label" for="actif1"> actif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="ajout_radio" <?php echo ($edit['actif'] == 0)?'checked':''; ?> value="0"  id="actif2" required>
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