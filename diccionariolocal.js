const dictionary = {
    "es": {
      "nav-news": "Más noticias",
      "nav-login": "Iniciar sesión",
      "nav-home": "Inicio",
      "nav-logout": "Cerrar sesión",
      "nav-backnews": "Volver a Noticias",
      "header-title":"Ultimas Noticias",
      "header-subtitle":"Manténgase informado de las últimas noticias sobre energía sostenible",
      "header-description": "Bienvenidos a EcoBlog, un espacio dedicado a promover la energía sostenible y el acceso a fuentes limpias de energía para todos. Aquí encontrarás información sobre energías renovables, eficiencia energética y consejos para reducir tu huella energética en el día a día.",
      "section-title-1": "¿Qué es el ODS 7?",
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

      // Añade todos los translate-id usados en tu HTML
    },
    "en": {
      "nav-news": "More news",
      "nav-login": "Log in",
      "nav-home": "Home",
      "nav-logout": "Log out",
      "nav-backnews": "Back to news",
      "header-title":"Last News",
      "header-subtitle":"Stay informed about the latest news on sustainable energy",
      "header-description": "Welcome to EcoBlog, a space dedicated to promoting sustainable energy and access to clean energy sources for everyone. Here you will find information on renewable energies, energy efficiency, and tips to reduce your energy footprint daily.",
      "section-title-1": "What is SDG 7?",
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
    const textos = Array.from(dinamicos).map(el => el.innerText.trim());

     // 🔍 DEBUG
    console.log("Traduciendo dinámicos:", textos);
  
    if (textos.length === 0) return;

    console.log("Tipo de 'textos':", typeof textos);
    console.log("¿Es array?", Array.isArray(textos));
    console.log("Contenido:", textos);

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
         // ✅ Agrega esta línea para ver la respuesta del servidor
        console.log("Respuesta DeepL:", data);
      if (data.translations) {
        data.translations.forEach((t, i) => {
          dinamicos[i].innerText = t.text;
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
  
  // Ejecutar traducción al cargar la página y establecer el selector
  document.addEventListener("DOMContentLoaded", () => {
    const storedLang = localStorage.getItem("lang") || "es";
    currentLang = storedLang;
    translatePage(storedLang);
    setTimeout(() => traducirDinamicos(storedLang), 200);
  
    const langSelect = document.getElementById("language-selector"); // asegúrate del id
    if (langSelect) {
      langSelect.value = storedLang;
      langSelect.addEventListener("change", e => {
        const newLang = e.target.value;
        localStorage.setItem("lang", newLang);
        translatePage(newLang);
        traducirDinamicos(newLang);
      });
    }
  });

  
  

  