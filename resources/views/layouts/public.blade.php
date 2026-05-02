<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->meta_title ?: $page->title }}</title>
    <meta name="description" content="{{ $page->meta_description }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-madani.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-body">
    <a href="#main-content" class="skip-link">Lewati ke konten</a>

    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ route('home') }}" class="brand-lockup" aria-label="{{ $settings->get('site_name', 'Madani Montessori Islamic School') }}">
                <img src="{{ asset('images/logo-madani-montessori.png') }}" alt="Logo {{ $settings->get('site_name', 'Madani Montessori Islamic School') }}" class="logo-badge">
                <span class="brand-copy">
                    <span class="brand-copy__name">Madani Montessori</span>
                    <span class="brand-copy__descriptor">Islamic School</span>
                </span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="site-navigation">
                <span class="sr-only">Buka menu</span>
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav id="site-navigation" class="site-nav" data-site-nav aria-label="Navigasi utama">
                @foreach ($headerNavigation as $item)
                    @php
                        $path = trim($item->url, '/');
                        $isActive = ($item->url === '/' && request()->is('/')) || ($path !== '' && request()->is($path));
                    @endphp
                    <a href="{{ $item->url }}" @class(['is-active' => $isActive])>{{ $item->label }}</a>
                @endforeach
                <a href="{{ $whatsappUrl }}" class="nav-cta">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer__texture" aria-hidden="true"></div>
        <div class="site-footer__inner">
            <div class="footer-brand">
                <div class="footer-brand__top">
                    <img src="{{ asset('images/logo-madani-montessori.png') }}" alt="Logo {{ $settings->get('site_name', 'Madani Montessori Islamic School') }}" class="logo-badge logo-badge--footer">
                    <div>
                        <strong>{{ $settings->get('site_name', 'Madani Montessori Islamic School') }}</strong>
                        <span>TK Islam Terpadu berbasis Montessori</span>
                    </div>
                </div>
                <p>{{ $settings->get('footer_summary') }}</p>
            </div>

            <div class="footer-links">
                <h2 class="footer-heading">Menu</h2>
                @foreach ($footerNavigation as $item)
                    <a href="{{ $item->url }}">{{ $item->label }}</a>
                @endforeach
            </div>

            <div class="footer-contact">
                <h2 class="footer-heading">Kontak</h2>
                <p>{{ $settings->get('address') }}</p>
                <a href="{{ $whatsappUrl }}" class="btn btn--gold">Hubungi Admin</a>
            </div>
        </div>
        <div class="site-footer__bottom">
            <span>&copy; {{ now()->year }} Madani Montessori Islamic School.</span>
            <span>Designed for a calm, premium school experience.</span>
        </div>
    </footer>
</body>
</html>
