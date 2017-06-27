<?php

    include '../conf.php';
    include 'connect_api.php';
    /*
     *   ***** FONCTION D'UPLOAD DE FICHIER *****
     */
    function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)
    {
        //Test1: fichier correctement uploadé
        if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
        //Test2: taille limite
        //if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
        //Test3: extension
        $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
        //if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
        //Déplacement
        return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
    }

    /*

     *   ***** SECURISATION DES VARIABLES UTILISATEUR *****
     */
    $req_info_complexe = $db->prepare('SELECT * FROM gerant INNER JOIN lieux ON gerant.gerant_lieu_id = lieux.lieu_id WHERE gerant_membre_id = :membre_id');
    $req_info_complexe->execute(array(
        'membre_id' => $_SESSION['id']
        ));
    $res_info_complexe = $req_info_complexe->fetch();
    $cp = $res_info_complexe['lieu_cp'];
    $lieu_id = $res_info_complexe['lieu_id'];
    $ville = $res_info_complexe['lieu_ville'];
    $nom_lieu = $res_info_complexe['lieu_nom'];
    $adresse = $res_info_complexe['lieu_adresse_l1'];
    $heure_debut = htmlspecialchars(trim($_POST['heure_debut']));
    $heure_fin = htmlspecialchars(trim($_POST['modal_heure_fin']));
    $titre = htmlspecialchars(trim($_POST['type_event']));
    if ($_POST["type_event"] == "rencontre") {
        $nb_equipes = htmlspecialchars(trim($_POST['nb_equipes']));
        $joueurs_max = 7;
        $joueurs_min = htmlspecialchars(trim($_POST['joueurs_equipe_min']));
    }
    else{
        $nb_equipes = 1;
        $joueurs_max = htmlspecialchars(trim($_POST['joueurs_requis']));
        $joueurs_min = htmlspecialchars(trim($_POST['joueurs_requis']));
        $nb_joueurs_presents = htmlspecialchars(trim($_POST['joueurs_presents']));
    }
    $tarif = htmlspecialchars(trim($_POST['tarif']));
    $event_date = htmlspecialchars(trim($_POST['event_date']));
    $restriction = htmlspecialchars(trim($_POST['restriction']));
    $pass = htmlspecialchars(trim($_POST['event_pass']));
    $paiement = htmlspecialchars(trim($_POST['paiement']));
    $tarification_equipe = htmlspecialchars(trim($_POST['event_tarification_equipe']));
    $descriptif = htmlspecialchars(trim($_POST['event_descriptif']));
    $terrain_id = htmlspecialchars(trim($_POST['terrain_id']));
    // Infos du compte
    $select_compte = htmlspecialchars(trim($_POST["select-compte"]));
    $rib_bic = htmlspecialchars(trim($_POST['compte_rib_bic']));
    $rib_iban = htmlspecialchars(trim($_POST['compte_rib_iban']));
    $compte_nom = htmlspecialchars(trim($_POST["compte_nom"]));
    $compte_prenom = htmlspecialchars(trim($_POST["compte_prenom"]));
    $compte_adresse_l1 = htmlspecialchars(trim($_POST["compte_adresse"]));
    $compte_adresse_l2 = htmlspecialchars(trim($_POST["compte_adresse_2"]));
    $compte_cp = htmlspecialchars(trim($_POST["compte_cp"]));
    $compte_ville = htmlspecialchars(trim($_POST["compte_ville"]));

    if (!empty($cp) && !empty($ville) && !empty($adresse) && !empty($nom_lieu) && !empty($titre) && !empty($joueurs_max) && !empty($tarif) && !empty($event_date)) {
        /*
         *   ***** GESTION DU DEPARTEMENT *****
         */
        // Découpage du code postal
        $dpt_code = substr($cp, 0, 2);
        // Récuperation de l'id du Département
        $req_dpt_id = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
        $req_dpt_id->bindValue('dpt_code', $dpt_code, PDO::PARAM_STR);
        $req_dpt_id->execute();
        $res_dpt_id = $req_dpt_id->fetch();
        $dpt_id = $res_dpt_id['dpt_id'];


        /*
         *   ***** GESTION DU PAIEMENT EN LIGNE *****|
         */
        $mango = array("im_id" => null);
        if (!empty($_POST['compte_rib_iban']) && !empty($compte_nom) && !empty($compte_prenom) && !empty($compte_adresse_l1) && !empty($compte_ville) || $select_compte != "new") {

            if ($select_compte != "new") {
                $rib_id = $select_compte;
            } else {
                // Récupération du dernier id de rib
                $req_rib_id = $db->query('SELECT MAX(compte_id) FROM compte;');
                $req_rib_id->execute();
                $rib_id = $req_rib_id->fetchColumn() + 1;
                if (empty($rib_bic) || $rib_bic == "")
                    $rib_bic = null;
                if (empty($compte_adresse_l2) || $compte_adresse_l2 == "")
                    $compte_adresse_l2 = null;
                if (strlen($compte_cp) == 5) {
                    // Ajout du rib
                    $req_rib = $db->prepare('INSERT INTO compte (compte_id, compte_rib_bic, compte_rib_iban, compte_membre_id, compte_nom, compte_prenom, compte_adresse_l1, compte_adresse_l2, compte_cp, compte_ville) 
                VALUES (:compte_id, :compte_rib_bic, :compte_rib_iban, :compte_membre_id, :compte_nom, :compte_prenom, :compte_adresse, :compte_adresse_2, :compte_cp, :compte_ville)');
                    $req_rib->bindValue(':compte_id', $rib_id, PDO::PARAM_INT);
                    $req_rib->bindValue(':compte_rib_bic', $rib_bic, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_rib_iban', $rib_iban, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_membre_id', $_SESSION["id"], PDO::PARAM_INT);
                    $req_rib->bindValue(':compte_nom', $compte_nom, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_prenom', $compte_prenom, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_adresse', $compte_adresse_l1, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_adresse_2', $compte_adresse_l2);
                    $req_rib->bindValue(':compte_cp', $compte_cp, PDO::PARAM_STR);
                    $req_rib->bindValue(':compte_ville', $compte_ville, PDO::PARAM_STR);
                    $req_rib->execute();
                    $paiement = 1;

                } else {
                    echo 'err_code_postal_compte';
                }
            }

            $req_mango = $db->prepare("SELECT * FROM infos_mango WHERE im_membre_id = :id LIMIT 1");
            $req_mango->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
            $req_mango->execute();
            $mango = $req_mango->fetch();
            $id_mango = $mango['im_mango_id'];

            $req_compte = $db->prepare("SELECT * FROM compte WHERE compte_id = :id");
            $req_compte->bindValue(":id", $rib_id, PDO::PARAM_INT);
            $req_compte->execute();
            $compte_user = $req_compte->fetch();
            if (empty($id_mango) || $id_mango == null) {
                try {
                    $date_naissance = new DateTime($jour . "-" . $mois . "-" . $annee);
                    $User = new MangoPay\UserNatural();
                    $User->Email = $_SESSION["membre_mail"];
                    $User->FirstName = $compte_user["compte_prenom"];
                    $User->LastName = $compte_user["compte_nom"];
                    $User->Birthday = $date_naissance->getTimestamp();
                    $User->Nationality = "FR";
                    $User->CountryOfResidence = "FR";
                    $userAdded = $mangoPayApi->Users->Create($User);

                    $Wallet = new \MangoPay\Wallet();
                    $Wallet->Owners = array($userAdded->Id);
                    $Wallet->Description = "Porte-monnaie complexe";
                    $Wallet->Currency = "EUR";
                    $walletAdded = $mangoPayApi->Wallets->Create($Wallet);

                    $req = $db->prepare("INSERT INTO infos_mango (im_mango_id, im_membre_id, im_wallet_id) VALUES (:id_user, :id_membre, :id_wallet);");
                    $req->bindValue(":id_user", $userAdded->Id, PDO::PARAM_INT);
                    $req->bindValue(":id_membre", $_SESSION['id'], PDO::PARAM_INT);
                    $req->bindValue(':id_wallet', $walletAdded->Id, PDO::PARAM_INT);
                    $req->execute();

                    $id_mango = $userAdded->Id;
                } catch (\MangoPay\Libraries\ResponseException $err) {
                    echo 'err_donnees_user_invalides' . $err->getMessage();
                    die;
                } catch (Exception $ex) {
                    echo 'err_compte' . $ex->getMessage();
                    die;
                }
            }
            try {
                $adresse = new \MangoPay\Address();
                $adresse->AddressLine1 = $compte_user["compte_adresse_l1"];
                $adresse->AddressLine2 = $compte_user["compte_adresse_l2"];
                $adresse->City = $compte_user["compte_ville"];
                $adresse->PostalCode = $compte_user["compte_cp"];
                $adresse->Region = "Aquitaine";
                $adresse->Country = "FR";

                $compte = new \MangoPay\BankAccount();
                $compte->OwnerAddress = $adresse;
                $compte->OwnerName = $compte_user["compte_nom"] . " " . $compte_user["compte_prenom"];
                $compte->UserId = $id_mango;
                $details = new \MangoPay\BankAccountDetailsIBAN();
                $details->BIC = $compte_user['compte_rib_bic'];
                $details->IBAN = $compte_user["compte_rib_iban"];
                $compte->Details = $details;
                $compte->Type = "IBAN";
                $compte->Active = true;
                $compte->CreationDate = time();
                $mangoPayApi->Users->CreateBankAccount($id_mango, $compte);
            } catch (\MangoPay\Libraries\ResponseException $err) {
                echo 'err_donnees_compte_invalides' . $err->getMessage();
                die;
            } catch (Exception $ex) {
                echo 'err_compte' . $ex->getMessage();
                die;
            }
        } else {
            $rib_id = NULL;
            $paiement = 0;
        }

        /*
         *   ***** REFORMATAGE DES HEURES *****
         */
        $heure_debut = $heure_debut.':00';
        $heure_fin = $heure_fin.':00';

        //var_dump($_FILES['icone']['name']);

        /*
         *   ***** GESTION DE L'ICONE *****
         **/
        $path_img = null;
        //$path_img = date("Y-m-d_H-i-s") . '_' . $_FILES["icone"]["name"];
        //$upload1 = upload('icone', '../img/logo-tournois/' . $path_img, 15360, array('png', 'gif', 'jpg', 'jpeg'));

        //var_dump($upload1);


        /*
         *   ***** GESTION DU TOURNOI *****
         */
        // Mot de pass null si l'evenement est public
        if ($restriction == 0){
            $pass = null;
        }
        
        $asso = null;

        if (empty($nb_equipes)){
            $nb_equipes = 2;
        }

        if ($_POST['type_event'] == "rencontre"){
            $event_tournoi = 1;
        }
        else{
            $event_tournoi = 0;
        }
        // Récupération du dernier ID de tournoi
        $req_event_id = $db->query('SELECT MAX(event_id) FROM tournois');
        $req_event_id->execute();
        $res_event_id = $req_event_id->fetchColumn() + 1;
        // Ajout des informations du tournoi
        $req_organiser_tournoi = $db->prepare('INSERT INTO tournois(event_id, event_titre, event_nb_equipes, event_joueurs_max, event_joueurs_min, event_tarif, event_lieu, event_date, event_heure_debut, event_heure_fin, event_prive, event_pass, event_paiement, event_rib_id, event_mango, event_tarification_equipe, event_orga, event_orga_2, event_img, event_descriptif, event_tournoi) 
            VALUES (:event_id, :event_titre, :event_nb_equipes, :event_joueurs_max, :event_joueurs_min, :tarif, :event_lieu, :event_date, :event_heure_debut, :event_heure_fin, :event_prive, :event_pass, :event_paiement, :event_rib_id, :event_mango, :event_tarification_equipe, :event_orga, :event_orga_2, :event_img, :event_descriptif, :event_tournoi)');
        $req_organiser_tournoi->bindValue(":event_id", $res_event_id, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_titre", $titre, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_nb_equipes", $nb_equipes, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_joueurs_max", $joueurs_max, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_joueurs_min", $joueurs_min, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":tarif", $tarif, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_lieu", $lieu_id, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_date", $event_date, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_heure_debut", $heure_debut, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_heure_fin", $heure_fin, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_prive", $restriction, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_pass", $pass, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_paiement", $paiement, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_rib_id", $rib_id, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_mango", $mango["im_id"], PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_tarification_equipe", $tarification_equipe, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_orga", $_SESSION["id"], PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_orga_2", $asso, PDO::PARAM_INT);
        $req_organiser_tournoi->bindValue(":event_img", $path_img, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_descriptif", $descriptif, PDO::PARAM_STR);
        $req_organiser_tournoi->bindValue(":event_tournoi", $event_tournoi, PDO::PARAM_INT);
        $req_organiser_tournoi->execute();

        $creneau_datetime = $event_date.' '.$heure_debut;
        $creneau_datetime_fin = $event_date.' '.$heure_fin;

        $req_creneau = $db->prepare('INSERT INTO creneaux(creneau_datetime, creneau_datetime_fin, creneau_statut_id, creneau_event_id, creneau_terrain_id)
            VALUES (:creneau_datetime, :creneau_datetime_fin, :creneau_statut_id, :creneau_event_id, :creneau_terrain_id)');
        $req_creneau->execute(array(
            'creneau_datetime' => $creneau_datetime,
            'creneau_datetime_fin' => $creneau_datetime_fin,
            'creneau_statut_id' => 2,
            'creneau_event_id' => $res_event_id,
            'creneau_terrain_id' => $terrain_id
            ));

        /*echo '<br><br />';
        var_dump($res_event_id);
        var_dump($titre);
        var_dump($nb_equipes);
        var_dump($joueurs_min);
        var_dump($joueurs_max);
        var_dump($tarif);
        var_dump($res_lieu_id);
        var_dump($event_date);
        var_dump($heure_debut);
        var_dump($heure_fin);
        var_dump($restriction);
        var_dump($pass);
        var_dump($paiement);
        var_dump($rib_id);
        var_dump($tarification_equipe);
        var_dump($_SESSION['id']);
        var_dump($descriptif);*/
        //var_dump($res_event_id);

   
    }else{
        echo 'err_champs_vides';
        die;
    }
header("location:planning.php");






?>

