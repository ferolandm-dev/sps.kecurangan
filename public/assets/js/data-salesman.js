/* ===========================================================
   CONFIG
=========================================================== */
const SALESMAN_CFG = window.SALESMAN_CFG;

/* ===========================================================
   OPEN MODAL & LOAD DATA
=========================================================== */
function showKecurangan(idSales, pageUrl = null) {

    $("#tableKecurangan").html(`
        <tr>
            <td colspan="5" class="text-center text-muted py-3">Loading...</td>
        </tr>
    `);

    $("#kecuranganPagination").html("");
    $("#modalKecurangan").modal('show');

    /* URL FINAL */
    const url = pageUrl ?? `${SALESMAN_CFG.getKecuranganUrl}/${idSales}`;

    $.get(url, function (res) {

        let indexStart = res.first ?? 1;
        let rows = "";

        if (!res.data || res.data.length === 0) {
            rows = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        Tidak ada data
                    </td>
                </tr>
            `;
        } else {
            res.data.forEach((row, i) => {
                rows += `
                    <tr>
                        <td class="text-center">${indexStart + i}</td>
                        <td>${row.JENIS_SANKSI ?? '-'}</td>
                        <td>${row.KETERANGAN_SANKSI ?? '-'}</td>
                        <td>
                            Rp ${new Intl.NumberFormat('id-ID').format(row.NILAI_SANKSI ?? 0)}
                        </td>
                        <td>${row.TANGGAL ?? '-'}</td>
                    </tr>
                `;
            });
        }

        $("#tableKecurangan").html(rows);

        /* TOTAL NILAI */
        $("#kecuranganTotal").html(`
            <div class="mb-2 text-right">
                <p class="text-danger font-weight-bold" style="font-size:14px;">
                    Total Nilai Sanksi:
                    Rp ${new Intl.NumberFormat('id-ID').format(res.total_nilai ?? 0)}
                </p>
            </div>
        `);

        /* PAGINATION */
        $("#kecuranganPagination").html(res.pagination ?? "");

        $("#kecuranganPagination").find("a.page-link").on("click", function (e) {
            e.preventDefault();
            showKecurangan(idSales, $(this).attr("href"));
        });

    }).fail(function () {

        $("#tableKecurangan").html(`
            <tr>
                <td colspan="5" class="text-center text-danger py-3">
                    Gagal memuat data
                </td>
            </tr>
        `);
    });
}
