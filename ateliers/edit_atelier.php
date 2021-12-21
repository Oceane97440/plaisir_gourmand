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
    include('../includes/head.php');
 
?>



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
    <?php include('../includes/footer.php');?>
</body>
</html>
<?php }else{
    include('../includes/deco.php');
}?>