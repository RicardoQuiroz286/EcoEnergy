document.addEventListener("DOMContentLoaded", function () {
    const languageSelector = document.getElementById("language-select");

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
    fetch("textInicioSesion.json")
        .then(response => response.json())
        .then(data => {
            if (data[language]) {
                // Recorremos las claves del idioma seleccionado
                Object.keys(data[language]).forEach(id => {
                    let element = document.getElementById(id);
                    if (element) {
                        if (element.tagName === "INPUT") {
                            // Para los campos de texto (inputs), actualizamos el placeholder
                            element.setAttribute("placeholder", data[language][id]);
                        } else if (element.tagName === "BUTTON" || element.tagName === "A") {
                            // Para los botones o enlaces, usamos textContent
                            element.textContent = data[language][id];
                        } else if (id === "recordar-text") {
                            // Para el texto de recordar, actualizamos textContent específicamente
                            element.textContent = data[language]["recordar"];
                        } else {
                            // Para otros elementos, usamos innerHTML
                            element.innerHTML = data[language][id];
                        }
                    }
                });
            }
        })
        .catch(error => console.error("Error loading translation:", error));
}
