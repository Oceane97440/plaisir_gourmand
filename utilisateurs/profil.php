<?php
session_start();
include('../includes/connect_bdd.php');

if(isset($_SESSION['id']) AND $_SESSION['id']>0)
{
    $getid=intval($_SESSION['id']);
    $requser=$bdd->prepare('SELECT*FROM utilisateurs WHERE id=?');
    $requser->execute(array($getid));
    $userinfo=$requser->fetch();

 

?>




<html>
    <head>
   <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profil</title>
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
                    <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
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
  </ul>
  </nav>   
  
  
        <section>
                    
             <div>       
                <h3>Profil de <?php echo $userinfo['nom'];?></h3>
               
                <h4>Role : <?php echo $_SESSION['role'];?></h4>

                prenom:<?php echo $userinfo['prenom'];?>
                    <br/>
                mail:<?php echo $userinfo['email'];?>
                <br/>

                <?php
                //on vérifie que la session id existe puis qu'elle est égale à id utilisateur
                if(isset($_SESSION['id']) AND ($userinfo['id']==$_SESSION['id']) )
                {
                ?>
               
                <?php
                }
                ?>
            </div>
            
            








        

                                            
                                    
                                            
                            
                    
              
        </section>      
        <?php
}
?>                  
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
   
</html>
