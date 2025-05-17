const dictionary = {
    "es": {
      "nav-news": "Más noticias",
      "nav-login": "Iniciar sesión",
      "nav-home": "Inicio",
      "nav-logout": "Cerrar sesión",
      "logout": "Cerrar sesion",
      "login-status": "No has iniciado sesion",
      "nav-backnews": "Volver a Noticias",
      "header-title":"Ultimas Noticias",
      "header-subtitle":"Manténgase informado de las últimas noticias sobre energía sostenible",
      "header-description": "Bienvenidos a EcoBlog, un espacio dedicado a promover la energía sostenible y el acceso a fuentes limpias de energía para todos. Aquí encontrarás información sobre energías renovables, eficiencia energética y consejos para reducir tu huella energética en el día a día.",
      "section-title-1": "¿Qué es el ODS 7?",
      "section-title-2": "Metas DEL ODS 7",
      "section-description-1": "La ODS 7 forma parte de los Objetivos de Desarrollo Sostenible de la ONU y busca garantizar el acceso universal a una energía asequible, fiable, sostenible y moderna. Algunos de sus principales objetivos incluyen:",
      "goal-1": "Garantizar el acceso universal a servicios energéticos asequibles.",
      "goal-2": "Aumentar la proporción de energías renovables en la matriz energética global.",
      "goal-3": "Duplicar la tasa de mejora de la eficiencia energética.",
      "recent-news-title": "LO MÁS RECIENTE",
      "author-label": "Autor:",
      "date-label": "Fecha:",
      "more-news-title": "MÁS NOTICIAS",
      "more-news-description": "¡¡Ponte al día con todas las noticias!!",
      "more-news-link": "Más noticias",
      "more-info": "Más información...",
      "leave-comment": "Deja un comentario",
      "post-comment": "Publica un comentario",
      "Inic": "Inicia sesion para dejar un comentario",
      "comments": "Comentarios",
      "no-comments": "No hay comentarios aún. ¡Sé el primero en comentar!",
      "comment-placeholder": "Escribe tu comentario aquí...",
      "sign-up-btn-text": "Registrate",

      // Añade todos los translate-id usados en tu HTML
    },
    "en": {
      "nav-news": "More news",
      "nav-login": "Log in",
      "nav-home": "Home",
      "nav-logout": "Log out",
      "logout": "Log out",
      "login-status": "You are not logged in",
      "nav-backnews": "Back to news",
      "header-title":"Last News",
      "header-subtitle":"Stay informed about the latest news on sustainable energy",
      "header-description": "Welcome to EcoBlog, a space dedicated to promoting sustainable energy and access to clean energy sources for everyone. Here you will find information on renewable energies, energy efficiency, and tips to reduce your energy footprint daily.",
      "section-title-1": "What is SDG 7?",
      "section-title-2": "SDG 7 TARGETS",
      "section-description-1": "SDG 7 is part of the United Nations Sustainable Development Goals and aims to ensure universal access to affordable, reliable, sustainable, and modern energy. Some of its main goals include:",
      "goal-1": "Ensure universal access to affordable energy services.",
      "goal-2": "Increase the share of renewable energy in the global energy mix.",
      "goal-3": "Double the rate of improvement in energy efficiency.",
      "recent-news-title": "LATEST",
      "author-label": "Author:",
      "date-label": "Date:",
      "more-news-title": "MORE NEWS",
      "more-news-description": "Catch up with all the news!!",
      "more-news-link": "More news",
      "more-info": "More info...",
      "leave-comment": "Leave a comment",
      "post-comment": "Post a comment",
      "inic": "Login to leave a comment",
      "comments": "Comments",
      "no-comments": "There are no comments yet, be the first to comment!",
      "comment-placeholder": "Write your comment here...",
      "sign-up-btn-text": "Sign up",
      
      // Añade traducción de todos los translate-id
    }
  };

  let currentLang = "es"; // Idioma por defecto

  function translatePage(lang) {
    const elements = document.querySelectorAll(".translatable");
    elements.forEach(el => {
      const key = el.getAttribute("data-translate-id");
      if (key && dictionary[lang] && dictionary[lang][key]) {
        el.innerHTML = dictionary[lang][key];
      }
    });
    currentLang = lang; // Actualizamos el idioma actual
  }
  
  function traducirDinamicos(lang) {
  const dinamicos = document.querySelectorAll(".dynamic-deepl");
  const items = [];

  dinamicos.forEach(el => {
    // Verifica si tiene texto visible
    const text = el.innerText.trim();
    if (text) {
      items.push({ el, type: "text", value: text });
    }

    // Verifica atributos como placeholder, title, value
    ["placeholder", "title", "value", "alt", "aria-label"].forEach(attr => {
      if (el.hasAttribute(attr)) {
        const val = el.getAttribute(attr).trim();
        if (val) {
          items.push({ el, type: attr, value: val });
        }
      }
    });
  });

  if (items.length === 0) return;

  const textos = items.map(item => item.value);

  fetch("servidorprueba.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      text: textos,
      target_lang: lang.toUpperCase()
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.translations) {
        data.translations.forEach((t, i) => {
          const item = items[i];
          if (item.type === "text") {
            item.el.innerText = t.text;
          } else {
            item.el.setAttribute(item.type, t.text);
          }
        });
      }
    })
    .catch(err => console.error("Error al traducir dinámico:", err));
}
  
  // Observador que detecta cuando se agregan nodos al DOM y traduce textos locales
  const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      mutation.addedNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE) {
          // Traducción local para textos estáticos añadidos dinámicamente
          const dynamicTexts = node.querySelectorAll('.translatable');
          dynamicTexts.forEach(el => {
            const key = el.getAttribute('data-translate-id');
            if (key && dictionary[currentLang] && dictionary[currentLang][key]) {
              el.innerHTML = dictionary[currentLang][key];
            }
          });
  
          // Traducción DeepL para textos dinámicos añadidos
          const deeplTexts = node.querySelectorAll('.dynamic-deepl');
          if(deeplTexts.length > 0) {
            traducirDinamicos(currentLang);
          }
        }
      });
    });
  });
  
  // Activar el observador para todo el documento
  observer.observe(document.body, { childList: true, subtree: true });
  
  document.addEventListener("DOMContentLoaded", () => {
    const storedLang = localStorage.getItem("lang") || "es";
    let currentLang = storedLang;

    // Función para aplicar el idioma actual
    translatePage(currentLang);
    setTimeout(() => traducirDinamicos(currentLang), 200);

    // Marcar botón seleccionado
    const updateSelectedLanguageButton = (lang) => {
      document.querySelectorAll(".language-option").forEach(btn => {
        btn.classList.remove("selected");
        if (btn.dataset.lang === lang) {
          btn.classList.add("selected");
        }
      });
    };

    // Inicializa el estado visual de los botones
    updateSelectedLanguageButton(currentLang);

    // Escuchar clics en los botones de idioma
    document.querySelectorAll(".language-option").forEach(button => {
      button.addEventListener("click", () => {
        const newLang = button.dataset.lang;
        localStorage.setItem("lang", newLang);
        currentLang = newLang;
        translatePage(newLang);
        traducirDinamicos(newLang);
        updateSelectedLanguageButton(newLang);
      });
    });
  });

  
  

  