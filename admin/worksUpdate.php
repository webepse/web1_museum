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
        header("LOCATION:works.php");
    }

    // req à la bdd pour savoir si l'id existe bien
    require "../connexion.php";

    $req = $bdd->prepare("SELECT * FROM works WHERE id=?");
    $req->execute([$id]);
    
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:works.php");
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
            <h1>Add Works</h1>
            <form action="treatmentWorksUpdate.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $don['id'] ?>">
                <div class='my-3'>
                    <label for="title">Title: </label>
                    <input type="text" name="title" id="title" value="<?= $don['title'] ?>" class="form-control">
                </div>
                <div class="my-3">
                    <label for="year">Year: </label>
                    <input type="number" name="year" id="year" step="1" min="600" max="2030" class="form-control" value="<?= $don['year'] ?>">
                </div>
                <div class="my-3">
                    <label for="description">Description: </label>
                    <textarea name="description" id="description" rows="10" class="form-control"><?= $don['description'] ?></textarea>
                </div>
                <div class="my-3">
                    <div class="col-3">
                        <img src="../images/<?= $don['cover'] ?>" alt="<?= $don['title'] ?>" class="img-fluid">
                    </div>
                    <label for="cover">Cover: </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="200000">
                    <input type="file" name="cover" id="cover" class="form-control">
                </div>
                <div class="my-3">
                    <label for="author">Author: </label>
                    <select name="author" id="author" class="form-control">
                        <?php
                            $authors = $bdd->query("SELECT * FROM author");
                            while($donAuthors = $authors->fetch())
                            {
                                if($don['id_author']==$donAuthors['id'])
                                {
                                    echo '<option value="'.$donAuthors['id'].'" selected>'.$donAuthors['firstname'].' '.$donAuthors['lastname'].'</option>';
                                }else{
                                    echo '<option value="'.$donAuthors['id'].'">'.$donAuthors['firstname'].' '.$donAuthors['lastname'].'</option>';
                                }
                            }
                            $authors->closeCursor();
                        ?>
                    </select>
                </div>
                <div class="my-3">
                    <input type="submit" value="Update" class="btn btn-warning">
                    <a href="works.php" class="btn btn-secondary">Return</a>
                </div>
            </form>
            <?php
                if(isset($_GET['error']))
                {
                    echo "<div class='alert alert-danger'>Une erreur est survenue (code error: ".$_GET['error']." )</div>";
                }
                if(isset($_GET['upload']))
                {
                    echo "<div class='alert alert-danger'>Une erreur est survenue lors de l'upload du fichier</div>";
                }
                if(isset($_GET['imgerror']))
                {
                    if($_GET['imgerror']==1)
                    {
                        echo "<div class='alert alert-danger'>L'extension du fichier n'est pas acceptée</div>";
                    }else{
                        echo "<div class='alert alert-danger'>La taille du fichier dépasse la limite autorisée</div>";
                    }
                }
            ?>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>