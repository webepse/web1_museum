<?php
  session_start();

  /* security */
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }

  /* tester si formulaire envoyé ou non */
  if(isset($_POST['firstname']))
  {
    // tester les valeurs
    // init les erreurs à 0
    $err = 0;

    if(empty($_POST['firstname']))
    {
        $err=1;
    }else{
        $firstName = htmlspecialchars($_POST['firstname']);
    }

    if(empty($_POST['lastname']))
    {
        $err=2;
    }else{
        $lastName= htmlspecialchars($_POST['lastname']);
    }

    if(empty($_POST['birthdate']))
    {
        $err=3;
    }else{
        $birthdate = htmlspecialchars($_POST['birthdate']);
    }


    if($err==0)
    {
        require "../connexion.php";
        $insert = $bdd->prepare("INSERT INTO author(firstname, lastname, birthdate) VALUES(:firstname,:lastname,:birthdate)");
        $insert->execute([
            ':firstname'=>$firstName,
            ':lastname'=>$lastName,
            ":birthdate"=>$birthdate
        ]);
        $insert->closeCursor();
        header("LOCATION:authors.php?add=success");
        
    }else{
        header("LOCATION:authorsAdd.php?error=".$err);
    }


  }else{
      /* redirection si le formulaire n'a pas été envoyé */
      header("LOCATION:authorsAdd.php");
  }

