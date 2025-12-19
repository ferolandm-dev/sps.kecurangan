/* ============================================================
   VIEW SELECTOR (NAVBAR STYLE)
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {

    const viewButtons = document.querySelectorAll(".view-btn");

    const sections = {
        overview: document.getElementById("section-overview"),
        summary: document.getElementById("section-summary"),
        tables: document.getElementById("section-tables"),
        calendar: document.getElementById("section-calendar"),
    };

    function showSection(key) {
        Object.keys(sections).forEach(name => {
            sections[name].style.display = (name === key ? "block" : "none");
        });
    }

    // Toggle view button active state
    viewButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            viewButtons.forEach(b => {
                b.classList.remove("active");
                b.setAttribute("aria-selected", "false");
            });

            btn.classList.add("active");
            btn.setAttribute("aria-selected", "true");

            const view = btn.dataset.view;
            showSection(view);

            // Render heatmap only when calendar activated
            if (view === "calendar") {
                setTimeout(renderHeatmap, 150);
            }
        });
    });

    /* ============================================================
       RANDOM DEFAULT VIEW
       ============================================================ */
    const randomView = ["overview", "summary", "tables", "calendar"]
    [Math.floor(Math.random() * 4)];

    showSection(randomView);

    viewButtons.forEach(b => {
        const active = b.dataset.view === randomView;
        b.classList.toggle("active", active);
        b.setAttribute("aria-selected", active ? "true" : "false");
    });

    if (randomView === "calendar") {
        setTimeout(() => {
            if (typeof renderHeatmap === "function") renderHeatmap();
        }, 200);
    }

});

/* ============================================================
   ANIMASI SCROLL (FADE-UP)
   ============================================================ */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add("in-view");
            obs.unobserve(e.target);
        }
    });
}, {
    threshold: 0.15
});

document.querySelectorAll("[data-animate]").forEach(el => obs.observe(el));

/* ============================================================
   CARD TILT EFFECT
   ============================================================ */
document.querySelectorAll(".card-tilt").forEach(card => {
    const inner = card.querySelector(".tilt-inner");

    card.addEventListener("mousemove", e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        inner.style.transform =
            `rotateX(${(y * -8).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
    });

    card.addEventListener("mouseleave", () => {
        inner.style.transform = "none";
    });
});
