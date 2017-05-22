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

function nomducomplexe() {
  var nom = document.querySelector('#post input:checked + input').value;
  var id = document.querySelector('#post input:checked').value;
  document.getElementById('nom_complexe').innerHTML= nom;
  document.getElementById('glyph_complexe').className= "glyphicon glyphicon-ok-circle";
  document.getElementById('nom_complexe_input').value= nom;
  document.getElementById('id_complexe_input').value= id;
}

var numeroInput=1;