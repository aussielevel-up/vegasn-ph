/*
 * fonts.js
 * Injects the web fonts used across the whole site.
 * Fonts are loaded here (via script) rather than hard-coded in HTML so the
 * typography stays consistent on every page.
 */
(function () {
  "use strict";

  function addLink(attrs) {
    var link = document.createElement("link");
    Object.keys(attrs).forEach(function (k) {
      link.setAttribute(k, attrs[k]);
    });
    document.head.appendChild(link);
  }

  // Preconnect for faster font delivery.
  addLink({ rel: "preconnect", href: "https://fonts.googleapis.com" });
  addLink({ rel: "preconnect", href: "https://fonts.gstatic.com", crossorigin: "" });

  // Primary UI font (Inter) + display font (Sora) + mono (JetBrains Mono).
  addLink({
    rel: "stylesheet",
    href:
      "https://fonts.googleapis.com/css2?" +
      "family=Inter:wght@400;500;600;700&" +
      "family=Sora:wght@500;600;700;800&" +
      "family=JetBrains+Mono:wght@400;500&display=swap"
  });

  // Material Symbols for inline icons.
  addLink({
    rel: "stylesheet",
    href:
      "https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
  });

  if (window.logModule) window.logModule("fonts.js");
})();
