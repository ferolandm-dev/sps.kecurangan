/* ============================================================
   OPEN MODAL & LOAD DATA SALESMAN PER DISTRIBUTOR
   ============================================================ */
function showSalesman(idDistributor, pageUrl = null) {

    // === Loading state ===
    $("#tableSalesman").html(`
        <tr>
            <td colspan="3" class="text-center text-muted py-3">
                Loading...
            </td>
        </tr>
    `);

    $("#salesmanPagination").html("");
    $("#modalSalesman").modal("show");

    // === URL handler ===
    let baseUrl = window.DISTRIBUTOR_CONFIG?.salesmanUrl;
    let url = pageUrl ?? `${baseUrl}/${idDistributor}`;

    // === AJAX ===
    $.get(url, function (res) {

        let list = res.data || [];
        let indexStart = res.first ?? 1;

        let rows = "";

        if (list.length === 0) {
            rows = `
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">
                        Tidak ada data
                    </td>
                </tr>
            `;
        } else {
            list.forEach((row, i) => {
                rows += `
                    <tr>
                        <td class="text-center">${indexStart + i}</td>
                        <td>${row.ID_SALESMAN ?? '-'}</td>
                        <td class="text-left">${row.NAMA_SALESMAN ?? '-'}</td>
                    </tr>
                `;
            });
        }

        $("#tableSalesman").html(rows);

        // === Total Salesman ===
        $("#salesmanTotal").html(`
            <div class="mb-2 text-right">
                <p class="text-primary font-weight-bold" style="font-size:14px;">
                    Total Salesman: ${res.total_salesman ?? 0}
                </p>
            </div>
        `);

        // === Pagination ===
        $("#salesmanPagination").html(res.pagination);

        // === Pagination AJAX ===
        $("#salesmanPagination a.page-link").on("click", function (e) {
            e.preventDefault();
            showSalesman(idDistributor, $(this).attr("href"));
        });

    }).fail(function (xhr) {

        console.error("AJAX Error:", xhr.responseText);

        $("#tableSalesman").html(`
            <tr>
                <td colspan="3" class="text-center text-danger py-3">
                    Gagal memuat data
                </td>
            </tr>
        `);
    });
}
