<div id="volet">

    <?php
        $compte_notif = Notifications::getCompteNewNotif($_SESSION["id"]);
        $compte_msg = compteMessagesNonVus($_SESSION["id"]);
    ?>

    <h3 class="volet-titre"><?php echo $_SESSION["pseudo"]; ?></h3>

    <!--<img src="../img/logo.png" width="100%" height="80" style="margin-bottom: 1%;">-->

    <?php $url_complete = $_SERVER['REQUEST_URI']; ?>

        <div id="accueil">
            <a href="index.php" <?php activer_item('index.php'); ?>>
                <span class="glyphicon glyphicon-home"></span> Gerer mes tournois
            </a>
        </div>

        <div id="mes_paiements">
            <a href="organiser_tournoi.php" <?php activer_item('organiser_tournoi.php'); ?>>
                <span class="glyphicon glyphicon-asterisk"></span> Organiser un tournoi
            </a>
        </div>


        <hr class="separateur-volet">

    <div id="mes_messages">
        <a href="../notifs.php" <?php activer_item('notifs.php'); ?>>
            <span class="glyphicon glyphicon-alert"></span>
            <span id="notif-compte">
                <?php if ($compte_notif > 0){
                    echo '<b>Notifications ('.$compte_notif.')</b>';
                }else{
                    echo 'Notifications ('.$compte_notif.')';} ?>
            </span>
        </a>
    </div>

    <div id="mes_messages">
        <a href="../mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
            <span class="glyphicon glyphicon-envelope"></span>
            <span class="msg-compte">
                <?php if ($compte_msg > 0){
                    echo '<b>Messagerie ('.$compte_msg.')</b>';
                }else{
                    echo 'Messagerie ('.$compte_msg.')';} ?>
            </span>
        </a>
    </div>

    <div id="mes_messages">
        <a href="../parametres.php" <?php activer_item('parametres.php'); ?>>
            <span class="glyphicon glyphicon-cog"></span> Parametres
        </a>
    </div>

    <hr class="separateur-volet">

        <div id="deco">
            <a href="../deconnexion.php">
                <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
            </a>
        </div>

</div>

