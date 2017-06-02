$(document).ready(function(){
  $("#liste_departements input").click(function(){
    $.ajax({type:"POST", data: $(this).serialize(), url:"index_ajax2.php",
      success: function(data){
        $("#post").html(data);
      },
      error: function(){
        $("#post").html('Une erreur est survenue.');
      }
    });
    document.getElementById('valider').click();
    $("#form_dpt").reset();
    $(this).reset();
  });
});

$("#form-inscription").submit(function (e) {
    e.preventDefault();
    var pseudo = $("#inputPseudo").val();
    var pass = $("#inputPass").val();
    var tel = $('#inputTel').val();
    var mail = $("#inputEmail").val();
    var return_link = $("#return").val();

    $.post($(this).attr("action"), {return_link:return_link, pseudo:pseudo, pass:pass, tel:tel, mail:mail}, function (data) {
        $('#erreur-insc').html(data);
    });
});

$("#form-connexion").submit(function (e) {
    e.preventDefault();
    var pseudo = $("#pseudo-inp").val();
    var pass = $("#pass-inp").val();
    var return_link = $("#return").val();
    $.post("connexion_check.php", {return:return_link, pseudo:pseudo, pass:pass}, function (data) {
        $('#erreur-co').html(data);
    });
});

$("#form-avis").submit(function (e) {
    e.preventDefault();
    var msg = $('#avis').val();
    $.post('send_msg_pv.php', {id:1, msg:msg}, function (data) {
        $('#post-avis').html('<span id="quit-avis" class="right rouge">X</span><h3 class="vert">Merci d\'avoir laissé votre avis, nous le prendrons en compte</h3>');
    });

    $('#quit-avis').click(function () {
        $('#post-avis').css("display", "none");
    });
});

var numeroInput=1;