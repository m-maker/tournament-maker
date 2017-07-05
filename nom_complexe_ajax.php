<?php
    $lieu_id= $_POST['complexe_id'];
    $lieu = recupLieuById($lieu_id);
    ?>

        <div>
            <span class="glyphicon glyphicon-pushpin"></span>
            <span>Complexe : </span>
        </div>
        <div class="div_input_div">
            <img src="<?php echo $lieu['lieu_logo']; ?>" class="img img-responsive" style="height: 50px;">
            <p style="text-align: left; margin-left: 5px;">
                <span style="font-size: 15px;"><?php echo $lieu['lieu_nom']; ?>,</span>
                <br/>
                <span><?php echo $lieu['lieu_ville']; ?></span>
            </p>
        </div>

