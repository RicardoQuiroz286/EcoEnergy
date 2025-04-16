document.addEventListener("DOMContentLoaded", function () {
    // Animación de cambio de formulario
    const sign_in_btn = document.querySelector('#sign-in-btn');
    const sign_up_btn = document.querySelector('#sign-up-btn');
    const container = document.querySelector('.container');

    sign_up_btn.addEventListener("click", () => {
        container.classList.add('sign-up-mode');
    });

    sign_in_btn.addEventListener('click', () => {
        container.classList.remove('sign-up-mode');
    });

    // Validación del inicio de sesión
    function validateLogin(event) {
        event.preventDefault(); // Evita el envío del formulario sin validación

        const password = document.getElementById("password").value;
        const username = document.getElementById("username").value;

        if (username.trim() === "" || password.trim() === "") {
            alert("Por favor, completa todos los campos.");
            return;
        }

        if (password.length < 8) {
            alert("La contraseña debe tener al menos 8 caracteres.");
            return;
        }

        alert("Inicio de sesión exitoso.");
        document.querySelector(".sign-in-form").submit();
    }

    // Validación de contraseña en registro
    window.validateRegister = function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        const password = document.getElementById("register-password").value;
        const confirmPassword = document.getElementById("confirm-password").value;

        if (password.length < 8) {
            alert("La contraseña debe tener al menos 8 caracteres.");
            return false;
        }

        if (password !== confirmPassword) {
            alert("Las contraseñas no coinciden. Inténtalo de nuevo.");
            return false;
        }

        alert("Registro exitoso.");
        return true; // Envía el formulario si la validación es correcta
    };

    // Mostrar contraseña con el último carácter visible
    const campoContraseña = document.getElementById("password");

    campoContraseña.addEventListener("input", () => {
        const valor = campoContraseña.value;
        if (valor.length > 0) {
            const enmascarado = "*".repeat(valor.length - 1);
            campoContraseña.setAttribute("data-last-char", valor.charAt(valor.length - 1));
            campoContraseña.value = enmascarado + valor.charAt(valor.length - 1);
        }
    });
});
