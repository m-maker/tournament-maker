<?php
  include "conf.php";
  if(isset($_POST['dpt']) AND !empty($_POST['dpt'])){
        $dpt = htmlspecialchars(trim($_POST['dpt']));
    }
    else{
        $dpt = 33;
    };
  $liste_lieux = liste_lieux_dpt_code($dpt);
  $boucle = 0;
  foreach ($liste_lieux as $lieu_key => $lieu_value) {
    ?>

      <div class="liste_events cont" <?php if ($boucle == 1){ echo "style='display:none;'";} ?> id="cont-onglet-<?php echo $lieu_value[0];?>">
        <div class="div_input_div">
            <img src="<?php echo $lieu_value['lieu_logo']; ?>" class="img img-responsive" style="height: 50px;">
            <p style="text-align: left; margin-left: 5px;">
                <span style="font-size: 15px;"><?php echo $lieu_value['lieu_nom']; ?>,</span>
                <br/>
                <span><?php echo $lieu_value['lieu_ville']; ?></span>
            </p>
        </div>
        <?php
          $liste_events = liste_tournois_complexe($lieu_value[0]);
            foreach ($liste_events as $event_value_key => $event_value) {
              ?>
                <div class="container-fluid recap_event">
                  <a href="feuille_de_tournois.php?tournoi=<?php echo $event_value[0]; ?>">
                    <div class=" row <?php if (isset($event_value['event_tournoi']) && $event_value['event_tournoi'] == 0){ echo 'match'; } else { echo 'tournoi';}?> ">
                      <div class="col-xs-12">
                        <p class="bold">
                          Le <span ><?php echo $event_value['event_date']; ?></span>
                          <br/>
                          <span class="heure"><?php echo $event_value['event_heure_debut']; ?></span> - <span><?php echo $event_value['event_heure_fin']; ?></span>
                        </p>
                      </div>
                      <div class="recap_event_titre col-xs-6" >
                        <p class="<?php /*echo $color; */?>">
                          <span>
                            <?php 
                              if (isset($event_value['event_tournoi']) && $event_value['event_tournoi'] == 0){ 
                                echo 'Match ';
                              }
                             else {
                              echo 'Tournoi ';
                              }
                              if ($event_value['event_prive'] == 0){
                                echo "publique";
                              }
                              else{
                                echo "privé";
                              }
                            ?> 
                            <br/>
                            Oragnisé par <?php echo $event_value['event_orga_id']; ?>.
                          </span>
                        </p>
                      </div>
                      <div class="prix col-xs-6">
                        <span class="bold">
                            <?php echo $event_value['event_tarif']; ?>€
                            <?php 
                              if($event_value['event_tarification_equipe'] == 1 ){
                                echo "Par équipe";
                              }
                              else{
                                echo "Par joueur";
                              }
                            ?>
                        </span>
                        <br/>
                        <span class="glyphicon glyphicon-user"></span><span class="bold">
                            <?php echo compte_equipes($event_value[0]) . ' / ' . $event_value['event_nb_equipes']; ?>
                            <?php 
                              if (isset($event_value['event_tournoi']) && $event_value['event_tournoi'] == 0){ 
                                echo 'Joueurs inscrits';
                              }
                              else {
                                echo 'Équipes inscrites';
                              }
                            ?>
                        </span>
                      </div>
                    </div>
                  </a>
                </div>
                <br/>
              <?php
            }
          $boucle = 1;
        ?>
      </div>
    <?php
  }
?>




  