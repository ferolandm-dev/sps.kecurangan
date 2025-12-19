$(document).ready(function () {

    let fotoList = [];
    let currentIndex = 0;

    /* ===============================
       MODAL LIHAT BUKTI
    =============================== */
    $(document).on('click', '.btn-lihat-bukti', function () {

        const id = $(this).data('id');
        fotoList = [];
        currentIndex = 0;

        $('#modalBukti').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });

        $.ajax({
            url: `/kecurangan/${id}/bukti`,
            method: 'GET',
            beforeSend: () => {
                $('#modalImage').attr('src', '').attr('alt', 'Memuat...');
            },
            success: (response) => {
                if (!response.length) {
                    $('#modalImage').attr('alt', 'Tidak ada foto.');
                    return;
                }

                fotoList = response.map(f => f.url);
                showModalImage(0);
            },
            error: () => {
                $('#modalImage').attr('alt', 'Gagal memuat foto.');
            }
        });
    });

    function showModalImage(index) {
        if (!fotoList.length) return;

        $('#modalImage').addClass('fade-out');
        setTimeout(() => {
            $('#modalImage')
                .attr('src', fotoList[index])
                .removeClass('fade-out');
        }, 150);
    }

    $('#modalNext').on('click', function () {
        if (!fotoList.length) return;
        currentIndex = (currentIndex + 1) % fotoList.length;
        showModalImage(currentIndex);
    });

    $('#modalPrev').on('click', function () {
        if (!fotoList.length) return;
        currentIndex = (currentIndex - 1 + fotoList.length) % fotoList.length;
        showModalImage(currentIndex);
    });

    /* ===============================
       KEYBOARD CONTROL
    =============================== */
    $(document).on('keydown', function (e) {
        if (!$('#modalBukti').hasClass('show')) return;

        if (e.key === 'Escape') $('#modalBukti').modal('hide');
        if (e.key === 'ArrowRight') $('#modalNext').click();
        if (e.key === 'ArrowLeft') $('#modalPrev').click();
    });

    /* ===============================
       LOCK SCROLL SAAT MODAL
    =============================== */
    $('#modalBukti')
        .on('shown.bs.modal', () => $('body').css('overflow', 'hidden'))
        .on('hidden.bs.modal', () => {
            $('#modalImage').attr('src', '');
            fotoList = [];
            currentIndex = 0;
            $('body').css('overflow', 'auto');
        });

    /* ===============================
       MODAL KETERANGAN
    =============================== */
    $(document).on('click', '.btn-lihat-keterangan', function () {
        $('#isiKeterangan').text($(this).data('keterangan'));
        $('#modalKeterangan').modal('show');
    });

});

/* ===============================
   MODAL KONFIRMASI (DELETE / VALIDASI)
=============================== */
$(document).on('click', '.btn-confirm', function () {

    const action = $(this).data('action');
    const url = $(this).data('url');

    $('#confirmForm').attr('action', url);
    $('#confirmForm input[name="_method"]').remove();

    if (action === 'delete') {
        $('#confirmIcon').css('color', '#e74c3c');
        $('#confirmTitle').text('Hapus Data?');
        $('#confirmMessage').text('Data yang dihapus tidak dapat dikembalikan.');
        $('#confirmForm').append('<input type="hidden" name="_method" value="DELETE">');
    }

    if (action === 'validasi') {
        $('#confirmIcon').css('color', '#29b14a');
        $('#confirmTitle').text('Validasi Data?');
        $('#confirmMessage').text('Pastikan data sudah benar sebelum divalidasi.');
    }

    $('#modalConfirm').modal('show');
});
