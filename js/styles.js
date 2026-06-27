/*
 * styles.js
 * The site's design system now lives in real stylesheets that every page links
 * directly: css/base.css, css/layout.css, css/components.css.
 *
 * This module is kept only to (1) preserve the module-loading log and (2) make
 * sure the theme color tokens in config.js stay in sync with the CSS variables
 * if they are ever customised at runtime.
 */
(function () {
  "use strict";

  var t = (window.SITE && window.SITE.theme) || {};
  var root = document.documentElement;

  // Keep CSS custom properties aligned with config.js (optional override).
  var map = {
    "--primary": t.primary,
    "--primary-dark": t.primaryDark,
    "--accent": t.accent,
    "--warn": t.warn,
    "--danger": t.danger,
    "--ink": t.ink,
    "--muted": t.muted,
    "--line": t.line,
    "--bg": t.bg,
    "--bg-soft": t.bgSoft
  };
  Object.keys(map).forEach(function (key) {
    if (map[key]) root.style.setProperty(key, map[key]);
  });

  if (window.logModule) window.logModule("styles.js");
})();
