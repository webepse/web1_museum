<?php
  session_start();

  /* security */
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }

  /* tester si formulaire envoyé ou non */
  if(isset($_POST['title']))
  {
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
        // traitement
        $dossier = '../images/';
        $fichier = basename($_FILES['cover']['name']);
        $taille_maxi = 200000;
        $taille = filesize($_FILES['cover']['tmp_name']);
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
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

            if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
            {
                echo 'Upload effectué avec succès !';
            }
            else 
            {
                echo 'Echec de l\'upload !';
            }
        }
        else
        {
            header("LOCATION:worksAdd.php?imgerror=".$imgerror);
        }
    }else{
        header("LOCATION:worksAdd.php?error=".$err);
    }


  }else{
      /* redirection si le formulaire n'a pas été envoyé */
      header("LOCATION:worksAdd.php");
  }

