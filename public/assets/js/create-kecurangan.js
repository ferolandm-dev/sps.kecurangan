$(document).ready(function () {

    const CFG = window.KECURANGAN_CONFIG;

    /* ===============================
       SELECT2 SETUP
    =============================== */
    $('#id_sales, #jenis_sanksi, #deskripsi_sanksi, #id_ass').select2({
        placeholder: "-- Pilih --",
        width: '100%'
    });

    $('#customer_id').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });

    /* ===============================
       FOTO PREVIEW
    =============================== */
    let selectedFiles = [];
    let currentIndex = 0;
    const MAX_FILES = 5;
    const $fileInput = $('#bukti');
    const $previewContainer = $('#preview-container');

    $('#btn-upload').on('click', function () {
        $fileInput.val('');
        $fileInput.trigger('click');
    });

    function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        $fileInput[0].files = dt.files;
    }

    $fileInput.on('change', function () {
        const incoming = Array.from(this.files || []);
        const newFiles = incoming.filter(f =>
            !selectedFiles.some(sf => sf.name === f.name && sf.size === f.size)
        );

        selectedFiles = [...selectedFiles, ...newFiles].slice(0, MAX_FILES);
        renderPreview();
        syncInputFiles();
    });

    function renderPreview() {
        $previewContainer.empty();
        selectedFiles.forEach((file, index) => {
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) return;

            const reader = new FileReader();
            reader.onload = e => {
                $previewContainer.append(`
                    <div class="position-relative m-1">
                        <img src="${e.target.result}" class="preview-img" data-index="${index}"
                             style="width:100px;height:100px;object-fit:cover;border-radius:10px;cursor:pointer;">
                        <button type="button" class="btn-remove" data-index="${index}">×</button>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });
    }

    $(document).on('click', '.btn-remove', function (e) {
        e.stopPropagation();
        selectedFiles.splice($(this).data('index'), 1);
        renderPreview();
        syncInputFiles();
    });

    /* ===============================
       MODAL PREVIEW
    =============================== */
    function getPreviewEls() {
        return $('#preview-container img.preview-img').toArray();
    }

    $(document).on('click', '.preview-img', function () {
        const els = getPreviewEls();
        currentIndex = els.indexOf(this);
        $('#modalImage').attr('src', $(this).attr('src'));
        $('#modalPreview').modal('show');
    });

    $('#modalNext').on('click', function () {
        const els = getPreviewEls();
        currentIndex = (currentIndex + 1) % els.length;
        $('#modalImage').attr('src', $(els[currentIndex]).attr('src'));
    });

    $('#modalPrev').on('click', function () {
        const els = getPreviewEls();
        currentIndex = (currentIndex - 1 + els.length) % els.length;
        $('#modalImage').attr('src', $(els[currentIndex]).attr('src'));
    });

    $('#tanggal').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: false, // ❌ matikan dropdown tahun
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
            const parts = dateText.split('/');
            if (parts.length !== 3) return;

            const month = parseInt(parts[1], 10);
            const year = parseInt(parts[2], 10);

            const kuartal =
                month <= 3 ? 'Q1' :
                    month <= 6 ? 'Q2' :
                        month <= 9 ? 'Q3' : 'Q4';

            $('#kuartal').val(`${kuartal} ${year}`);
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



    /* ===============================
       LOAD SALES → ASS → CUSTOMER
    =============================== */
    $('#id_sales').on('change', function () {
        const idSales = $(this).val();

        $('#nama_sales, #distributor, #nama_ass').val('');
        $('#id_ass').html('<option value="">-- Pilih ASS --</option>');
        $('#customer_id').html('<option value="">-- Tidak Isi Toko --</option>');

        if (!idSales) return;

        $.getJSON(`${CFG.salesDetailUrl}/${idSales}`, function (data) {

            $('#nama_sales').val(data.nama_sales);
            $('#distributor').val(data.distributor);

            // TYPE 7 → ASS = DIRI SENDIRI
            if (data.type_salesman == 7) {
                $('#id_ass')
                    .html(`<option value="${data.id_salesman}" data-nama="${data.nama_sales}">
                            ${data.id_salesman} - ${data.nama_sales}
                          </option>`)
                    .trigger('change');

                $('#nama_ass').val(data.nama_sales);
                return;
            }

            // LOAD ASS NORMAL
            $.getJSON(`${CFG.assBySalesUrl}/${idSales}`, function (list) {
                let opt = '<option value="">-- Pilih ASS --</option>';
                list.forEach(a => {
                    opt += `<option value="${a.ID_SALESMAN}" data-nama="${a.NAMA_SALESMAN}">
                                ${a.ID_SALESMAN} - ${a.NAMA_SALESMAN}
                            </option>`;
                });
                $('#id_ass').html(opt);
            });
        });

        // LOAD CUSTOMER
        $.getJSON(`${CFG.customerBySalesUrl}/${idSales}`, function (data) {
            let opt = '<option value="">-- Tidak Isi Toko --</option>';
            data.forEach(c => {
                opt += `<option value="${c.NAMA_CUST}">
                            ${c.ID_CUST} - ${c.NAMA_CUST}
                        </option>`;
            });
            $('#customer_id').html(opt);
        });
    });

    $('#id_ass').on('change', function () {
        $('#nama_ass').val($(this).find(':selected').data('nama') || '');
    });

    /* ===============================
       SANKSI → DESKRIPSI → NILAI
    =============================== */
    $('#jenis_sanksi').on('change', function () {
        const jenis = $(this).val();
        $('#deskripsi_sanksi').html('<option value="">-- Pilih Deskripsi --</option>');
        $('#nilai_sanksi').val('');

        if (!jenis) return;

        $.getJSON(`${CFG.sanksiDeskripsiUrl}/${jenis}`, function (data) {
            let opt = '<option value="">-- Pilih Deskripsi --</option>';
            data.forEach(i => opt += `<option value="${i.KETERANGAN}">${i.KETERANGAN}</option>`);
            $('#deskripsi_sanksi').html(opt);
        });
    });

    $('#deskripsi_sanksi').on('change', function () {
        const jenis = $('#jenis_sanksi').val();
        const deskripsi = $(this).val();
        if (!jenis || !deskripsi) return;

        $.getJSON(`${CFG.nilaiSanksiUrl}/${jenis}/${encodeURIComponent(deskripsi)}`, function (r) {
            $('#nilai_sanksi').val('Rp ' + new Intl.NumberFormat('id-ID').format(r?.NILAI ?? 0));
        });
    });

    /* ===========================================================
   KEYBOARD NAVIGATION (← → ESC)
=========================================================== */
    $(document).on('keydown', function (e) {

        if (!$('#modalPreview').hasClass('show')) return;

        const els = getPreviewEls();
        if (!els.length) return;

        // →
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % els.length;
            $('#modalImage').attr('src', $(els[currentIndex]).attr('src'));
        }

        // ←
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + els.length) % els.length;
            $('#modalImage').attr('src', $(els[currentIndex]).attr('src'));
        }

        // ESC
        if (e.key === 'Escape') {
            $('#modalPreview').modal('hide');
        }
    });

});
