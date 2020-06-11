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
    <title>Profil Particulier</title>
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
                <li class="nav-item"><a class="nav-link" href="profil_particulier.php">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="../ateliers/liste_particulier.php">Réservation</a></li>
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
 
    </body>  
</html>
