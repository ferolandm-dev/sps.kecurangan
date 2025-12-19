/* ============================================================
   OPEN MODAL & LOAD DATA KECURANGAN ASS
   ============================================================ */
function showKecurangan(idAss, pageUrl = null) {

    // === Loading state ===
    $("#tableKecurangan").html(`
        <tr>
            <td colspan="5" class="text-center text-muted py-3">Loading...</td>
        </tr>
    `);

    $("#kecuranganPagination").html("");
    $("#modalKecurangan").modal('show');

    // === URL handler ===
    let baseUrl = window.ASS_CONFIG?.kecuranganUrl;
    let url = pageUrl ?? `${baseUrl}/${idAss}`;

    // === AJAX ===
    $.get(url, function (res) {

        let pg = res.data;
        let list = pg.data || [];
        let indexStart = pg.from ?? 1;

        let rows = "";

        if (list.length === 0) {
            rows = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        Tidak ada data
                    </td>
                </tr>
            `;
        } else {
            list.forEach((row, i) => {
                rows += `
                    <tr>
                        <td class="text-center">${indexStart + i}</td>
                        <td>${row.JENIS_SANKSI ?? '-'}</td>
                        <td>${row.KETERANGAN_SANKSI ?? '-'}</td>
                        <td>Rp ${new Intl.NumberFormat("id-ID").format(row.NILAI_SANKSI ?? 0)}</td>
                        <td>${row.TANGGAL}</td>
                    </tr>
                `;
            });
        }

        $("#tableKecurangan").html(rows);

        // === Total nilai ===
        $("#kecuranganTotal").html(`
            <div class="mb-2 text-right">
                <p class="text-danger font-weight-bold" style="font-size:14px;">
                    Total Nilai Sanksi:
                    Rp ${new Intl.NumberFormat("id-ID").format(res.total_nilai ?? 0)}
                </p>
            </div>
        `);

        // === Pagination ===
        $("#kecuranganPagination").html(res.pagination);

        // === Pagination AJAX ===
        $("#kecuranganPagination a.page-link").on("click", function (e) {
            e.preventDefault();
            showKecurangan(idAss, $(this).attr("href"));
        });

    }).fail(function (xhr) {

        console.error("AJAX Error:", xhr.responseText);

        $("#tableKecurangan").html(`
            <tr>
                <td colspan="5" class="text-center text-danger py-3">
                    Gagal memuat data
                </td>
            </tr>
        `);
    });
}
