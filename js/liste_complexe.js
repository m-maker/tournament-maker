$(document).ready(function(){
  $("#formulaire_liste_departement input").click(function(){
    $.ajax({type:"POST", data: $(this).serialize(), url:"formulaire_liste_departement_traitement.php",
      success: function(data){
      $("#post_liste_complexe").html(data);
      $("#post_liste_complexe").click();
    },
    error: function(){
              $("#post").html('Une erreur est survenue.');
            }
          });
          return true;
        });
      });