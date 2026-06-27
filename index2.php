<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>StackGuide — Your Independent Guide to the Modern Web Stack</title>
  <meta name="description"
    content="StackGuide is an independent, educational resource explaining how popular web technologies work — Google services, domains, Cloudflare CDN, and more — in plain English." />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://stackguide.example/index.php" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="StackGuide — Your Independent Guide to the Modern Web Stack" />
  <meta property="og:description"
    content="Plain-English guides to Google services, domains, Cloudflare, and the technology that runs the modern web." />
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/layout.css" />
  <link rel="stylesheet" href="css/components.css" />
  <link rel="icon"
    href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a73e8'/%3E%3Ctext x='16' y='22' font-family='Arial' font-size='18' font-weight='bold' fill='white' text-anchor='middle'%3ES%3C/text%3E%3C/svg%3E" />
</head>

<body>

  <div id="app-header"></div>

  <main>
    <!-- HERO -->
    <section class="hero">
      <div class="container hero-grid">
        <div class="reveal">
          <span class="eyebrow"><span class="material-symbols-rounded" style="font-size:1rem">verified</span>
            Independent &amp; ad-free editorial</span>
          <h1>Understand the technology that powers the web.</h1>
          <p class="lead">StackGuide breaks down the tools millions rely on every day — Google's ecosystem, domain
            registration, and content delivery networks — into clear, jargon-free explanations anyone can follow.</p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="features.html">Explore Google features <span
                class="material-symbols-rounded">arrow_forward</span></a>
            <a class="btn btn-ghost" href="guides.html">Read the guides</a>
          </div>
          <div class="pill-row" style="margin-top:1.6rem">
            <span class="pill">Search</span>
            <span class="pill">Workspace</span>
            <span class="pill">Domains</span>
            <span class="pill">Cloudflare CDN</span>
            <span class="pill">Security</span>
          </div>
        </div>
        <div class="reveal">
          <div class="hero-card">
            <div class="row"><span class="ic"><span class="material-symbols-rounded">search</span></span>
              <div><strong>Google Search</strong>
                <div class="muted" style="font-size:.9rem">How indexing &amp; ranking really work</div>
              </div>
            </div>
            <div class="row"><span class="ic"><span class="material-symbols-rounded">language</span></span>
              <div><strong>Domains</strong>
                <div class="muted" style="font-size:.9rem">Registering &amp; managing a domain name</div>
              </div>
            </div>
            <div class="row"><span class="ic"><span class="material-symbols-rounded">bolt</span></span>
              <div><strong>Cloudflare</strong>
                <div class="muted" style="font-size:.9rem">CDN, caching &amp; DDoS protection explained</div>
              </div>
            </div>
            <div class="row"><span class="ic"><span class="material-symbols-rounded">workspace_premium</span></span>
              <div><strong>Workspace</strong>
                <div class="muted" style="font-size:.9rem">Gmail, Docs, Drive &amp; collaboration</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TRUST STRIP -->
    <section class="section-tight">
      <div class="container">
        <p class="center muted" style="margin-bottom:1.2rem">Topics we cover in depth</p>
        <div class="logos">
          <span class="chip"><span class="material-symbols-rounded">search</span> Google Search</span>
          <span class="chip"><span class="material-symbols-rounded">mail</span> Gmail &amp; Workspace</span>
          <span class="chip"><span class="material-symbols-rounded">cloud</span> Cloud &amp; Drive</span>
          <span class="chip"><span class="material-symbols-rounded">language</span> Domains</span>
          <span class="chip"><span class="material-symbols-rounded">shield</span> Cloudflare</span>
          <span class="chip"><span class="material-symbols-rounded">map</span> Google Maps</span>
        </div>
      </div>
    </section>

    <!-- VALUE CARDS -->
    <section style="padding-top:24px">
      <div class="container">
        <div class="center" style="max-width:680px;margin:0 auto 40px">
          <span class="eyebrow-2">Why StackGuide</span>
          <h2>Clear answers, no marketing fluff</h2>
          <p class="muted">We research, test, and explain — so you can make informed decisions about the services you
            use online.</p>
        </div>
        <div class="grid grid-3">
          <div class="card reveal">
            <div class="ic"><span class="material-symbols-rounded">menu_book</span></div>
            <h3>Plain-English explainers</h3>
            <p>Complex topics like DNS, CDNs, and indexing turned into guides you can actually understand.</p>
          </div>
          <div class="card reveal">
            <div class="ic"><span class="material-symbols-rounded">balance</span></div>
            <h3>Independent &amp; honest</h3>
            <p>We're not owned by any vendor. We compare options on the merits and tell you the trade-offs.</p>
          </div>
          <div class="card reveal">
            <div class="ic"><span class="material-symbols-rounded">update</span></div>
            <h3>Kept up to date</h3>
            <p>The web changes fast. We revisit our guides regularly so the information stays accurate.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURE SPLIT: GOOGLE -->
    <section style="background:var(--bg-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
      <div class="container split">
        <div class="reveal">
          <span class="eyebrow-2">Spotlight</span>
          <h2>The Google ecosystem, explained</h2>
          <p class="muted">From the search engine that answers billions of queries a day to the productivity suite teams
            run their work on, Google's products are woven into daily life. We unpack what each one does and when it
            makes sense to use it.</p>
          <ul class="checklist">
            <li><span class="material-symbols-rounded">check_circle</span> How Google Search crawls, indexes, and ranks
              pages</li>
            <li><span class="material-symbols-rounded">check_circle</span> Gmail, Docs, Drive &amp; Meet inside Google
              Workspace</li>
            <li><span class="material-symbols-rounded">check_circle</span> Maps, Photos, Chrome, Android and more</li>
            <li><span class="material-symbols-rounded">check_circle</span> Privacy controls and account security</li>
          </ul>
          <a class="btn btn-primary" href="features.html" style="margin-top:1rem">See all Google features <span
              class="material-symbols-rounded">arrow_forward</span></a>
        </div>
        <div class="reveal">
          <div class="grid grid-2">
            <div class="card">
              <div class="ic"><span class="material-symbols-rounded">search</span></div>
              <h3>Search</h3>
              <p>The world's most used search engine.</p>
            </div>
            <div class="card">
              <div class="ic"><span class="material-symbols-rounded">mail</span></div>
              <h3>Gmail</h3>
              <p>Fast, secure email for 1.8B+ users.</p>
            </div>
            <div class="card">
              <div class="ic"><span class="material-symbols-rounded">map</span></div>
              <h3>Maps</h3>
              <p>Navigation &amp; local discovery.</p>
            </div>
            <div class="card">
              <div class="ic"><span class="material-symbols-rounded">cloud</span></div>
              <h3>Drive</h3>
              <p>Cloud storage &amp; sharing.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <section>
      <div class="container">
        <div class="stats">
          <div class="stat reveal">
            <div class="num">40+</div>
            <div class="lbl">Topics covered</div>
          </div>
          <div class="stat reveal">
            <div class="num">120+</div>
            <div class="lbl">In-depth articles</div>
          </div>
          <div class="stat reveal">
            <div class="num">9</div>
            <div class="lbl">Categories</div>
          </div>
          <div class="stat reveal">
            <div class="num">2019</div>
            <div class="lbl">Publishing since</div>
          </div>
        </div>
      </div>
    </section>

    <!-- TWO COLUMN: DOMAINS + CLOUDFLARE -->
    <section style="padding-top:0">
      <div class="container grid grid-2">
        <div class="card reveal" style="padding:30px">
          <span class="eyebrow-2">Domains</span>
          <h3 style="font-size:1.5rem">Registering a domain, the right way</h3>
          <p class="muted">What a domain actually is, how registrars like Squarespace Domains work, and how DNS connects
            your name to your website.</p>
          <a class="more" href="domains.html">Read the domains guide <span
              class="material-symbols-rounded">arrow_forward</span></a>
        </div>
        <div class="card reveal" style="padding:30px">
          <span class="eyebrow-2">Performance &amp; Security</span>
          <h3 style="font-size:1.5rem">How Cloudflare protects &amp; speeds up sites</h3>
          <p class="muted">CDN caching, DDoS mitigation, DNS, and SSL — why so many websites sit behind Cloudflare and
            how it works.</p>
          <a class="more" href="cloudflare.html">Read the Cloudflare guide <span
              class="material-symbols-rounded">arrow_forward</span></a>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section>
      <div class="container">
        <div class="cta-band reveal">
          <h2>Start with the fundamentals</h2>
          <p>Whether you're launching your first website or just curious how the web works, our guides give you a solid,
            vendor-neutral foundation.</p>
          <a class="btn btn-light" href="guides.html">Browse all guides <span
              class="material-symbols-rounded">arrow_forward</span></a>
        </div>
      </div>
    </section>
  </main>

  <div id="app-footer"></div>

  <!-- Site is assembled from modular scripts: config → fonts → styles → UI modules -->
  <script src="js/config.js"></script>
  <script src="js/fonts.js"></script>
  <script src="js/styles.js"></script>
  <script src="js/components.js"></script>
  <script src="js/header.js"></script>
  <script src="js/footer.js"></script>
  <script src="js/animations.js"></script>
  <script src="js/forms.js"></script>
  <script src="js/cookie-consent.js"></script>
  <script src="js/analytics.js"></script>
</body>

</html>