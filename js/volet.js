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

$('#quit-avis').click(function () {
    $('#post-avis').css("display", "none");
});