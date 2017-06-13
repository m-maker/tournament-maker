<?php
	include "conf.php";

	$dpt = htmlspecialchars(trim($_POST['dpt']));
	$req_dpt = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt->execute(array(
		'dpt_code' => $dpt
		));
	$res_dpt = $req_dpt->fetch();

    ?>
                            <div class="container-fluid center" style="padding: 2%;">
                                <div class="gauche">
                                <p>Selectionnez un département</p>
                                <button id="btn_dpt2" class="btn btn-default center" data-toggle="modal" data-target="#myModal2">
                                    <div id="nom_departement2" > Département  <b class="caret"></b> </div>
                                </button>
                                </div>
                                <hr/>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title" id="myModalLabel2">Département</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="liste_departements" id="liste_departements2">
                                                <form id="form_dpt2">
                                                    <ul>
                                                        <?php
                                                        foreach (listeDepartements() as $key) {
                                                            ?>
                                                            <li>
                                                                <label> <?php echo '('.$key['dpt_code'].') '.$key['dpt_nom']; ?>
                                                                    <input type="radio" name="dpt" value="<?php echo $key['dpt_code'] ?>" class="badgebox">
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="valider2" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
    <div id="complexe">

    <?php
	$liste_complexes = liste_lieux($res_dpt['dpt_id']);
    //var_dump($liste_complexes);
        foreach ($liste_complexes as $lieu){
            ?>
                <div class="complexe ligne center" >
                        <input type="radio" name="complexe" value="<?php echo $lieu['lieu_id']; ?>">
                        <img class="logo_complexe img-responsive" src="<?php echo $lieu['lieu_logo']; ?>" alt="<?php echo $lieu['lieu_nom']; ?>">
                        <p><?php echo $lieu['lieu_nom'].'<br/>'.$lieu['lieu_ville']; ?></p>
                </div>
        	<?php
        }
    ?>
    </div>
