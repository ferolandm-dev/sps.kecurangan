/* sidebar-fix.js (replace existing) */
document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');

    // lebih kebal terhadap struktur: cari tombol sidebar kiri di dalam navbar
    const sidebarToggle = document.querySelector('.navbar-toggle .navbar-toggler, .sidebar-toggle-left .navbar-toggler');

    // tombol collapse (Bootstrap 4 or 5)
    const collapseToggle = document.querySelector('[data-target="#navigation"], [data-bs-target="#navigation"]');

    // debug helper (hapus/komentari jika sudah ok)
    function log(...args) {
        if (window.location.search.includes('debugsidebar')) {
            console.debug('[sidebar-fix]', ...args);
        }
    }

    log('init', { sidebarExists: !!sidebar, sidebarToggle: !!sidebarToggle, collapseToggle: !!collapseToggle });

    // 1) Toggle kiri -> buka/tutup sidebar hanya di mobile
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (e) {
            if (window.innerWidth >= 992) return;
            e.stopPropagation(); // aman: mencegah click ini dianggap klik luar
            body.classList.toggle('nav-open');
            log('sidebarToggle clicked, nav-open:', body.classList.contains('nav-open'));
        });
    } else {
        log('sidebarToggle not found - selector may not match DOM');
    }

    // 2) Jangan stopPropagation pada tombol collapse — biarkan Bootstrap handle
    // 3) Dropdown: jangan stopPropagation (Bootstrap needs event bubbling)

    // 4) Click di luar sidebar -> tutup sidebar (kecuali klik pada tombol collapse atau tombol sidebar)
    document.addEventListener('click', function (e) {
        if (window.innerWidth >= 992) return;
        if (!body.classList.contains('nav-open')) return;

        const insideSidebar = sidebar && sidebar.contains(e.target);
        const clickedSidebarButton = e.target.closest('.navbar-toggle .navbar-toggler, .sidebar-toggle-left .navbar-toggler');

        const clickedCollapseButton = e.target.closest('[data-target="#navigation"], [data-bs-target="#navigation"], [data-toggle="collapse"][href="#navigation"]');

        // jika klik tombol collapse -> jangan tutup sidebar
        if (clickedCollapseButton) {
            log('clicked collapse button - ignore closing sidebar');
            return;
        }

        // jika bukan di sidebar dan bukan tombol sidebar kiri -> tutup sidebar
        if (!insideSidebar && !clickedSidebarButton) {
            body.classList.remove('nav-open');
            log('clicked outside -> nav-open removed');
        }
    });

    // 5) Resize -> reset sidebar
    window.addEventListener("resize", function () {
        if (window.innerWidth >= 992) {
            body.classList.remove("nav-open");
        }
    });

    // small safeguard: if overlay exists, ensure it doesn't block sidebar toggle
    const observer = new MutationObserver(() => {
        const closeLayer = document.querySelector('.close-layer');
        if (closeLayer) {
            // close-layer should be under navbar-collapse but above page; allow clicks through if needed
            closeLayer.style.zIndex = closeLayer.style.zIndex || '1010';
            closeLayer.style.pointerEvents = 'auto';
            log('close-layer adjusted', closeLayer.style.zIndex);
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
