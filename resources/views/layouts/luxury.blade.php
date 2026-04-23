<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <title>{{ $title ?? 'Elevate Luxury Living' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --lux-black:      #0A0A0A;
            --lux-black-soft: #141414;
            --lux-black-card: #1C1C1C;
            --lux-gold:       #C9A84C;
            --lux-gold-light: #E2C97E;
            --lux-gold-dim:   #8A6E2F;
            --lux-white:      #F5F0E8;
            --lux-white-soft: #D6CFC2;
        }

        body.luxury-page {
            background-color: var(--lux-black);
            color: var(--lux-white);
            font-family: 'Figtree', sans-serif;
        }

        /* ── NAV ── */
        .lux-nav {
            background-color: var(--lux-black);
            border-bottom: 1px solid rgba(201,168,76,0.2);
        }
        .lux-nav a { color: var(--lux-white-soft); transition: color .2s; }
        .lux-nav a:hover { color: var(--lux-gold); }
        .lux-nav .lux-active { color: var(--lux-gold); }

        .lux-brand {
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: .08em;
            color: var(--lux-white);
        }

        /* ── GOLD LINE DIVIDER ── */
        .gold-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--lux-gold), transparent);
        }

        /* ── BUTTONS ── */
        .btn-gold {
            background: linear-gradient(135deg, var(--lux-gold), var(--lux-gold-light));
            color: var(--lux-black);
            font-weight: 600;
            letter-spacing: .05em;
            transition: opacity .2s, transform .2s;
        }
        .btn-gold:hover { opacity: .9; transform: translateY(-1px); }

        .btn-gold-outline {
            border: 1px solid var(--lux-gold);
            color: var(--lux-gold);
            transition: background .2s, color .2s;
        }
        .btn-gold-outline:hover {
            background: var(--lux-gold);
            color: var(--lux-black);
        }

        /* ── SECTION BACKGROUNDS ── */
        .lux-bg-black  { background-color: var(--lux-black); }
        .lux-bg-soft   { background-color: var(--lux-black-soft); }
        .lux-bg-card   { background-color: var(--lux-black-card); }

        /* ── TYPOGRAPHY ── */
        .lux-heading {
            font-family: 'Cormorant Garamond', serif;
            color: var(--lux-white);
            letter-spacing: .03em;
        }
        .lux-gold-text { color: var(--lux-gold); }
        .lux-muted { color: var(--lux-white-soft); }

        /* ── CARDS ── */
        .lux-card {
            background-color: var(--lux-black-card);
            border: 1px solid rgba(201,168,76,0.15);
            transition: border-color .3s, box-shadow .3s;
        }
        .lux-card:hover {
            border-color: rgba(201,168,76,0.5);
            box-shadow: 0 8px 40px rgba(201,168,76,0.08);
        }

        /* ── STEP BADGES ── */
        .lux-step-number {
            background: rgba(201,168,76,0.12);
            color: var(--lux-gold);
            border: 1px solid rgba(201,168,76,0.3);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 600;
        }
        .lux-step-row { border: 1px solid rgba(201,168,76,0.1); transition: border-color .3s, box-shadow .3s; }
        .lux-step-row:hover {
            border-color: rgba(201,168,76,0.4);
            box-shadow: 0 4px 24px rgba(201,168,76,0.06);
        }

        .lux-cost-badge {
            background: rgba(201,168,76,0.1);
            color: var(--lux-gold-light);
            border: 1px solid rgba(201,168,76,0.25);
            font-size: .75rem;
            font-weight: 600;
            padding: .35rem .75rem;
            border-radius: .5rem;
        }

        /* ── FOOTER ── */
        .lux-footer {
            background-color: #060606;
            border-top: 1px solid rgba(201,168,76,0.2);
        }

        /* ── RESPONSIVE NAV ── */
        .lux-nav-desktop { display: none; }
        .lux-nav-hamburger { display: flex; }
        .lux-mobile-menu {
            background-color: var(--lux-black);
            border-top: 1px solid rgba(201,168,76,0.2);
        }
        @media (min-width: 768px) {
            .lux-nav-desktop { display: flex; align-items: center; gap: 2rem; }
            .lux-nav-hamburger { display: none; }
            .lux-mobile-menu { display: none !important; }
        }
    </style>
</head>

<body class="luxury-page font-sans antialiased">

    {{-- ─── NAVIGATION ─── --}}
    <nav class="lux-nav sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('luxury-living-logo-1.png') }}"
                         alt="Elevate Luxury Living"
                         class="h-10 w-auto">
                </a>

                {{-- Desktop nav --}}
                <div class="lux-nav-desktop">
                    <a href="{{ route('interior-design') }}"
                       class="text-xs font-semibold tracking-[.2em] uppercase transition"
                       style="color: #C9A84C;">Interieur Design</a>
                    <a href="{{ route('projects.index') }}"
                       class="text-xs font-semibold tracking-[.2em] uppercase transition"
                       style="color: #D6CFC2;"
                       onmouseover="this.style.color='#C9A84C'" onmouseout="this.style.color='#D6CFC2'">Projecten</a>
                    <a href="{{ route('contact') }}"
                       class="text-xs font-semibold tracking-[.2em] uppercase transition"
                       style="color: #D6CFC2;"
                       onmouseover="this.style.color='#C9A84C'" onmouseout="this.style.color='#D6CFC2'">Contact</a>
                    <a href="{{ route('home') }}"
                       class="text-xs font-semibold tracking-[.2em] uppercase transition"
                       style="color: #D6CFC2;"
                       onmouseover="this.style.color='#C9A84C'" onmouseout="this.style.color='#D6CFC2'">← Elevate Real Estate</a>

                    <div class="flex items-center gap-1 pl-6" style="border-left: 1px solid rgba(255,255,255,0.1);">
                        <a href="{{ route('lang.switch', 'nl') }}"
                           class="text-xs px-2 py-1 rounded font-semibold transition"
                           style="color: {{ app()->getLocale() == 'nl' ? '#C9A84C' : 'rgba(255,255,255,0.35)' }};">NL</a>
                        <a href="{{ route('lang.switch', 'en') }}"
                           class="text-xs px-2 py-1 rounded font-semibold transition"
                           style="color: {{ app()->getLocale() == 'en' ? '#C9A84C' : 'rgba(255,255,255,0.35)' }};">EN</a>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="lux-nav-hamburger w-10 h-10 items-center justify-center rounded-sm transition"
                        style="border: 1px solid rgba(201,168,76,0.3); color: #C9A84C;">
                    <i class="fas fa-bars text-base" x-show="!mobileOpen" style="display:block;"></i>
                    <i class="fas fa-times text-base" x-show="mobileOpen" style="display:none;"></i>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div class="lux-mobile-menu" x-show="mobileOpen" x-transition style="display:none;">
            <div class="max-w-6xl mx-auto px-6 py-8 space-y-1">
                <a href="{{ route('interior-design') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-sm text-xs font-semibold tracking-[.2em] uppercase"
                   style="color: #C9A84C; background: rgba(201,168,76,0.06);">
                    Interieur Design
                </a>
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-sm text-xs font-semibold tracking-[.2em] uppercase"
                   style="color: #D6CFC2;">
                    Projecten
                </a>
                <a href="{{ route('contact') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-sm text-xs font-semibold tracking-[.2em] uppercase"
                   style="color: #D6CFC2;">
                    Contact
                </a>
                <a href="{{ route('home') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-sm text-xs font-semibold tracking-[.2em] uppercase"
                   style="color: rgba(214,207,194,0.5);">
                    ← Elevate Real Estate
                </a>
                <div class="flex gap-3 px-4 pt-4" style="border-top: 1px solid rgba(201,168,76,0.15); margin-top: 8px;">
                    <a href="{{ route('lang.switch', 'nl') }}"
                       class="text-xs font-semibold px-3 py-2 rounded-sm"
                       style="color: {{ app()->getLocale() == 'nl' ? '#C9A84C' : 'rgba(255,255,255,0.35)' }}; border: 1px solid {{ app()->getLocale() == 'nl' ? 'rgba(201,168,76,0.4)' : 'rgba(255,255,255,0.1)' }};">NL</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="text-xs font-semibold px-3 py-2 rounded-sm"
                       style="color: {{ app()->getLocale() == 'en' ? '#C9A84C' : 'rgba(255,255,255,0.35)' }}; border: 1px solid {{ app()->getLocale() == 'en' ? 'rgba(201,168,76,0.4)' : 'rgba(255,255,255,0.1)' }};">EN</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ─── MAIN CONTENT ─── --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ─── FOOTER ─── --}}
    <footer class="lux-footer">
        <div class="max-w-6xl mx-auto px-6 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- Brand --}}
                <div>
                    <img src="{{ asset('luxury-living-logo-1.png') }}"
                         alt="Elevate Luxury Living"
                         class="h-10 w-auto mb-5">
                    <p class="lux-muted text-sm leading-relaxed">
                        Wij creëren interieurs die tijdloos, elegant en functioneel zijn.
                    </p>
                    <div class="flex gap-3 mt-5">
                        <a href="https://www.facebook.com/elevaterealestatenv"
                           class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-white/50 hover:border-[#C9A84C] hover:text-[#C9A84C] transition text-sm">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/elevaterealestatenv/"
                           class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-white/50 hover:border-[#C9A84C] hover:text-[#C9A84C] transition text-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?phone=5978180018"
                           class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-white/50 hover:border-[#C9A84C] hover:text-[#C9A84C] transition text-sm">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#C9A84C] mb-5">Navigatie</h4>
                    <ul class="space-y-3 text-sm lux-muted">
                        <li><a href="{{ route('interior-design') }}" class="hover:text-[#C9A84C] transition">Interieur Design</a></li>
                        <li><a href="{{ route('projects.index') }}" class="hover:text-[#C9A84C] transition">Projecten</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-[#C9A84C] transition">Contact</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-[#C9A84C] transition">Terug naar Elevate Real Estate</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-[#C9A84C] mb-5">Contact</h4>
                    <ul class="space-y-3 text-sm lux-muted">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-0.5 text-[#C9A84C]"></i>
                            <span>Frederik Derbystraat no.78<br>Paramaribo, Suriname</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-[#C9A84C]"></i>
                            <a href="tel:+5978180018" class="hover:text-[#C9A84C] transition">+597 8180018</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-[#C9A84C]"></i>
                            <a href="mailto:info@elevaterealestate.sr" class="hover:text-[#C9A84C] transition">info@elevaterealestate.sr</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="gold-divider mt-12 mb-8"></div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs lux-muted">
                <p>&copy; {{ date('Y') }} Elevate Luxury Living. Alle rechten voorbehouden.</p>
                <p>Built by <a href="https://www.evolutionwebworks.be/" target="_blank" class="text-[#C9A84C] hover:text-[#E2C97E] transition">Evolution Web Works</a></p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
