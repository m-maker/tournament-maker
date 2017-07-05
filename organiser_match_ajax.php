<?php
    include "conf.php";

    $dpt = htmlspecialchars(trim($_POST['dpt']));
    $liste_tournois = liste_tournois($dpt);

    $req_ajout_dpt = $db->prepare("UPDATE membres SET membre_dpt_code = :dpt WHERE id = :id_membre");
    $req_ajout_dpt->bindValue(":dpt", $dpt, PDO::PARAM_STR);
    $req_ajout_dpt->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
    $req_ajout_dpt->execute();

    global $res_dpt_from_liste_tournois;

    if(isset($_SESSION['id'])){
        $user = $db->prepare('SELECT * FROM membres WHERE membres.id = :membre_id');
        $user->execute(array(
            'membre_id' => $_SESSION['id']
        ));
    }

    $req_dpt = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
    $req_dpt->execute(array(
        'dpt_code' => $dpt
        ));
    $res_dpt = $req_dpt->fetch();

    $req_membre_dpt_code = $db->prepare('UPDATE membres SET membre_dpt_code = :dpt_code WHERE id = :membre_id');
    $req_membre_dpt_code->execute(array(
        'dpt_code' => $dpt,
        'membre_id' => $_SESSION['id']
        ));

    $liste_complexes = liste_lieux($res_dpt['id']);
    //arsort($tab_complexes_events, SORT_NUMERIC);
    //var_dump($tab_complexes_events);
    //var_dump($tab_complexes_events); 
?>      
            <hr/>
            <div class="onglet-contenu">
            <p>
                <span class="filtre"><b> Complexe:</b>
                    <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                        <div id="nom_departement" > <?php echo $res_dpt['dpt_nom']; ?>  <b class="caret"></b> </div>
                    </button>
                </span>
            </p>
            <br/>
                <div class="menu_complexe">
                    <?php
                        $i = 0;
                        foreach ($liste_complexes as $complex_key => $complexe_value) {
                            $lieu = recupLieuById($complexe_value[0]);
                            ?>
                                <div class="center show_complexe onglet_complexe_1 <?php if($i == 0){echo 'acti';} ?>" id="<?php echo $complexe_value[0]; ?>" >
                                        <div class="center onglet_complexe_1_1">
                                            <p><?php echo $lieu['lieu_nom']; ?> <br/> <?php echo $lieu['lieu_ville']; ?></p>
                                        </div>
                                        <div class="center onglet_complexe_1_2" style="background-image: url(<?php echo $lieu['lieu_logo']; ?>);">
                                            <!-- <img src="<?php echo $lieu['lieu_logo']; ?>" alt="<?php echo $lieu['lieu_nom']; ?>"> -->
                                        </div>
                                </div>
                            <?php
                            $i++;
                        }
                    ?>
                </div>
            </div>
            <hr/>

        <?php
        $i = 0;
        $boucle = 0;
        
        foreach ($liste_complexes as $complexe_key => $complexe_value) {
            $lieu = recupLieuById($complexe_value[0]);
            $liste_events = liste_creneaux_libres($complexe_value[0]);
            $liste_creneaux_libres = liste_creneaux_libres($complexe_value[0]);
            ?>
                <div style="color:black; <?php if($boucle == 1 ){ echo 'display: none'; } ?> " id="cont-<?php echo $complexe_value[0]; ?>" class="cont">
                    <form action="organiser_match_traitement.php" method="post">
                    <input type="hidden" name="id_lieu" value="<?php echo $complexe_value[0]; ?>">
                    <p>Les créneaux dans le complexe : <?php echo $complexe_value['lieu_nom']; ?></p>
                    <div class="ligne calendrier">
                    <?php 
                        $date = new datetime;
                        for ($i=0; $i< 7; $i++) { 
                            ?>
                                    
                                    <div class="cont-creneaux">
                                    <p><?php echo $date->format('d/m'); ?></p>
                                        <?php
                                            foreach ($liste_creneaux_libres as $creneau_libre_key => $creneau_libre_value) {
                                                $creneau_obj_time = DateTime::createFromFormat('Y-m-d H:i:s', $creneau_libre_value['creneau_datetime']);
                                                $creneau_obj_time_fin = DateTime::createFromFormat('Y-m-d H:i:s', $creneau_libre_value['creneau_datetime_fin']);
                                                if ($creneau_obj_time->format('Y-m-d') == $date->format('Y-m-d')){
                                                    ?>
                                                        <div style="background-color: rgb(66,133,244); margin: 1px;">
                                                            <label >
                                                                <span><?php echo $creneau_obj_time->format('H:i').' - '.$creneau_obj_time_fin->format('H:i'); ?></span>
                                                                <input type="checkbox" name="<?php echo $creneau_libre_value['creneau_datetime']; ?>" value="<?php echo $creneau_libre_value['creneau_datetime']; ?>">
                                                            </label>
                                                        </div>
                                                    <?php
                                                }
                                                unset($creneau_obj_time);
                                            }
                                        ?>
                                    </div>
                            <?php
                            $date->add(new dateinterval('P1D'));
                        }
                        unset($date);
                    ?>
                    </div>
                    </form>
                </div>
            <?php
            $boucle = 1;
        }
            
        ?>
            <script>
                $('.show_complexe').click(function () {
                    var id = $(this).attr("id");
                    var cont = $('.cont');
                    var cont_event;
                    cont.hide();
                    if (id == "onglet-all"){
                        cont_event = $('#cont-all');
                    }else{
                        cont_event = $('#cont-' + id);
                    }
                    cont_event.show();
                    $(".acti").removeClass('acti');
                    $(this).addClass("acti");
                });
            </script>
            <?php
            $boucle = 1;
    ?>