$(document).ready(function() {
    function loadData() {
        var keyword = $('.keyword').val() || "";
        var kelas = $('.filter-kelas').val() || "";
        var jurusan = $('.filter-jurusan').val() || "";
        
        $.get("../ajax/carisiswa.php?keyword=" + keyword + "&kelas=" + kelas + "&jurusan=" + jurusan, function(data) {
            $('#container_siswa').html(data);
            $('#navdd').dropdown();
        });
    }

    $('.keyword').on('keyup', function() {
        loadData();
    });
});