<?php
include('conf.php');

if (!isset($_SESSION["id"]))
    header("Location: index.php");

$liste_membre_msg = recupMembresMessages($_SESSION["id"]);
$tab_id = [];
foreach ($liste_membre_msg as $unMembre){
    //var_dump($unMembre); echo "<br />";
    if ($unMembre[0] == $_SESSION["id"])
        $tab_id[] = $unMembre[1];
    else
        $tab_id[] = $unMembre[0];
}

$tab_id_unique = array_unique($tab_id);

?>
<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="css/messages.css">
    <script type="text/javascript" src="js/index.js"></script>
    <title>Tournois de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">

        <h1 id="titre_corps">Mes messages</h1>


        <div class="bandeau-msg">
            <div class="row" style="margin: 0;">
                <div class="col-md-2">
                    Total : <span class="bold"><?php echo compte_msg_total($_SESSION["id"]); ?></span>
                </div>
                <div class="col-md-2">
                    Reçus : <span class="bold"><?php echo compte_msg_recus($_SESSION["id"]); ?></span>
                </div>
                <div class="col-md-2">
                    Envoyés : <span class="bold"><?php echo compte_msg_envoyes($_SESSION["id"]); ?></span>
                </div>
                <div class="col-md-3">
                    <button id="btn-vider" class="btn-grand disabled" disabled><span class="glyphicon glyphicon-trash"></span> Vider la boite de réception</button>
                </div>
                <div class="col-md-3">
                    <button id="btn-supr" class="btn-grand" disabled><span class="glyphicon glyphicon-minus"></span> Supprimer la conversation</button>
                </div>
            </div>
        </div>


        <div class="conteneur-msg">
            <div class="exp-msg">
                <div id="btn-new-msg" class="un-exp"><span class="bold"><span class="glyphicon glyphicon-plus"></span> Nouveau Message</span></div>
                <?php $i=0;
                if (!empty($tab_id_unique)) {
                    foreach ($tab_id_unique as $unId) {
                        $membre = recupMembreByID($unId); ?>
                        <div class="un-exp <?php if ($i == 0) {
                            echo 'exp-act';
                        } ?>" id="<?php echo $unId; ?>">
                            <span class="bold"><?php echo $membre["membre_pseudo"]; ?></span><br/>
                            <?php //echo $unMsg["pv_objet"];
                            ?>
                        </div>
                        <?php $i++;
                    }
                } ?>
            </div>

            <div id="new-msg" style="display: none;" class="contenu-msg">
                <div class="contenu-msg-txt" id="msg-txt-new">
                    <input id="pseudo-msg" data-provide="typeahead" class="pseudo-msg" placeholder="Saisissez ici le pseudo du destinataire" type="text" style="width: 100%;">
                </div>
                <div class="contenu-msg-form">
                    <form method="post" class="form-msg" id="form-new">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" id="msg-new" placeholder="Votre message.." class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Envoyer</button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php $i=0;
            if (!empty($tab_id_unique)) {
                foreach ($tab_id as $unId) {
                    $membre = recupMembreByID($unId);
                    $liste_msg = liste_messages($unId, $_SESSION["id"]); ?>
                    <div id="e-<?php echo $unId; ?>" <?php if ($i > 0) {
                        echo 'style="display:none;"';
                    } ?> class="contenu-msg">
                        <div class="contenu-msg-txt" id="msg-txt-<?php echo $unId; ?>">
                            <?php foreach ($liste_msg as $unMsg) { ?>
                                <div class="un-msg <?php if ($unMsg["exp"] == $_SESSION["id"]) {
                                    echo 'msg-me';
                                } ?>">
                                    <?php echo $unMsg['msg']; ?>
                                    <span class="right" style="margin-top: 10px;font-size: 12px;">
                                    <?php echo $unMsg['date']; ?>
                                </span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="contenu-msg-form">
                            <form method="post" class="form-msg" id="form-<?php echo $unId; ?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" id="msg-<?php echo $unId; ?>" placeholder="Votre message.."
                                               class="form-control">
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Envoyer</button>
                                    </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php $i++;
                }
            }else{ ?>
                <div class="contenu-msg center" style="padding-top: 170px;">
                    Vous n'avez pas encore envoyé / reçu de messages...<br />
                    <button id="btn-new" style="margin-top: 5px;"><span class="glyphicon glyphicon-plus"></span> Envoyer un message</button>
                </div>
            <?php } ?>

        </div>

    </div>
</div>
<!-- FOOTER -->
<?php
include('footer.php');
$req = $db->prepare("UPDATE messages_prives SET pv_vu = 1 WHERE pv_destinataire_id = :id");
$req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
$req->execute();
?>

<script>
    $(".un-exp").click(function () {
        var id = $(this).attr("id");
        var cont = $('#e-' + id);
        console.log(cont);
        $('.un-exp').removeClass('exp-act');
        $(this).addClass('exp-act');
        $(".contenu-msg").hide();
        cont.show();
    });

    $('.form-msg').submit(function (e) {
        e.preventDefault();
        var id = $(this).attr("id");
        var msg_cont;
        var msg;
        if (id == "form-new"){
            msg_cont = $("#msg-new");
            msg = msg_cont.val();
            var pseudo = $("#pseudo-msg").val();
            $.post('send_msg_pv.php', {msg: msg, pseudo:pseudo}, function (data) {
                $("#msg-txt-new").append(data);
                msg_cont.val("");
            });
            console.log('lol');
        }else {
            id = id.substring(5);
            msg_cont = $("#msg-" + id);
            msg = msg_cont.val();
            $.post('send_msg_pv.php', {msg: msg, id: id}, function (data) {
                $("#msg-txt-" + id).append(data);
                msg_cont.val("");
            });
        }
        console.log(id + " " + msg);
    });

    $("#btn-new").click(function () {
        $('.un-exp').removeClass('exp-act');
        $('#btn-new-msg').addClass('exp-act');
        $(".contenu-msg").hide();
        $('#new-msg').show();
    });

    $('#btn-new-msg').click(function () {
        $('.un-exp').removeClass('exp-act');
        $(this).addClass(("exp-act"));
        $(".contenu-msg").hide();
        $('#new-msg').show();
    });

    $( function() {
        $('#pseudo-msg').autocomplete({
            source: 'autocomplete_pseudo.php'
        });
    })
</script>

</body>

</html>