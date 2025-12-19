$(document).on('click', '.btn-confirm', function() {

    // --- Ambil aksi dan url ---
    let action = $(this).data('action');
    let url = $(this).data('url');

    // Set form action ke URL (delete / validasi)
    $('#confirmForm').attr('action', url);

    // Hapus method lama untuk memastikan tidak dobel
    $('#confirmForm input[name="_method"]').remove();

    /* ============================
       MODE DELETE
       ============================ */
    if (action === 'delete') {

        // Title & pesan
        $('#confirmTitle').text('Hapus Data?');
        $('#confirmMessage').text('Data yang dihapus tidak dapat dikembalikan.');

        // Set method DELETE
        $('#confirmForm').append('<input type="hidden" name="_method" value="DELETE">');

        // Tombol submit menjadi merah
        $('#confirmForm button[type="submit"]')
            .removeClass('btn-success')
            .addClass('btn-danger');
    }

    /* ============================
       MODE VALIDASI
       ============================ */
    if (action === 'validasi') {

        // Ganti ikon modal
        $('#confirmIcon')
            .removeClass()
            .addClass('now-ui-icons ui-1_check')
            .css('color', '#29b14a');

        // Title & pesan modal validasi
        $('#confirmTitle').text('Validasi Data?');
        $('#confirmMessage').text('Pastikan data sudah benar sebelum divalidasi.');

        // Tombol submit menjadi hijau
        $('#confirmForm button[type="submit"]')
            .removeClass('btn-danger')
            .addClass('btn-success');
    }

    // --- Tampilkan modal ---
    $('#modalConfirm').modal('show');
});