/*
 * footer.js
 * Renders the footer (link columns, disclaimer, socials) into
 * <div id="app-footer"></div>.
 */
(function () {
  "use strict";

  var S = window.SITE || {};
  var groups = S.footerLinks || [];
  var year = new Date().getFullYear();

  var columns = groups
    .map(function (g) {
      var links = g.links
        .map(function (l) {
          var ext = l.external ? ' target="_blank" rel="noopener nofollow"' : "";
          return '<a href="' + l.href + '"' + ext + ">" + l.label + "</a>";
        })
        .join("");
      return "<div><h4>" + g.title + "</h4>" + links + "</div>";
    })
    .join("");

  var socials = (S.socials || [])
    .map(function (s) {
      return '<a href="' + s.url + '" target="_blank" rel="noopener nofollow">' + s.label + "</a>";
    })
    .join("");

  var html =
    '<footer class="site-footer">' +
      '<div class="container">' +
        '<div class="footer-grid">' +
          '<div class="footer-brand">' +
            '<a class="brand" href="index.php"><span class="logo">S</span>' + (S.name || "StackGuide") + "</a>" +
            "<p style=\"margin-top:14px\">" + (S.description || "") + "</p>" +
          "</div>" +
          columns +
        "</div>" +
        '<p class="disclaimer">' +
          "Disclaimer: " + (S.name || "StackGuide") + " is an independent educational website. " +
          "We are not affiliated with, endorsed by, or sponsored by Google LLC, Squarespace Inc., " +
          "Cloudflare Inc., or any other company mentioned on this site. All product names, logos, " +
          "and brands are property of their respective owners and are referenced for informational and " +
          "comparison purposes only." +
        "</p>" +
        '<div class="footer-bottom">' +
          "<span>\u00A9 " + year + " " + (S.name || "StackGuide") + ". All rights reserved.</span>" +
          '<span style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center">' +
            '<a href="privacy.html">Privacy</a>' +
            '<a href="terms.html">Terms</a>' +
            '<a href="contact.html">Contact</a>' +
            '<span class="socials">' + socials + "</span>" +
          "</span>" +
        "</div>" +
      "</div>" +
    "</footer>";

  var mount = document.getElementById("app-footer");
  if (mount) mount.innerHTML = html;

  if (window.logModule) window.logModule("footer.js");
})();
