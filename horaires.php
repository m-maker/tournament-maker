<?php 
  if (!isset($id_match)){
    $date_creation = new DateTime();
  }
  else {
    $req_date_creation = $bdd->prepare('SELECT * FROM matchs
   WHERE id = :id_match');
    $req_date_creation->execute(array(
      'id_match' => $id_match
      ));
    $res_date_creation = $req_date_creation->fetch();
    $date_creation = $res_date_creation['datecreation'];
  }
    ?>
  <div id="les_horaires">
  <div id="horaire1" class="horaire">
    <select id="horairej1" name="horairej1">
      <?php 
        $joursem = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
        $mois = array("janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre");

        $date = clone $date_creation;
        $date->format('Y-m-d');

        for ($i=0; $i < 15; $i++) { 
        unset($date);
        $date = clone $date_creation;
        $now = new DateTime; 
        
        $date -> add( new DateInterval('P'.$i.'D'));
                ?> 
                  <option value=<?php echo $date->format('j_n_Y').' ';
                    if ($date <  $now) {
                       echo "class=disable";
                       }?>>
                  <?php 
                    echo $joursem[$date->format('w')].' '.$date->format('d').' '.$mois[$date->format('n')-1];
                  ?> 
                  </option>
                <?php
              }
      ?>
    </select>

    <span> de </span>

    <select id="horairedebut1" name="horairedebut1">
      <?php
      for ($i=8; $i <= 23; $i++) {
        for ($j=0; $j <=1 ; $j++) { 
          $h1 = new DateTime ()
          ?> 
            <option value=
             <?php 
               if ($j==0){
                $minutes= "00";
                }
                else{
                  $minutes= "30";
                }
                echo $i.'_'.$minutes; 
              ?>
            >
             <?php 
               if ($j==0){
                $minutes= "00";
                }
                else{
                  $minutes= "30";
                }
                $h1 = Date($i.':'.$minutes.':00');
              ?>
            </option>
          <?php
         }
      }
      ?>
    </select>

    <span> à </span>

    <select id="horairefin1" name="horairefin1">
      <?php
      for ($i=8; $i <= 24; $i++) {
        for ($j=0; $j <=1 ; $j++) { 
          if ($i == 8 AND $j == 0){
          }
          elseif ($i == 24 AND $j == 1){
          }
          else{
            ?>  
              <option value=
                <?php 
                  if ($j==0){
                    $minutes= "00";
                    }
                    else{
                      $minutes= "30";
                    }
                    echo $i.'_'.$minutes; 
                ?>
              >
                <?php 
                  if ($j==0){
                  $minutes= "00";
                  echo $i.':'.$minutes; 
                  }
                  elseif ($j==1) {
                    $minutes= "30";
                    echo $i.':'.$minutes; 
                  }
                ?>
              </option>
            <?php
          }
        }
      }
      ?>
    </select>
    
    <span> .</span>
    
  <button id="button1" type="button" onclick="supprimer_creneau(1)" >-</button>

  </div>
  </div>
  <button id="button_add" type="button" onclick="ajouter_creneau()" >+</button>

  <script type="text/javascript" >
  function ajouter_creneau() {
    var idhoraire = "horaire" + window.numeroInput;
    var horaire = document.getElementById(idhoraire).cloneNode(true);

    window.numeroInput = window.numeroInput +1;
    var newidhoraire = "horaire"+window.numeroInput;

    horaire.setAttribute('id',newidhoraire);

    document.getElementById("les_horaires").appendChild(horaire);
    var anciennumeroInput = numeroInput -1;

    $("#horaire"+numeroInput + " #horairej"+anciennumeroInput).attr('id', "horairej"+numeroInput);
    $("#horaire"+numeroInput + " #horairej"+numeroInput).attr('name', "horairej"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairej"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairej"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #horairedebut"+anciennumeroInput).attr('id', "horairedebut"+numeroInput);
    $("#horaire"+numeroInput + " #horairedebut"+numeroInput).attr('name', "horairedebut"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairedebut"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairedebut"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #horairefin"+anciennumeroInput).attr('id', "horairefin"+numeroInput);
    $("#horaire"+numeroInput + " #horairefin"+numeroInput).attr('name', "horairefin"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairefin"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairefin"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #button"+anciennumeroInput).attr('id', "button"+numeroInput);
    $("#horaire"+numeroInput + " #button"+numeroInput).attr('onclick', "supprimer_creneau("+numeroInput+")");

    $("#nb_creneaux").attr('value',numeroInput);
  }

  function supprimer_creneau(numero){
    if (numero != 1){
      $("#horaire"+numero).remove();
    }
  }
</script>

<script type="text/javascript">
  var numeroInput = 1;
  alert(var);
</script>