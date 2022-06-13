<?php
  session_start();

  /* security */
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }

   /* tester si l'id est présent sinon la page ne peut pas fonctionner  */
   if(isset($_GET['id']))
   {
       $id=htmlspecialchars($_GET['id']);
   }else{
       header("LOCATION:authors.php");
   }

   // req à la bdd pour savoir si l'id existe bien
   require "../connexion.php";

   $req = $bdd->prepare("SELECT * FROM author WHERE id=?");
   $req->execute([$id]);
   
   if(!$don = $req->fetch())
   {
       $req->closeCursor();
       header("LOCATION:authors.php");
   }
   $req->closeCursor();


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
        
        $update = $bdd->prepare("UPDATE author SET firstname=:firstname, lastname=:lastname, birthdate=:birthdate WHERE id=:myid");
        $update->execute([
            ':firstname'=>$firstName,
            ':lastname'=>$lastName,
            ":birthdate"=>$birthdate,
            ":myid"=>$id
        ]);
        $update->closeCursor();
        header("LOCATION:authors.php?update=success&id=".$id);
        
    }else{
        header("LOCATION:authorsUpdate.php?id=".$id."&error=".$err);
    }


  }else{
      /* redirection si le formulaire n'a pas été envoyé */
      header("LOCATION:authorsUpdate.php?id=".$id);
  }

