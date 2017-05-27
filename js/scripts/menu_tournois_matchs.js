/**
 * Created by Niquelesstup on 19/05/2017.
 */
$(".show").click(function() {
    $(".show").removeClass("acti");
    $(this).addClass("acti");
    $(".cont").hide();
    var id = $(this).attr("id");
    if (id == "show-matchs")
        $("#matchs").show();
    else if (id == "show-tournois")
        $("#tournois").show();
});