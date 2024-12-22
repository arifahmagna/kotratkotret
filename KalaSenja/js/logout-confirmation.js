// logout-confirmation.js
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.querySelectorAll('.logout-btn');

    logoutBtn.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah aksi default

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari akun Anda!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mengirim permintaan logout ke server menggunakan Fetch API
                    fetch('logout.php', {
                        method: 'POST',
                    })
                    .then(response => response.text())  // Menangani response dari server
                    .then(data => {
                        // Jika berhasil, arahkan pengguna ke halaman login atau ke halaman tertentu
                        window.location.href = '../fungsi/login.php';  // Ganti sesuai halaman yang diinginkan
                    })
                    .catch(error => console.error('Logout failed: ', error));
                }
            });
        });
    });
});
