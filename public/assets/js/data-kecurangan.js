/* ===========================================================
   CONFIG
=========================================================== */
const REPORT_CFG = window.KECURANGAN_REPORT_CONFIG;

/* ===========================================================
   INIT SELECT2 INSIDE MODAL
=========================================================== */
function initSelect2InModal(modalId) {
    const modal = $(modalId);

    modal.on("shown.bs.modal", function () {
        modal.find("select.select2").select2({
            dropdownParent: modal,
            width: "100%"
        });
    });
}

initSelect2InModal("#modalFilter");

/* ===========================================================
   LOAD KETERANGAN BY JENIS SANKSI
=========================================================== */
function loadKeterangan(jenis, targetSelect) {

    const $target = $(targetSelect);
    $target.html(`<option value="">Memuat...</option>`);

    if (!jenis) {
        $target.html(`<option value="">Semua Keterangan</option>`);
        return;
    }

    $.ajax({
        url: `${REPORT_CFG.keteranganByJenisUrl}/${encodeURIComponent(jenis)}`,
        method: "GET",
        success: function (data) {

            let opt = `<option value="">Semua Keterangan</option>`;
            data.forEach(item => {
                opt += `<option value="${item.KETERANGAN}">
                            ${item.KETERANGAN}
                        </option>`;
            });

            $target.html(opt).trigger("change");
        },
        error: function () {
            $target.html(`<option value="">Gagal memuat</option>`);
        }
    });
}

/* ================= FILTER ================= */
$("#filter_jenis_sanksi_filter").on("change", function () {
    loadKeterangan($(this).val(), "#filter_keterangan_sanksi_filter");
});

/* ===========================================================
   MODAL BUKTI (IMAGE SLIDER)
=========================================================== */
$(document).ready(function () {

    let fotoList = [];
    let currentIndex = 0;

    $(".btn-lihat-bukti").on("click", function () {

        const id = $(this).data("id");
        fotoList = [];
        currentIndex = 0;

        $("#modalBukti").modal({
            backdrop: "static",
            keyboard: true,
            show: true
        });

        $.ajax({
            url: `${REPORT_CFG.buktiByIdUrl}/${id}/bukti`,
            method: "GET",
            beforeSend: () => {
                $("#modalImage").attr("src", "").attr("alt", "Memuat...");
            },
            success: function (response) {

                if (!response.length) {
                    $("#modalImage").attr("alt", "Tidak ada foto.");
                    return;
                }

                fotoList = response.map(f => f.url);
                showModalImage(0);
            },
            error: () => {
                $("#modalImage").attr("alt", "Gagal memuat foto.");
            }
        });
    });

    function showModalImage(index) {
        if (!fotoList.length) return;

        $("#modalImage").addClass("fade-out");
        setTimeout(() => {
            $("#modalImage")
                .attr("src", fotoList[index])
                .removeClass("fade-out");
        }, 150);
    }

    $("#modalNext").on("click", function () {
        if (!fotoList.length) return;
        currentIndex = (currentIndex + 1) % fotoList.length;
        showModalImage(currentIndex);
    });

    $("#modalPrev").on("click", function () {
        if (!fotoList.length) return;
        currentIndex = (currentIndex - 1 + fotoList.length) % fotoList.length;
        showModalImage(currentIndex);
    });

    $(document).on("keydown", function (e) {
        if (!$("#modalBukti").hasClass("show")) return;
        if (e.key === "Escape") $("#modalBukti").modal("hide");
        if (e.key === "ArrowRight") $("#modalNext").click();
        if (e.key === "ArrowLeft") $("#modalPrev").click();
    });

    $("#modalBukti")
        .on("shown.bs.modal", () => $("body").css("overflow", "hidden"))
        .on("hidden.bs.modal", () => {
            $("#modalImage").attr("src", "");
            fotoList = [];
            $("body").css("overflow", "auto");
        });

    /* ===============================
       MODAL KETERANGAN
    =============================== */
    $(".btn-lihat-keterangan").on("click", function () {
        $("#isiKeterangan").text($(this).data("keterangan"));
        $("#modalKeterangan").modal("show");
    });

    $(document).ready(function () {

        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: false, // ❌ MATIKAN DROPDOWN TAHUN
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
            }
        });
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
   FIX FILTER DATE (dd/mm/yyyy -> yyyy-mm-dd)
=========================================================== */
    $('#modalFilter form').on('submit', function () {

        $(this).find('.datepicker').each(function () {
            const val = $(this).val();

            if (!val) return;

            // dd/mm/yyyy -> yyyy-mm-dd
            const parts = val.split('/');
            if (parts.length === 3) {
                const formatted = `${parts[2]}-${parts[1]}-${parts[0]}`;
                $(this).val(formatted);
            }
        });

    });

});
