<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }

    require "../connexion.php";

    if(isset($_GET['delete']))
    {
        // protection
        $id = htmlspecialchars($_GET['delete']);

        // vérif de l'existance de l'id
        $verif = $bdd->prepare('SELECT * FROM author WHERE id=?');
        $verif->execute([$id]);
        if(!$don = $verif->fetch())
        {
            $verif->closeCursor();
            header("LOCATION:authors.php");
        }
        $verif->closeCursor();

        // supprimer les images des oeuvres liées à l'auteur avant de supprimer l'entrée dans la bdd 
        $reqWorks = $bdd->prepare("SELECT * FROM works WHERE id_author=?");
        $reqWorks->execute([$id]);
        while($donMyWorks = $reqWorks->fetch())
        {
            unlink("../images/".$donMyWorks['cover']);
        }
        $reqWorks->closeCursor();




        // supprimer les oeuvres liées à l'auteur
        $deleteWorks = $bdd->prepare("DELETE FROM works WHERE id_author=?");
        $deleteWorks->execute([$id]);
        $deleteWorks->closeCursor();

        // supprimer l'auteur
        $deleteAuthor = $bdd->prepare("DELETE FROM author WHERE id=?");
        $deleteAuthor->execute([$id]);
        $deleteWorks->closeCursor();

        header("LOCATION:authors.php?deletesuccess=".$id);

    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Admin - Authors</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main>
        <div class="container">
            <h1>Authors</h1>
            <?php
                if(isset($_GET['add']))
                {
                    echo "<div class='alert alert-success'>Vous avez bien ajouté un nouvel auteur</div>";
                }
                if(isset($_GET['update']) && isset($_GET['id']))
                {
                    echo "<div class='alert alert-warning'>Vous avez bien modifié l'auteur n°".$_GET['id']."</div>";
                }
                if(isset($_GET['deletesuccess']))
                {
                    echo "<div class='alert alert-danger'>Vous avez bien supprimé l'auteur n°".$_GET['deletesuccess']."</div>";
                }

            ?>
            <a href="authorsAdd.php" class='btn btn-success'>Ajouter</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Birthdate</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $authors = $bdd->query("SELECT * FROM author");
                        while($donAuthors = $authors->fetch())
                        {
                            echo "<tr>";
                                echo "<td>".$donAuthors['id']."</td>";
                                echo "<td>".$donAuthors['firstname']."</td>";
                                echo "<td>".$donAuthors['lastname']."</td>";
                                echo "<td>".$donAuthors['birthdate']."</td>";
                                echo "<td>";
                                    echo "<a href='authorsUpdate.php?id=".$donAuthors['id']."' class='btn btn-warning mx-2'>Modifier</a>";
                                    echo "<a href='authors.php?delete=".$donAuthors['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                                echo "</td>";
                            echo "</tr>";    
                        }
                        $authors->closeCursor();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>