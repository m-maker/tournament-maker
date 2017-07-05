<div id="volet">

    <?php
        if (isset($_SESSION['id'])) {
            $compte_notif = Notifications::getCompteNewNotif($_SESSION["id"]);
            $compte_msg = compteMessagesNonVus($_SESSION["id"]);
        }
    ?>

    <h3 class="volet-titre">
        <img src="<?php echo $_SESSION["membre_avatar"]; ?>" width="30" /> <?php echo $_SESSION["pseudo"]; ?>
    </h3>

    <div id="accueil">
        <a href="index.php" <?php activer_item('index.php'); ?> >
            <?php 
                if ($_SESSION["membre_orga"] == 0){
                    ?>
                        <span class="glyphicon glyphicon-search"></span> Trouver des matchs
                    <?php 
                }
                else{ 
                    ?>
                        <span class="glyphicon glyphicon-home"></span> Gerer mes matchs
                    <?php 
                }
            ?>
        </a>
    </div>

    <?php 
    if(isset($_SESSION['id']) && !empty($_SESSION['id'])){ 
        if ($_SESSION["membre_orga"] == 0) { 
            ?>
                <div id="mes_matchs">
                    <a href="mes_matchs.php" <?php activer_item('mes_matchs.php'); ?>>
                        <span class="glyphicon glyphicon-thumbs-up"></span> Mes tournois
                    </a>
                </div>
                <div id="invite">
                    <a href="invite.php" <?php activer_item('invite.php'); ?>>
                        <span class="glyphicon glyphicon-plus-sign"></span> Invitations
                    </a>
                </div>
                <div id="organiser">
                    <a href="organiser_match.php" <?php activer_item('organiser_match.php'); ?>>
                        <span class="glyphicon glyphicon-plus-sign"></span> Oragniser un match
                    </a>
                </div>
            <?php
        }
        else{ 
            ?>
                <div id="mes_paiements" <?php activer_item('organiser_tournoi.php'); ?>>
                    <a href="organisateur/organiser_tournoi.php">
                        <span class="glyphicon glyphicon-asterisk"></span> Organiser un tournoi
                    </a>
                </div>
            <?php 
        } 
        ?>

        <hr class="separateur-volet">

        <div id="mes_messages">
            <a href="notifs.php" <?php activer_item('notifs.php'); ?>>
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
            <a href="mes_messages.php" <?php activer_item('mes_messages.php'); ?>>
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
            <a href="parametres.php" <?php activer_item('parametres.php'); ?>>
                <span class="glyphicon glyphicon-cog"></span> Parametres
            </a>
        </div>

        <hr class="separateur-volet">

        <div id="deco">
                <a href="deconnexion.php">
                    <span class="glyphicon glyphicon-ban-circle"></span> Deconnexion
                </a>
        </div>

        <hr class="separateur-volet">

        <div class="center" id="post-avis">
            <div>
                <div>
                    <span id="quit-avis" class="right rouge">X</span>
                    <h3>Le site est actuellement en béta ! Aidez-nous à améliorer la plateforme en donnant votre avis : </h3>
                    <form id="form-avis" class="form-horizontal">
                        <div class="form-group">
                            <textarea id="avis" class="form-control btn-grand" placeholder="Votre avis..."></textarea>
                            <button type="submit" class="form-control btn btn-success btn-grand">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <b>Les paiements en ligne seront disponibles dès mardi 06 juin.</b>
            </div>
        </div>

        <?php
	}
    ?>

</div>

