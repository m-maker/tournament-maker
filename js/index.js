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

var numeroInput=1;