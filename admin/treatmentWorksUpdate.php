<?php
  session_start();

  /* security */
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }

  /* tester si formulaire envoyé ou non */
  if(isset($_POST['id']))
  {
    // viens de l'input type hidden
    $id = htmlspecialchars($_POST['id']);

    // vérification dans la bdd si l'oeuvre existe 
    require "../connexion.php";
    $works = $bdd->prepare("SELECT * FROM works WHERE id=?");
    $works->execute([$id]);
    if(!$donWorks = $works->fetch())
    {
        $works->closeCursor();
        header("LOCATION:works.php");
    }
    $works->closeCursor();

    // tester les valeurs
    // init les erreurs à 0
    $err = 0;

    if(empty($_POST['title']))
    {
        $err=1;
    }else{
        $title = htmlspecialchars($_POST['title']);
    }

    if(empty($_POST['description']))
    {
        $err=2;
    }else{
        $description= htmlspecialchars($_POST['description']);
    }

    if(empty($_POST['year']))
    {
        $err=3;
    }else{
        $year = htmlspecialchars($_POST['year']);
        if(!is_numeric($year))
        {
            $err=4;
        }
    }

    if(empty($_POST['author']))
    {
        $err=5;
    }else{
        $author = htmlspecialchars($_POST['author']);
        // to do: vérifier s'il existe dans la bdd 
    }

    if($err==0)
    {
        // 2 options: soit il y a une image soit il n'y a pas d'image 
        if(empty($_FILES['cover']['tmp_name']))
        {
            $update = $bdd->prepare("UPDATE works SET title=:titre, year=:annee, description=:descri, id_author=:auteur WHERE id=:myid");
            $update->execute([
                ":titre"=>$title,
                ":annee"=>$year,
                ":descri"=>$description,
                ":auteur"=>$author,
                ":myid"=>$id
            ]);
            $update->closeCursor();
            header("LOCATION:works.php?update=success&id=".$id);
        }else{
             // traitement de l'image car il y en a une
            $dossier = '../images/';
            $fichier = basename($_FILES['cover']['name']);
            $taille_maxi = 200000;
            $taille = filesize($_FILES['cover']['tmp_name']);
            $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
            $extension = strrchr($_FILES['cover']['name'], '.');
            if(!in_array($extension, $extensions)) 
            {
                $imgerror = 1;
            }
            if($taille>$taille_maxi) // on teste la taille de notre fichier
            {
                $imgerror = 2;
            }

            
            if(!isset($imgerror)) 
            {
            
                $fichier = strtr($fichier,
                'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
                'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); 
                $fichiercplt = rand().$fichier;

                // ../images/12354969monimage.jpg
                if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
                {
                    // supprimer l'image dans le dossier "../images/"
                    unlink("../images/".$donWorks['cover']);


                   // tout est ok, modification de l'entrée dans la bdd
                  
                   $update = $bdd->prepare("UPDATE works SET title=:titre, year=:annee, description=:descri, id_author=:auteur, cover=:couverture WHERE id=:myid");
                   $update->execute([
                       ":titre"=>$title,
                       ":annee"=>$year,
                       ":descri"=>$description,
                       ":auteur"=>$author,
                       ":couverture"=>$fichiercplt,
                       ":myid"=>$id
                   ]);
                   $update->closeCursor();
                   header("LOCATION:works.php?update=success&id=".$id);
                }
                else 
                {
                    header("LOCATION:worksUpdate.php?id=".$id."&upload=echec");
                }
            }
            else
            {
                header("LOCATION:worksUpdate.php?id=".$id."&imgerror=".$imgerror);
            }
            


        }


       

    }else{
        header("LOCATION:worksUpdate.php?id=".$id."&error=".$err);
    }


  }else{
      /* redirection si le formulaire n'a pas été envoyé */
      header("LOCATION:works.php");
  }

