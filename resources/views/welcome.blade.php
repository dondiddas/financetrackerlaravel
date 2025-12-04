<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinanceTracker — Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f7f9;
            --card: #ffffff;
            --accent: #28a745;
            --muted: #6c757d;
            --shadow: rgba(12, 18, 26, 0.08);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #0b1720;
            background: var(--bg);
            margin: 0;
        }

        /* Header */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1100;
            transition: background-color 0.25s, box-shadow 0.25s;
        }

        .site-header .navbar {
            padding: 0.5rem 0;
        }

        .site-header .brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .site-header.transparent {
            background: transparent;
        }

        .site-header.scrolled {
            background: #fff;
            box-shadow: 0 6px 20px var(--shadow);
            border-bottom: 1px solid rgba(16, 24, 32, 0.06);
        }

        /* Hero */
        #hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
            padding: 3rem 1rem;
        }

        .hero-inner {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: center;
        }

        @media(min-width:992px) {
            .hero-inner {
                grid-template-columns: 1fr 500px;
            }
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        @media(min-width:768px) {
            .hero-title {
                font-size: 2.75rem;
            }
        }

        .hero-sub {
            color: var(--muted);
            font-size: 1.05rem;
            margin-bottom: 1.5rem;
        }

        .cta-group .btn {
            border-radius: .75rem;
            padding: .65rem 1.25rem;
            font-weight: 600;
        }

        /* Mock Dashboard */
        .mock-card {
            background: var(--card);
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 12px 30px var(--shadow);
            border: 1px solid rgba(34, 56, 84, 0.04);
        }

        .mock-chart {
            height: 180px;
            border-radius: 0.75rem;
            background: linear-gradient(90deg, rgba(40, 167, 69, 0.08), rgba(40, 167, 69, 0.02));
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            color: rgba(40, 167, 69, 0.6);
        }

        /* Features */
        #features {
            padding: 4rem 1rem;
        }

        .features-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media(min-width:768px) {
            .features-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .feature-card {
            background: var(--card);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid rgba(11, 23, 32, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px var(--shadow);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            background: rgba(40, 167, 69, 0.12);
            color: var(--accent);
            font-size: 1.5rem;
            margin: auto 0 1rem 0;
        }

        /* Insights */
        #insights {
            padding: 4rem 1rem;
            background: linear-gradient(180deg, rgba(40, 167, 69, 0.03), transparent);
        }

        .insights-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: center;
        }

        @media(min-width:992px) {
            .insights-grid {
                grid-template-columns: 1fr 420px;
            }
        }

        .insight-chart {
            background: var(--card);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 12px 30px var(--shadow);
        }

        /* Testimonial */
        .testimonial {
            background: #0b1720;
            color: #fff;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(11, 23, 32, 0.12);
            font-size: 0.9rem;
        }

        /* Footer */
        footer.site-footer {
            background: #071018;
            color: rgba(255, 255, 255, 0.85);
            padding: 3rem 1rem;
        }

        footer .footer-link {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        /* Animations */
        .reveal {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.in-view {
            opacity: 1;
            transform: none;
        }
    </style>
</head>

<body>

{{-- header --}}
    <header class="site-header transparent" id="siteHeader">
        <nav class="container d-flex align-items-center justify-content-between navbar">
            <a class="brand text-dark text-decoration-none" href="#">FinanceTracker</a>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-link text-dark">Login</a>
                <a href="#" class="btn btn-success text-white">Get Started</a>
            </div>
        </nav>
    </header>

{{-- Hero --}}
<section id="hero" class="position-relative overflow-hidden">
  <div class="hero-bg position-absolute w-100 h-100 top-0 start-0" style="z-index:0;">
    <div style="position:absolute; width:120%; height:120%; background: radial-gradient(circle at 20% 30%, rgba(40,167,69,0.15), transparent 70%); top:-10%; left:-10%; animation: float1 12s ease-in-out infinite alternate;"></div>
    <div style="position:absolute; width:100%; height:100%; background: radial-gradient(circle at 80% 80%, rgba(16,185,129,0.1), transparent 70%); top:0; left:0; animation: float2 15s ease-in-out infinite alternate;"></div>
    <div style="position:absolute; width:50px; height:50px; background: rgba(40,167,69,0.2); border-radius:50%; top:20%; left:15%; animation: float3 18s ease-in-out infinite alternate;"></div>
    <div style="position:absolute; width:70px; height:70px; background: rgba(16,185,129,0.15); border-radius:50%; top:60%; left:75%; animation: float4 20s ease-in-out infinite alternate;"></div>
  </div>

  <div class="hero-inner container position-relative" style="z-index:1;">
    <div class="hero-copy">
      <h1 class="hero-title reveal">Take control of your money with clarity and confidence</h1>
      <p class="hero-sub reveal">Smart budgets, automated reminders, and visual insights to guide your financial decisions.</p>
      <div class="d-flex gap-2 cta-group mb-3 reveal">
        <a href="{{ route('login') }}" class="btn btn-success">Start Tracking</a>
        <a href="#insights" class="btn btn-outline-secondary">Learn More</a>
      </div>
      <p class="muted small reveal">Trusted by individuals who want a simpler, clearer way to manage money.</p>
    </div>

    <div class="hero-media reveal">
      <div class="mock-card">
        <div class="d-flex justify-content-between mb-3">
          <div>
            <div class="small text-muted">Balance</div>
            <div class="h4 mb-0 fw-semibold">₱4,820.50</div>
          </div>
          <div class="text-end small text-success">+4.2%</div>
        </div>
        <div class="mock-chart" aria-hidden="false" aria-label="Balance sparkline">
          <!-- Your existing SVG trend -->
        </div>
        <div class="d-flex gap-2 small mt-2">
          <div class="flex-fill text-muted text-center">Transactions<br><strong>34</strong></div>
          <div class="flex-fill text-muted text-center">Budgets<br><strong>3</strong></div>
        </div>
      </div>
    </div>
  </div>
</section>

    {{-- features --}}
    <section id="features" style="background: linear-gradient(180deg,#071018 0%, #0b1720 100%); color: rgba(255,255,255,0.92); padding: 4rem 1rem;">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="fw-bold reveal">Core Features</h3>
                <p class="muted reveal" style="color: rgba(255,255,255,0.75);">Everything you need to simplify bills, budgets, and everyday spending.</p>
            </div>
            <div class="features-row">
                <article class="feature-card reveal" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.04);">
                    <span class="feature-icon" style="background: rgba(255,255,255,0.03); color: #28a745;"><img src="{{ asset('bills.gif') }}" alt="Bills & Subscriptions"
                            style="width:100%;height:100%;object-fit:cover;border-radius:12px;" /></span>

                    <h5 style="color: #fff;">Bill & Subscription Tracking</h5>
                    <p class="small" style="color: rgba(255,255,255,0.75);">Track due dates, set reminders, and manage recurring expenses.</p>
                </article>
                <article class="feature-card reveal" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.04);">
                    <span class="feature-icon" style="background: rgba(255,255,255,0.03); color: #28a745;"><img src="{{ asset('chart.gif') }}" alt="Pie Chart"
                            style="width:100%;height:100%;object-fit:cover;border-radius:12px;" /></span>
                    <h5 style="color: #fff;">Smart Budgeting</h5>
                    <p class="small" style="color: rgba(255,255,255,0.75);">Monitor budgets, get spending nudges, and save efficiently.</p>
                </article>
                <article class="feature-card reveal" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.04);">
                    <span class="feature-icon" style="background: rgba(255,255,255,0.03); color: #28a745;"><img src="{{ asset('transaction.gif') }}" alt="Transactions"
                            style="width:100%;height:100%;object-fit:cover;border-radius:12px;" /></span>
                    <h5 style="color: #fff;">Transactions Overview</h5>
                    <p class="small" style="color: rgba(255,255,255,0.75);">Search, categorize, and visualize spending at a glance.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Insights --}}
    <section id="insights">
        <div class="container">
            <div class="insights-grid">
                <div>
                    <h3 class="fw-bold reveal">Understand your financial habits</h3>
                    <p class="muted reveal">Detailed insights and simple recommendations help you save more and stress
                        less.</p>
                    <ul class="mt-3 reveal">
                        <li>• Visual spending breakdowns & trend analysis</li>
                        <li>• Personalized saving recommendations</li>
                        <li>• Exportable reports for taxes & planning</li>
                    </ul>
                    <div class="mt-3 reveal">
                        <div class="testimonial">
                            <div class="small text-muted">“This app made budgeting effortless — I finally know where my
                                money goes.”</div>
                            <div class="fw-bold mt-2">— Alex P., Product Designer</div>
                        </div>
                    </div>
                </div>
<aside class="insight-chart reveal">
  <svg width="100%" height="250" viewBox="0 0 600 250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="animated bar chart">
    <rect width="100%" height="100%" rx="10" fill="#fbfdff"/>
    
    {{-- Bars --}}
    <rect x="40" width="40" height="100" y="150" fill="#28a745" class="bar"/>
    <rect x="100" width="40" height="120" y="130" fill="#28a745" class="bar"/>
    <rect x="160" width="40" height="80" y="170" fill="#28a745" class="bar"/>
    <rect x="220" width="40" height="140" y="110" fill="#28a745" class="bar"/>
    <rect x="280" width="40" height="90" y="160" fill="#28a745" class="bar"/>
    <rect x="340" width="40" height="130" y="120" fill="#28a745" class="bar"/>
    <rect x="400" width="40" height="70" y="180" fill="#28a745" class="bar"/>
    <rect x="460" width="40" height="110" y="140" fill="#28a745" class="bar"/>
    <rect x="520" width="40" height="95" y="155" fill="#28a745" class="bar"/>
  </svg>
</aside>

<style>
.bar {
  transform-origin: bottom;
  animation: bounce 1.5s ease-in-out infinite;
}

/* stagger animation delays for natural loop */
.bar:nth-child(2) { animation-delay: 0.1s; }
.bar:nth-child(3) { animation-delay: 0.2s; }
.bar:nth-child(4) { animation-delay: 0.3s; }
.bar:nth-child(5) { animation-delay: 0.4s; }
.bar:nth-child(6) { animation-delay: 0.5s; }
.bar:nth-child(7) { animation-delay: 0.6s; }
.bar:nth-child(8) { animation-delay: 0.7s; }
.bar:nth-child(9) { animation-delay: 0.8s; }

@keyframes bounce {
  0%, 100% { transform: scaleY(0.5); }
  50% { transform: scaleY(1); }
}
</style>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">About</h6>
                    <p class="small footer-link">FinanceTracker helps you manage money with clarity and simple tools.
                    </p>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">Contact</h6>
                    <ul class="list-unstyled small">
                        <li class="footer-link">support@financetracker.app</li>
                        <li class="footer-link">Twitter: @financetracker</li>
                    
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">Legal</h6>
                    <ul class="list-unstyled small">
                        <li><a class="footer-link text-decoration-none" href="#">Privacy</a></li>
                        <li><a class="footer-link text-decoration-none" href="#">Terms</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <h6 class="fw-bold">Follow</h6>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-light btn-sm" href="#"></a>
                        <a class="btn btn-outline-light btn-sm" href="#"></a>
                        <a class="btn btn-outline-light btn-sm" href="#"></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3 small">© 2025 FinanceTracker. All rights reserved.</div>
        </div>
    </footer>

    <script>
        (function() {
            const header = document.getElementById('siteHeader');
            const onScroll = () => {
                header.classList.toggle('scrolled', window.scrollY > 20);
            };
            onScroll();
            window.addEventListener('scroll', onScroll);

            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('in-view');
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.12
            });
            document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
