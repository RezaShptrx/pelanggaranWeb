$(document).ready(function() {
    $('.keyword').on('keyup', function() {

        $.get("../ajax/cariguru.php?keyword=" + $('.keyword').val(), function(data) {
            $('#container_guru').html(data); 
            $('#navdd').dropdown();
        })

    });
});