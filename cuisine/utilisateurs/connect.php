<?php
session_start();
include('../includes/connect_bdd.php');

if(isset($_POST['form_connexion']) )
{
    $mailconnect=htmlspecialchars($_POST['mailconnect']);
    $mdpconnect=sha1($_POST['mdpconnect']);
    if(!empty($mailconnect) AND !empty($mdpconnect) )
    {
        $requser=$bdd->prepare("SELECT*FROM utilisateurs WHERE email=? AND mdp=?");
        $requser->execute(array($mailconnect,$mdpconnect) );
        $userexist=$requser->rowCount();
        $userinfo=$requser->fetch();
         
        //requette de recuperation du label du role a partir d un userid
            $role = $bdd->prepare('SELECT roles.label, roles.id FROM `utilisateurs_roles`, roles WHERE id_utilisateur = ? 
            and roles.id = utilisateurs_roles.id_role');    
            $role->execute([$userinfo['id']]);
            $role_info= $role-> fetch();

            if($userexist == 1)
            {
                $role_verif= $role-> fetch();

                $_SESSION['id']=$userinfo['id'];
                $_SESSION['nom']=$userinfo['nom'] .' '. $userinfo['prenom'];
                $_SESSION['role']=$role_info['label'];
                $_SESSION['id_role'] = $role_info['id'];
                //si session id existe et id_role de cette est = à 1 (cuisinier)
                if(isset($_SESSION) AND $_SESSION['id_role'] == 1 )

                { 
                  
                  header('location: profil.php');
              }
               //si session id existe et id_role de cette est = à 2 (particulier)
               if(isset($_SESSION) AND $_SESSION['id_role'] == 2)
              {
                
                header('location: profil_particulier.php');
              }
                
            }
            else {
                $erreur="Mauvais mail mdp";
            }
    }
    else
    {
        $erreur="Error";
    }
}





?>





<html lang="fr" class="h-100">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/connexion.css"/>
    
    
    </head>
        <body>
            
        <section>
        <div class="container">
            <div class="row centered-form">
                <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title-center">CONNEXION <a class="btn btn-info" href="ajout_utili.php"role=button> Inscription</a></h3>
                            
                            <form id="login-form" class="form" action="" method="post">
                                <div class="panel-body">
                            
                                <div class="form-group">
                                    <input type="email" name="mailconnect" id="email" class="form-control input-sm" placeholder="adresse@gmail.com" required>
                                </div>
    
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <input type="password" name="mdpconnect"  id="password" class="form-control input-sm" placeholder="Saisir mot de passe" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="submit" name="form_connexion"  value="Valider" class="btn btn-primary btn-block">
                                <a class="btn btn-primary btn-block" href="../index.html"role=button> Retour à l'accueil</a>
                            </form>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <?php
        if(isset($erreur))
        {
            echo $erreur;
        }
    
        ?>
            </section>    
    <script type="text/javascript" src="http://www.clubdesign.at/floatlabels.js"></script>                      
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        </body>
</html>