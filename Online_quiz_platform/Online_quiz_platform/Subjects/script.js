// Dark mode toggle
document.addEventListener("DOMContentLoaded", function () {
    let darkMode = localStorage.getItem("darkMode") === "enabled";
    updateTheme(darkMode);

    document.getElementById("toggleTheme").addEventListener("click", function () {
        darkMode = !darkMode;
        localStorage.setItem("darkMode", darkMode ? "enabled" : "disabled");
        updateTheme(darkMode);
    });

    function updateTheme(enableDarkMode) {
        if (enableDarkMode) {
            document.body.classList.add("dark-mode");
        } else {
            document.body.classList.remove("dark-mode");
        }
    }
});
