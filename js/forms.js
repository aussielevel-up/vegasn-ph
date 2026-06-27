/*
 * forms.js
 * Client-side validation + friendly success state for the contact and
 * newsletter forms. (Static site: submissions are validated and acknowledged
 * locally; wire to a backend / form service when deploying.)
 */
(function () {
  "use strict";

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  function isEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  ready(function () {
    document.querySelectorAll("form[data-form]").forEach(function (form) {
      var alert = form.querySelector(".alert");
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        var valid = true;

        form.querySelectorAll("[required]").forEach(function (field) {
          var ok = field.value.trim() !== "";
          if (field.type === "email") ok = ok && isEmail(field.value.trim());
          field.style.borderColor = ok ? "" : "var(--danger)";
          if (!ok) valid = false;
        });

        if (!valid) return;

        form.reset();
        if (alert) {
          alert.classList.add("show");
          alert.scrollIntoView({ behavior: "smooth", block: "center" });
          setTimeout(function () {
            alert.classList.remove("show");
          }, 6000);
        }
      });
    });
  });

  if (window.logModule) window.logModule("forms.js");
})();
