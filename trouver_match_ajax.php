<?php
    include "conf.php";
    if (!isset($_POST['dpt'])){
        $dpt = 33;
    }
    else{
        $dpt = $_POST['dpt'];
    }
?>  
    <div class="center show_complexe" id="onglet-all" >
    <button class="btn btn_complexe">Tous (<?php echo compte_event_dpt($dpt); ?>)</button>
    </div>
        
    </div>
    <?php
        $i = 0;
        if (!empty($dpt)){
            foreach (liste_lieux_dpt_code($dpt) as $lieu_key => $lieu_value) {
                    ?>
                        <div class="center show_complexe onglet_complexe_1" id="onglet-<?php echo $lieu_value[0]; ?>" onclick="myModal()">
                            <input type="hidden" class="complexe_id" name="complexe_id" value="<?php echo $lieu_value[0]; ?>">
                            <button class="btn btn_complexe">
                                <p class="text-left">
                                    <?php echo $lieu_value['lieu_nom']; ?> 
                                    <br/> 
                                    <?php echo $lieu_value['lieu_ville']; ?>
                                </p>
                                <p class="text-right">
                                    <?php echo "(".count(liste_tournois_complexe($lieu_value[0])).") "; ?>
                                </p>
                            </button>
                        </div>
            	    <?php
                    $i++;
            }
        }
    ?>

<script type="text/javascript">
  function  myModal(){
    $('#myModal').modal("hide");
  }


</script>
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