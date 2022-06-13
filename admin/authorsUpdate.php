<?php
    session_start();
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

   


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main>
        <div class="container">
            <h1>Update Author</h1>
            <form action="treatmentAuthorsUpdate.php?id=<?= $don['id'] ?>" method="POST">
                <div class='my-3'>
                    <label for="title">FirstName: </label>
                    <input type="text" name="firstname" id="firstname" value="<?= $don['firstname'] ?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="title">LastName: </label>
                    <input type="text" name="lastname" id="lastname" value="<?= $don['lastname'] ?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="title">birthdate: </label>
                    <input type="date" name="birthdate" id="birthdate" value="<?= $don['birthdate'] ?>" class="form-control">
                </div>
                <div class="my-3">
                    <input type="submit" value="Update" class="btn btn-warning">
                    <a href="authors.php" class="btn btn-secondary">Return</a>
                </div>
            </form>
            <?php
                if(isset($_GET['error']))
                {
                    echo "<div class='alert alert-danger'>Une erreur est survenue (code error: ".$_GET['error']." )</div>";
                }
            ?>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>