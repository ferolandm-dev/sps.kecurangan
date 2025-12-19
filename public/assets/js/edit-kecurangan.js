/* ===========================================================
   CONFIG
=========================================================== */
const EDIT_CFG = window.KECURANGAN_EDIT_CONFIG;

$(document).ready(function () {

    /* ===========================================================
       SELECT2
    =========================================================== */
    $('#id_sales, #jenis_sanksi, #deskripsi_sanksi, #id_ass').select2({
        placeholder: "-- Pilih --",
        width: '100%'
    });

    $('#customer_id').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });

    /* ===========================================================
       DATE PICKER
    =========================================================== */
    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: false, // ❌ matikan dropdown tahun panjang
        yearRange: '2018:2030',
        showButtonPanel: false,
        prevText: '',
        nextText: '',

        beforeShow: function (input, inst) {
            setTimeout(() => {
                injectYearJump(inst);
            }, 0);
        },

        onChangeMonthYear: function () {
            setTimeout(() => {
                injectYearJump($.datepicker._curInst);
            }, 0);
        },

        onSelect: function (dateText) {
            // dd/mm/yy
            const parts = dateText.split('/');
            if (parts.length !== 3) return;

            const month = parseInt(parts[1], 10);
            const year = parseInt(parts[2], 10);

            const kuartal =
                month <= 3 ? `Q1 ${year}` :
                    month <= 6 ? `Q2 ${year}` :
                        month <= 9 ? `Q3 ${year}` :
                            `Q4 ${year}`;

            $('#kuartal').val(kuartal);
        }
    });

    function injectYearJump(inst) {
        const dp = $(inst.dpDiv);
        if (dp.find('.ui-year-jump').length) return;

        const header = dp.find('.ui-datepicker-header');

        const prevYear = $('<button type="button" class="ui-year-jump">«</button>');
        const nextYear = $('<button type="button" class="ui-year-jump">»</button>');

        prevYear.on('click', function () {
            $.datepicker._adjustDate(inst.input, -1, 'Y');
        });

        nextYear.on('click', function () {
            $.datepicker._adjustDate(inst.input, 1, 'Y');
        });

        header.prepend(prevYear);
        header.append(nextYear);
    }


    /* ===========================================================
       SALES → DISTRIBUTOR → ASS
    =========================================================== */
    $('#id_sales').on('change', function () {

        const idSales = $(this).val();

        $('#nama_sales').val('');
        $('#distributor').val('');
        $('#nama_ass').val('');
        $('#id_ass').html('<option value="">-- Pilih ASS --</option>');
        $('#customer_id').html('<option value="">-- Tidak Isi Toko --</option>');

        if (!idSales) return;

        $.getJSON(`${EDIT_CFG.salesDetailUrl}/${idSales}`, function (data) {

            $('#nama_sales').val(data.nama_sales);
            $('#distributor').val(data.distributor);

            if (data.type_salesman == 7) {
                $('#id_ass').html(`
                    <option value="${data.id_salesman}" selected data-nama="${data.nama_sales}">
                        ${data.id_salesman} - ${data.nama_sales}
                    </option>
                `).trigger('change');

                $('#nama_ass').val(data.nama_sales);
                return;
            }

            $.getJSON(`${EDIT_CFG.assBySalesUrl}/${idSales}`, function (list) {

                let opt = '<option value="">-- Pilih ASS --</option>';
                let selectedNamaAss = '';

                list.forEach(a => {

                    const selected = a.ID_SALESMAN == EDIT_CFG.initial.assId ? 'selected' : '';

                    if (selected) selectedNamaAss = a.NAMA_SALESMAN;

                    opt += `
                        <option value="${a.ID_SALESMAN}"
                                data-nama="${a.NAMA_SALESMAN}"
                                ${selected}>
                            ${a.ID_SALESMAN} - ${a.NAMA_SALESMAN}
                        </option>`;
                });

                $('#id_ass').html(opt).trigger('change');

                if (selectedNamaAss) {
                    $('#nama_ass').val(selectedNamaAss);
                }
            });
        });

        $.getJSON(`${EDIT_CFG.customerBySalesUrl}/${idSales}`, function (list) {

            let opt = '<option value="">-- Tidak Isi Toko --</option>';
            list.forEach(c => {
                opt += `<option value="${c.NAMA_CUST}">${c.ID_CUST} - ${c.NAMA_CUST}</option>`;
            });

            $('#customer_id').html(opt);

            if (EDIT_CFG.initial.toko) {
                $('#customer_id').val(EDIT_CFG.initial.toko).trigger('change');
            }
        });
    });

    $('#id_ass').on('change', function () {
        $('#nama_ass').val($(this).find(':selected').data('nama') || '');
    });

    /* ===========================================================
       JENIS → DESKRIPSI → NILAI
    =========================================================== */
    function loadDeskripsi(jenis, selected = null) {

        $('#deskripsi_sanksi').html('<option value="">-- Pilih Deskripsi --</option>');
        $('#nilai_sanksi').val('');

        if (!jenis) return;

        $.getJSON(`${EDIT_CFG.keteranganByJenisUrl}/${encodeURIComponent(jenis)}`, function (data) {

            let opt = '<option value="">-- Pilih Deskripsi --</option>';
            data.forEach(d => {
                opt += `
                    <option value="${d.KETERANGAN}"
                        ${d.KETERANGAN === selected ? 'selected' : ''}>
                        ${d.KETERANGAN}
                    </option>`;
            });

            $('#deskripsi_sanksi').html(opt).trigger('change');
        });
    }

    function loadNilai(jenis, deskripsi) {

        if (!jenis || !deskripsi) {
            $('#nilai_sanksi').val('');
            return;
        }

        $.getJSON(`${EDIT_CFG.nilaiSanksiUrl}/${jenis}/${encodeURIComponent(deskripsi)}`, function (resp) {

            const nilai = parseInt(resp?.NILAI ?? 0);
            $('#nilai_sanksi').val(
                'Rp ' + new Intl.NumberFormat('id-ID').format(isNaN(nilai) ? 0 : nilai)
            );
        });
    }

    $('#jenis_sanksi').on('change', function () {
        loadDeskripsi($(this).val());
    });

    $('#deskripsi_sanksi').on('change', function () {
        loadNilai($('#jenis_sanksi').val(), $(this).val());
    });

    /* ===========================================================
       INIT EDIT MODE
    =========================================================== */
    if (EDIT_CFG.initial.salesId) {
        $('#id_sales').val(EDIT_CFG.initial.salesId).trigger('change');
    }

    if (EDIT_CFG.initial.jenisSanksi) {
        loadDeskripsi(
            EDIT_CFG.initial.jenisSanksi,
            EDIT_CFG.initial.deskripsiSanksi
        );
    }

    /* ===========================================================
       UPLOAD FOTO
    =========================================================== */
    $('#btn-upload').on('click', function () {
        $('#bukti').trigger('click');
    });

    /* ===========================================================
       DELETE FOTO LAMA (❌)
    =========================================================== */
    $(document).on('click', '.btn-delete-existing', function () {


        const fotoId = $(this).data('id');

        $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'deleted_photos[]')
            .val(fotoId)
            .appendTo('form');

        $(this).closest('.existing-photo').remove();
    });

    /* ===========================================================
    PREVIEW FOTO BARU + ❌ (FIX MULTI ADD)
    =========================================================== */
    $('#bukti').on('change', function () {

        const input = this;
        const preview = $('#preview-container');

        const existingCount = $('.existing-photo').length;
        const currentNewCount = $('.new-photo').length;

        const newFiles = Array.from(input.files);
        const total = existingCount + currentNewCount + newFiles.length;

        if (total > 5) {

            $('#photo-alert-container').html(`
            <div class="alert alert-danger alert-with-icon alert-dismissible fade show"
                data-notify="container"
                role="alert"
                style="
                    background: rgba(231,76,60,0.2);
                    border: 1px solid #e74c3c;
                    color: #721c24;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#721c24;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_bell-53 mr-2"></span>
                <span data-notify="message">
                    Maksimal <b>5 foto</b> (termasuk foto lama).
                </span>
            </div>
        `);
            input.value = '';
            return;
        }


        /* 🔥 GABUNG FILE LAMA + BARU */
        const dt = new DataTransfer();

        // file lama
        document.querySelectorAll('.new-photo').forEach(el => {
            const index = el.dataset.index;
            const file = window._newFiles?.[index];
            if (file) dt.items.add(file);
        });

        // file baru
        newFiles.forEach(file => dt.items.add(file));

        input.files = dt.files;

        /* 🔥 SIMPAN FILE KE GLOBAL */
        window._newFiles = Array.from(input.files);

        /* 🔥 RENDER ULANG PREVIEW */
        preview.html('');

        window._newFiles.forEach((file, index) => {

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.append(`
                <div class="position-relative m-1 new-photo" data-index="${index}">
                    <img src="${e.target.result}"
                         style="width:100px;height:100px;
                                object-fit:cover;
                                border-radius:10px;
                                border:1px solid #ccc;
                                cursor:pointer;">
                    <button type="button" class="btn-remove">×</button>
                </div>
            `);
            };

            reader.readAsDataURL(file);
        });

    });


    /* ===========================================================
   DELETE FOTO BARU (❌)
    =========================================================== */
    $(document).on('click', '#preview-container .btn-remove', function () {

        const $photo = $(this).closest('.new-photo');
        const index = $photo.data('index');

        const input = document.getElementById('bukti');
        const dt = new DataTransfer();

        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });

        input.files = dt.files;
        $photo.remove();
    });

    /* ===========================================================
   MODAL PREVIEW FOTO (FIX)
=========================================================== */

    let previewImages = [];
    let currentIndex = 0;

    /* 🔥 KLIK FOTO LAMA */
    $(document).on('click', '.existing-img', function () {

        previewImages = $('.existing-img').map(function () {
            return $(this).attr('src');
        }).get();

        currentIndex = previewImages.indexOf($(this).attr('src'));

        openPreview();
    });

    /* 🔥 KLIK FOTO BARU */
    $(document).on('click', '.new-photo img', function () {

        previewImages = $('.new-photo img').map(function () {
            return $(this).attr('src');
        }).get();

        currentIndex = previewImages.indexOf($(this).attr('src'));

        openPreview();
    });

    /* ================= OPEN MODAL ================= */
    function openPreview() {
        $('#modalImage').attr('src', previewImages[currentIndex]);
        $('#modalPreview').modal('show');
    }

    /* ================= NAVIGASI ================= */
    $('#modalNext').on('click', function () {
        currentIndex = (currentIndex + 1) % previewImages.length;
        $('#modalImage').attr('src', previewImages[currentIndex]);
    });

    $('#modalPrev').on('click', function () {
        currentIndex = (currentIndex - 1 + previewImages.length) % previewImages.length;
        $('#modalImage').attr('src', previewImages[currentIndex]);
    });

    /* ===========================================================
    KEYBOARD NAVIGATION (← →)
    =========================================================== */
    $(document).on('keydown', function (e) {

        // hanya aktif kalau modal terbuka
        if (!$('#modalPreview').hasClass('show')) return;

        // panah kanan →
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % previewImages.length;
            $('#modalImage').attr('src', previewImages[currentIndex]);
        }

        // panah kiri ←
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + previewImages.length) % previewImages.length;
            $('#modalImage').attr('src', previewImages[currentIndex]);
        }

        // ESC untuk tutup modal
        if (e.key === 'Escape') {
            $('#modalPreview').modal('hide');
        }
    });


});
