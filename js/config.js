/*
 * config.js
 * Global site configuration. Loaded first on every page.
 * Other scripts read from window.SITE to render the UI.
 */
(function () {
  "use strict";

  window.SITE = {
    name: "StackGuide",
    tagline: "Your Independent Guide to the Modern Web Stack",
    description:
      "StackGuide is an independent, educational resource that explains how popular web technologies and services work \u2014 from search and productivity tools to domains and content delivery networks.",
    email: "hello@stackguide.example",
    phone: "+1 (555) 014-2200",
    address: "2200 Knowledge Park, Suite 410, Austin, TX 78701, USA",
    foundedYear: 2019,
    socials: [
      { label: "X", url: "https://x.com" },
      { label: "LinkedIn", url: "https://www.linkedin.com" },
      { label: "YouTube", url: "https://www.youtube.com" }
    ],

    // Primary navigation used by header.js
    nav: [
      { label: "Home", href: "index.php" },
      { label: "Features", href: "features.html" },
      { label: "Domains", href: "domains.html" },
      { label: "Cloudflare", href: "cloudflare.html" },
      { label: "Reviews", href: "reviews.html" },
      { label: "Guides", href: "guides.html" },
      { label: "About", href: "about.html" },
      { label: "Contact", href: "contact.html" }
    ],

    // Footer link groups used by footer.js
    footerLinks: [
      {
        title: "Topics",
        links: [
          { label: "Google Features", href: "features.html" },
          { label: "Domains & DNS", href: "domains.html" },
          { label: "Cloudflare CDN", href: "cloudflare.html" },
          { label: "Reviews", href: "reviews.html" },
          { label: "How-To Guides", href: "guides.html" }
        ]
      },
      {
        title: "Company",
        links: [
          { label: "About Us", href: "about.html" },
          { label: "Contact", href: "contact.html" },
          { label: "Privacy Policy", href: "privacy.html" },
          { label: "Terms & Conditions", href: "terms.html" }
        ]
      },
      {
        title: "External Resources",
        links: [
          { label: "Google", href: "https://www.google.com", external: true },
          { label: "Squarespace Domains", href: "https://domains.squarespace.com", external: true },
          { label: "Cloudflare", href: "https://www.cloudflare.com", external: true },
          { label: "Google Workspace", href: "https://workspace.google.com", external: true }
        ]
      }
    ],

    // Brand color tokens consumed by styles.js
    theme: {
      primary: "#1a73e8",
      primaryDark: "#1558b0",
      accent: "#34a853",
      warn: "#fbbc05",
      danger: "#ea4335",
      ink: "#202124",
      muted: "#5f6368",
      line: "#e8eaed",
      bg: "#ffffff",
      bgSoft: "#f8f9fc"
    }
  };

  // Tiny logger so we can see in the console that every module loaded.
  window.SITE.__loaded = window.SITE.__loaded || [];
  window.logModule = function (name) {
    window.SITE.__loaded.push(name);
    if (window.console && console.debug) {
      console.debug("[StackGuide] module loaded:", name);
    }
  };

  window.logModule("config.js");
})();
