// Update jam WIB
function updateWIBClock() {
    const now = new Date();

    // Konversi zona waktu ke WIB
    const wibTime = new Date(
        now.toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        })
    );

    const h = String(wibTime.getHours()).padStart(2, '0');
    const m = String(wibTime.getMinutes()).padStart(2, '0');
    const s = String(wibTime.getSeconds()).padStart(2, '0');

    document.getElementById("wibClock").textContent = `${h}:${m}:${s} WIB`;
}

// Render awal + interval tiap detik
updateWIBClock();
setInterval(updateWIBClock, 1000);

// Update greeting
document.addEventListener("DOMContentLoaded", function() {

    function setGreeting() {
        const now = new Date();

        // Ambil waktu WIB
        const wib = new Date(
            now.toLocaleString("en-US", {
                timeZone: "Asia/Jakarta"
            })
        );
        const hour = wib.getHours();

        let greeting = "";
        let mood = "";

        // Penentuan waktu salam
        if (hour >= 5 && hour < 11) {
            greeting = "PAGI";
            mood = "Semangat memulai hari!";
        } else if (hour >= 11 && hour < 15) {
            greeting = "SIANG";
            mood = "Jaga fokus, tetap produktif!";
        } else if (hour >= 15 && hour < 18) {
            greeting = "SORE";
            mood = "Sedikit lagi menuju selesai.";
        } else {
            greeting = "MALAM";
            mood = "Terima kasih atas kerja keras hari ini.";
        }

        // Update teks greeting
        const targetGreeting = document.getElementById("greetingText");
        if (targetGreeting) targetGreeting.textContent = greeting;

        // Update mood/kesan
        const targetMood = document.getElementById("greetingMood");
        if (targetMood) targetMood.textContent = mood;
    }

    // Render pertama
    setGreeting();

    // Update setiap 30 menit
    setInterval(setGreeting, 30 * 60 * 1000);
});

// Update tanggal
function updateWIBDate() {
    const now = new Date();

    // Mengubah waktu JS ke WIB
    const wib = new Date(
        now.toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        })
    );

    const hari = [
        "Minggu", "Senin", "Selasa", "Rabu",
        "Kamis", "Jumat", "Sabtu"
    ];

    const bulan = [
        "Januari", "Februari", "Maret", "April",
        "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"
    ];

    const text =
        `${hari[wib.getDay()]}, ${wib.getDate()} ${bulan[wib.getMonth()]} ${wib.getFullYear()}`;

    document.getElementById("wibDate").textContent = text;
}

// Render pertama + update tiap jam
updateWIBDate();
setInterval(updateWIBDate, 3600000);