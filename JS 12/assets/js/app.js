// Rental Motor Lowokwaru Malang — App JavaScript
document.addEventListener("DOMContentLoaded", function () {
    // 1. Mobile navigation toggle
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const navMenu = document.getElementById("main-nav");

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener("click", function () {
            navMenu.classList.toggle("nav-open");
        });
    }

    // 2. Real-time table search filter (client-side fallback)
    const searchInput = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");

    if (searchInput && table) {
        searchInput.addEventListener("keyup", function () {
            const query = searchInput.value.toLowerCase();
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }
});
