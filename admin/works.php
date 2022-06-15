<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }

    require "../connexion.php";

    if(isset($_GET['delete']))
    {
        $id= htmlspecialchars($_GET['delete']);
        $reqWorks = $bdd->prepare("SELECT * FROM works WHERE id=?");
        $reqWorks->execute([$id]);
        if(!$donMyWorks = $reqWorks->fetch())
        {
            $reqWorks->closeCursor();
            header("LOCATION:works.php");
        }
        $reqWorks->closeCursor();

        unlink("../images/".$donMyWorks['cover']);

        $delete = $bdd->prepare("DELETE FROM works WHERE id=?");
        $delete->execute([$id]);
        $delete->closeCursor();
        
        header("LOCATION:works.php?deletesuccess=".$id);


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
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main>
        <div class="container">
            <h1>Works</h1>
            <?php
                  if(isset($_GET['add']))
                  {
                      echo "<div class='alert alert-success'>Vous avez bien ajouté une nouvelle oeuvre</div>";
                  }
                  if(isset($_GET['update']) && isset($_GET['id']))
                  {
                      echo "<div class='alert alert-warning'>Vous avez bien modifié l'oeuvre n°".$_GET['id']."</div>";
                  }
                  if(isset($_GET['deletesuccess']))
                  {
                      echo "<div class='alert alert-danger'>Vous avez bien supprimé l'oeuvre n°".$_GET['deletesuccess']."</div>";
                  }
            ?>
            <a href="worksAdd.php" class='btn btn-success'>Ajouter</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Year</th>
                        <th>Author</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $works = $bdd->query("SELECT works.id AS wid, works.title AS wtitle, works.year AS wyear, author.id AS aid, author.lastname AS alast, author.firstname AS afirst FROM works INNER JOIN author ON works.id_author = author.id");
                        while($donWorks = $works->fetch())
                        {
                            echo "<tr>";
                                echo "<td>".$donWorks['wid']."</td>";
                                echo "<td>".$donWorks['wtitle']."</td>";
                                echo "<td>".$donWorks['wyear']."</td>";
                                // $req = $bdd->prepare("SELECT * FROM author WHERE id=?");
                                // $req->execute([$donWorks['id_author']]);
                                // $don = $req->fetch();
                                // echo "<td>".$don['firstname']." ".$don['lastname']."</td>";
                                echo "<td>".$donWorks['afirst']." ".$donWorks['alast']."</td>";
                                echo "<td>";
                                    echo "<a href='worksUpdate.php?id=".$donWorks['wid']."' class='btn btn-warning mx-2'>Modifier</a>";
                                    echo "<a href='works.php?delete=".$donWorks['wid']."' class='btn btn-danger mx-2'>Supprimer</a>";
                                echo "</td>";
                            echo "</tr>";    
                        }
                        $works->closeCursor();
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