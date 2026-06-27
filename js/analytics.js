/*
 * analytics.js
 * Consent-aware analytics bootstrap. This is a privacy-friendly stub: it only
 * records a lightweight page-view event AFTER the visitor has accepted cookies.
 * Replace the `send()` body with your real analytics/ads tag when deploying.
 */
(function () {
  "use strict";

  var CONSENT_KEY = "sg_cookie_consent_v1";

  function hasConsent() {
    try {
      return localStorage.getItem(CONSENT_KEY) === "all";
    } catch (e) {
      return false;
    }
  }

  function send(eventName, payload) {
    if (!hasConsent()) return;
    // Placeholder: integrate Google Ads / GA4 / Plausible etc. here.
    if (window.console && console.debug) {
      console.debug("[analytics]", eventName, payload || {});
    }
  }

  window.SGAnalytics = { track: send };

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  ready(function () {
    send("page_view", { path: location.pathname, title: document.title });
  });

  if (window.logModule) window.logModule("analytics.js");
})();
