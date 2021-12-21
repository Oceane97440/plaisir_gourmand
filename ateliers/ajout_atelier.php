<?php
session_start(); 
//connexion bdd
include('../includes/connect_bdd.php');

//ici tu dois vérifier si $_SESSION existe
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) ){
$sessionid = intval($_SESSION['id']); 
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
    include('../includes/head.php');

?>

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


    <?php include('../includes/footer.php');?>
</body>
</html>
<?php }else{
    include('../includes/deco.php');
}?>