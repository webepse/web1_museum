<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }

    /* déconnexion de l'utilisateur */
    if(isset($_GET['deco']))
    {
        session_destroy();
        header("LOCATION:index.php?decosuccess=ok");
    }

    require "../connexion.php";

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
    <div class="container">
        <h1>Tableau de bord</h1>
        
        <div class="row">
            <div class="col-md-3 m-3 bg-primary text-center text-white">
                <h2>Works</h2>
                <?php
                    $works = $bdd->query("SELECT * FROM works");
                    $nbWorks = $works->rowCount();
                    $works->closeCursor();
                ?>
                <h3><?= $nbWorks ?></h3>
            </div>
            <div class="col-md-3 m-3 bg-success text-center text-white">
                <h2>Author</h2>
                <h3>1</h3>
            </div>
            <div class="col-md-3 m-3 bg-warning text-center text-white">
                <h2>Images</h2>
                <h3>1</h3>
            </div>

        </div>


    </div>
</body>
</html>