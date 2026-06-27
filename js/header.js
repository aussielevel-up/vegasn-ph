/*
 * header.js
 * Renders the sticky navigation bar into <div id="app-header"></div>.
 */
(function () {
  "use strict";

  var S = window.SITE || {};
  var nav = S.nav || [];
  var current = (location.pathname.split("/").pop() || "index.php").toLowerCase();
  if (current === "") current = "index.php";

  var links = nav
    .map(function (item) {
      var active = item.href.toLowerCase() === current ? " class=\"active\"" : "";
      return '<li><a href="' + item.href + '"' + active + ">" + item.label + "</a></li>";
    })
    .join("");

  var html =
    '<header class="site-header">' +
      '<div class="container nav">' +
        '<a class="brand" href="index.php">' +
          '<span class="logo">S</span>' + (S.name || "StackGuide") +
        "</a>" +
        '<ul class="nav-links" id="navLinks">' + links + "</ul>" +
        '<div class="nav-cta">' +
          '<a class="btn btn-primary" href="contact.html">Get in touch</a>' +
          '<button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">' +
            '<span class="material-symbols-rounded">menu</span>' +
          "</button>" +
        "</div>" +
      "</div>" +
    "</header>";

  var mount = document.getElementById("app-header");
  if (mount) mount.innerHTML = html;

  // Mobile menu toggle
  var toggle = document.getElementById("navToggle");
  var menu = document.getElementById("navLinks");
  if (toggle && menu) {
    toggle.addEventListener("click", function () {
      var open = menu.classList.toggle("open");
      toggle.setAttribute("aria-expanded", String(open));
      toggle.querySelector(".material-symbols-rounded").textContent = open ? "close" : "menu";
    });
  }

  if (window.logModule) window.logModule("header.js");
})();
