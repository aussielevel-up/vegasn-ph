/*
 * cookie-consent.js
 * Lightweight, GDPR-style cookie consent banner. Stores the choice in
 * localStorage so it is shown only once. Required for ad-network compliance.
 */
(function () {
  "use strict";

  var KEY = "sg_cookie_consent_v1";

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  ready(function () {
    var existing = null;
    try {
      existing = localStorage.getItem(KEY);
    } catch (e) {}
    if (existing) return;

    var bar = document.createElement("div");
    bar.className = "cookie";
    bar.setAttribute("role", "dialog");
    bar.setAttribute("aria-live", "polite");
    bar.innerHTML =
      "<p>We use cookies to keep the site reliable, understand traffic, and improve our " +
      'educational content. See our <a href="privacy.html">Privacy Policy</a> for details.</p>' +
      '<div class="row">' +
        '<button class="btn btn-primary" data-consent="all">Accept all</button>' +
        '<button class="btn btn-ghost" data-consent="essential">Essential only</button>' +
      "</div>";
    document.body.appendChild(bar);

    requestAnimationFrame(function () {
      bar.classList.add("show");
    });

    bar.addEventListener("click", function (e) {
      var choice = e.target && e.target.getAttribute("data-consent");
      if (!choice) return;
      try {
        localStorage.setItem(KEY, choice);
      } catch (err) {}
      bar.classList.remove("show");
      setTimeout(function () {
        bar.remove();
      }, 300);
    });
  });

  if (window.logModule) window.logModule("cookie-consent.js");
})();
