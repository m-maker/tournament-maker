$(document).ready(function(){
  $("#liste_departements input").click(function(){
    $.ajax({type:"POST", data: $(this).serialize(), url:"organiser_match_ajax.php",
      success: function(data){
        $("#post").html(data);
      },
      error: function(){
        $("#post").html('Une erreur est survenue.');
      }
    });
    document.getElementById('valider').click();
  });
});

$(document).ready(function(){
  $("#liste_departements2 input").click(function(){
    $.ajax({type:"POST", data: $(this).serialize(), url:"index_ajax.php",
      success: function(data){
        $("#post2").html(data);
      },
      error: function(){
        $("#post2").html('Une erreur est survenue.');
      }
    });
    document.getElementById('valider2').click();
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




var numeroInput=1;