<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 20/05/2017
 * Time: 20:24
 */

include '../conf.php';
$db = connexionBdd();

if (isset($_GET['term'])){

    $lieu_nom = htmlspecialchars(trim($_GET["term"]));

    $req = $db->prepare('SELECT * FROM lieux WHERE lieu_nom LIKE :lieu');
    $req->bindValue(":lieu", '%'.$lieu_nom.'%', PDO::PARAM_STR);
    $req->execute();
    $lieu = [];
    while ($lieux = $req->fetch()){
        $lieu[] = $lieux["lieu_nom"];
    }

    echo json_encode($lieu);

}elseif (isset($_GET['nom']) && isset($_GET['champ'])){

    $champ = htmlspecialchars(trim($_GET["champ"]));
    $lieu_nom = htmlspecialchars(trim($_GET["nom"]));

    $req = $db->prepare('SELECT * FROM lieux WHERE lieu_nom = :lieu');
    $req->bindValue(":lieu", $lieu_nom, PDO::PARAM_STR);
    $req->execute();
    $lieu = $req->fetch();

    if ($champ == "ville") {
        echo $lieu["lieu_ville"];
    }elseif ($champ == "cp") {
        echo $lieu["lieu_cp"];
    }elseif ($champ == "adresse") {
        echo $lieu["lieu_adresse_l1"];
    }


}