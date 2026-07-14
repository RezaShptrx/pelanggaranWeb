function berhasil() {
    Swal.fire({
        position: 'center-center',
        icon: 'success',
        title: 'Laporan berhasil dibuat!',
        showConfirmButton: false,
        timer: 1000
      }).then(function() {
          return window.location="../index.php";
      })
}


function gagal() {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Laporan gagal dibuat!',
    }).then(function() {
        return window.location="../index.php";
    })
}