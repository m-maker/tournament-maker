<div id="footer" style="margin-bottom: 0px;">
	<a href="contact.php" title="Contact"><img src="img/icones/icone_msg.gif" width="25" class="img-responsive img-circle"></a>
	<a href="#" title="Notre Facebook"><img src="img/icones/icone_facebook.ico" width="25" class="img-responsive img-circle"></a>
	<a href="#" title="Notre Twitter"><img src="img/icones/icone_twitter.ico" width="25" class="img-responsive img-circle"></a><br />
    <h3>Mate-Maker &copy; 2017</h3>
    <!--Ce site utilise la base de données de <a href="#">GeoLoc</a><br />
    Merci de bien lire nos <a href="#">Conditions génerales d'utilisation</a> de la plateforme-->
</div>
<?php
    if(isset($_SESSION['id']) AND !empty($_SESSION['id'])){
        ?>
            <script src="js/volet.js"></script>
        <?php
        echo '
        <script>
            function recupCompteNotif() {
                var id = '.$_SESSION["id"].';
                $.post("ajax_notif.php", {id:id}, function(data) {
                    $("#notif-compte").html(data);
                });
            }
            setInterval(recupCompteNotif, 5000);
        </script>
        ';
    }
?>