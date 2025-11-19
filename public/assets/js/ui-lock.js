/* =====================================================
   ZERO-LAG iPhone Viewport Height Fix
   TANPA DELAY, TANPA BOUNCE, TANPA KEDIP
===================================================== */

function applyViewportFix() {
    const vh = window.innerHeight * 0.01;

    // set langsung tanpa menunggu event -> no delay
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

// apply sesegera mungkin
applyViewportFix();

// event modern (lebih stabil)
window.addEventListener('visibilitychange', applyViewportFix);

// iPhone orientation (langsung, tanpa debounce)
window.addEventListener('orientationchange', applyViewportFix);

// avoid Safari resize spam
let resizeTimeout;
window.addEventListener('resize', () => {
    if (resizeTimeout) cancelAnimationFrame(resizeTimeout);
    resizeTimeout = requestAnimationFrame(applyViewportFix);
});
