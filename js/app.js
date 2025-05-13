document.addEventListener("DOMContentLoaded", function () {
  const sign_in_btn = document.querySelector('#sign-in-btn');
  const sign_up_btn = document.querySelector('#sign-up-btn');
  const container = document.querySelector('.container');

  sign_up_btn.addEventListener("click", () => {
    container.classList.add('sign-up-mode');
  });

  sign_in_btn.addEventListener("click", () => {
    container.classList.remove('sign-up-mode');
  });

  // Validación del formulario de inicio de sesión
  const loginForm = document.querySelector(".sign-in-form");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      const email = document.getElementById("login-email").value.trim();
      const password = document.getElementById("login-password").value.trim();

      if (email === "" || password === "") {
        event.preventDefault();
        alert("Por favor, completa todos los campos.");
        return;
      }

      if (password.length < 8) {
        event.preventDefault();
        alert("La contraseña debe tener al menos 8 caracteres.");
        return;
      }
    });
  }

  // Validación del formulario de registro
  const registerForm = document.querySelector(".sign-up-form");
  if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
      const email = document.getElementById("register-email").value.trim();
      const password = document.getElementById("register-password").value;
      const confirmPassword = document.getElementById("confirm-password").value;

      if (email === "" || password === "" || confirmPassword === "") {
        event.preventDefault();
        alert("Por favor, completa todos los campos.");
        return;
      }

      if (password.length < 8) {
        event.preventDefault();
        alert("La contraseña debe tener al menos 8 caracteres.");
        return;
      }

      if (password !== confirmPassword) {
        event.preventDefault();
        alert("Las contraseñas no coinciden.");
        return;
      }
    });
  }

  // Mostrar/Ocultar contraseñas
  const togglePassword = (toggleId, inputId) => {
    const toggleIcon = document.getElementById(toggleId);
    const inputField = document.getElementById(inputId);

    if (toggleIcon && inputField) {
      toggleIcon.addEventListener("click", function () {
        const isPassword = inputField.type === "password";
        inputField.type = isPassword ? "text" : "password";
        this.classList.toggle("bx-show", !isPassword);
        this.classList.toggle("bx-hide", isPassword);
      });
    }
  };

  togglePassword("toggle-login-password", "login-password");
  togglePassword("toggle-register-password", "register-password");
  togglePassword("toggle-confirm-password", "confirm-password");
});
