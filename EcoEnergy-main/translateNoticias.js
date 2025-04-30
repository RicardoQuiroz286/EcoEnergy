document.addEventListener("DOMContentLoaded", function () {
    const languageSelector = document.getElementById("language-selector");

    // Cargar el idioma guardado en localStorage si existe
    const savedLanguage = localStorage.getItem("language") || "es";
    languageSelector.value = savedLanguage;
    loadLanguage(savedLanguage);

    languageSelector.addEventListener("change", function () {
        const selectedLanguage = this.value;
        localStorage.setItem("language", selectedLanguage);
        loadLanguage(selectedLanguage);
    });
});

function loadLanguage(language) {
    fetch("textNoticias.json")
        .then(response => response.json())
        .then(data => {
            if (data[language]) {
                Object.keys(data[language]).forEach(id => {
                    let element = document.getElementById(id);
                    if (element) {
                        element.innerHTML = data[language][id];
                    }
                });
            }
        })
        .catch(error => console.error("Error loading translation:", error));
}
