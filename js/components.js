/*
 * components.js
 * Small helpers shared across pages: FAQ accordion behaviour, smooth anchor
 * scrolling, current-year stamping, and external-link safety hardening.
 */
(function () {
  "use strict";

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  ready(function () {
    // Stamp any element with [data-year] with the current year.
    document.querySelectorAll("[data-year]").forEach(function (el) {
      el.textContent = String(new Date().getFullYear());
    });

    // Make every external link safe (noopener) even if authored without it.
    document.querySelectorAll('a[target="_blank"]').forEach(function (a) {
      var rel = a.getAttribute("rel") || "";
      if (rel.indexOf("noopener") === -1) {
        a.setAttribute("rel", (rel + " noopener").trim());
      }
    });

    // Smooth-scroll for in-page anchor links.
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
      a.addEventListener("click", function (e) {
        var id = a.getAttribute("href");
        if (id.length > 1) {
          var target = document.querySelector(id);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: "smooth", block: "start" });
          }
        }
      });
    });
  });

  if (window.logModule) window.logModule("components.js");
})();
