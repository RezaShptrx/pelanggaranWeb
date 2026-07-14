$(document).ready(function() {
    $('.keyword').on('keyup', function() {

        $.get("../ajax/carisiswa.php?keyword=" + $('.keyword').val(), function(data) {
            $('#container_siswa').html(data);
            $('#navdd').dropdown();
        })

    });
});