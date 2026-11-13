{{-- Fonts + all Navir design CSS. Used by layouts/app, layouts/guest and components/admin-layout. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ===== Design tokens: tema biru/putih ===== */
        :root {
            --bg: #eef2f6;          /* latar abu-biru muda */
            --surface: #ffffff;
            --panel: #e3e9f0;       /* panel gambar produk */
            --line: #d5dde7;
            --ink: #0b1b2b;         /* teks utama (navy hampir hitam) */
            --muted: #5d6d7e;
            --navy: #0f2a4a;
            --blue: #1d4f86;
            --blue-soft: #dbe7f3;
            --radius: 10px;
            --serif: "Instrument Serif", Georgia, "Times New Roman", serif;
            --sans: "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;

            /* Chart colors (checked with the dataviz palette validator on the white card surface) */
            --chart-1: #2f6aa8;        /* the one data series color (brand blue, mid-lightness) */
            --chart-track: #dbe7f3;    /* lighter step of the same blue, for meter/bar tracks */
            --chart-grid: #e6ebf1;     /* hairline gridlines */
            --good: #0ca30c;  --good-text: #006300;  --good-bg: #e7f5e7;
            --warning: #fab219;  --warning-bg: #fff4dc;
            --critical: #d03b3b;  --critical-text: #b42318;  --critical-bg: #fdecea;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--ink); font-family: var(--sans); font-size: 15px; line-height: 1.5; }
        a { color: inherit; }
        img, svg { display: block; max-width: 100%; }
        .container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }
        main { min-height: 70vh; padding: 28px 0 64px; }

        /* ===== Tipografi ===== */
        .display { font-family: var(--serif); font-weight: 400; line-height: 1.02; letter-spacing: -.01em; margin: 0; }
        .display-xl { font-size: clamp(44px, 6vw, 84px); }
        .display-lg { font-size: clamp(36px, 4vw, 52px); }
        .display-md { font-size: 30px; }
        .eyebrow { font-size: 12px; color: var(--muted); letter-spacing: .02em; }
        .muted { color: var(--muted); }
        .lead { font-size: 18px; line-height: 1.45; }

        /* ===== Tombol & chip ===== */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 46px; padding: 0 26px;
               border-radius: var(--radius); border: 1px solid var(--navy); background: var(--navy); color: #fff;
               font: 500 14px var(--sans); text-decoration: none; cursor: pointer; }
        .btn:hover { background: var(--blue); border-color: var(--blue); }
        .btn-ghost { background: transparent; color: var(--navy); border-color: var(--line); }
        .btn-ghost:hover { background: var(--surface); color: var(--navy); border-color: var(--navy); }
        .btn-sm { height: 34px; padding: 0 14px; font-size: 13px; }
        .btn-danger { background: transparent; color: #b42318; border-color: #f0c8c4; }
        .btn-danger:hover { background: #fdecea; color: #b42318; border-color: #b42318; }

        .chips { display: flex; flex-wrap: wrap; gap: 6px; }
        .chip { display: inline-flex; align-items: center; height: 28px; padding: 0 12px; border: 1px solid var(--line);
                border-radius: 6px; background: var(--surface); font-size: 12px; text-decoration: none; color: var(--ink); }
        .chip:hover { border-color: var(--navy); }
        .chip.active { background: var(--navy); border-color: var(--navy); color: #fff; }

        .pill { background: var(--surface); border-radius: var(--radius); }
        .alert { padding: 12px 16px; border-radius: var(--radius); background: var(--blue-soft); color: var(--navy); margin-bottom: 20px; }
        .alert-error { background: #fdecea; color: #8a1c12; }        .alert ul { margin: 0; padding-left: 18px; }
        hr.rule { border: 0; border-top: 1px solid var(--line); margin: 20px 0; }

        /* ===== Navbar ===== */
        .site-header { position: sticky; top: 0; z-index: 10; padding: 14px 0; background: linear-gradient(var(--bg) 70%, transparent); }
        .nav { display: flex; justify-content: space-between; gap: 12px; }
        .nav-group { display: flex; align-items: center; gap: 4px; padding: 6px; background: var(--surface); border-radius: var(--radius); }
        .nav-group a { padding: 7px 12px; border-radius: 7px; text-decoration: none; font-size: 13px; color: var(--ink); }
        .nav-group a:hover { background: var(--bg); }
        .nav-group a.active { background: var(--bg); font-weight: 600; }
        .brand { display: flex; align-items: center; gap: 8px; font-weight: 600; letter-spacing: .08em; }
        .brand-mark { width: 18px; height: 18px; }
        .count { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 18px; margin-left: 6px;
                 padding: 0 5px; border: 1px solid var(--line); border-radius: 4px; font-size: 11px; }

        /* ===== Panel gambar produk ===== */
        .visual { background: var(--panel); border-radius: var(--radius); overflow: hidden; aspect-ratio: 4 / 5; }
        .visual svg { width: 100%; height: 100%; }

        /* Product without a photo (components/product-visual): name in the display serif, like
           card titles, so an empty slot still reads as Navir and not as a broken image.
           Container queries swap to a single initial in tiny thumbnails (dashboard table, gallery). */
        .pv-empty { container-type: size; box-sizing: border-box; width: 100%; height: 100%; padding: 10%;
                    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;
                    text-align: center; color: var(--navy); background: var(--panel); }
        .pv-empty__name { font-family: var(--serif); font-size: clamp(18px, 11cqi, 44px); line-height: 1.05; overflow-wrap: anywhere; }
        .pv-empty__size { font-size: 13px; }
        .pv-empty__note { margin-top: 10px; font-size: 12px; color: #46566a; } /* 5.8:1 on --panel */
        .pv-empty__initial { display: none; font-family: var(--serif); font-size: 55cqmin; line-height: 1; }
        .pv-empty--dark { background: transparent; color: #eaf2fa; }
        .pv-empty--dark .pv-empty__note { color: inherit; } /* the hero's lighter centre needs full-strength text */
        @container (max-width: 150px) {
            .pv-empty__name, .pv-empty__size, .pv-empty__note { display: none; }
            .pv-empty__initial { display: block; }
        }

        /* ===== Kartu produk ===== */
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .card { display: block; text-decoration: none; color: inherit; }
        .card .visual { transition: transform .25s ease; }
        .card:hover .visual { transform: translateY(-3px); }
        .card-body { display: flex; justify-content: space-between; gap: 12px; padding: 12px 2px 0; }
        .card-title { font-family: var(--serif); font-size: 22px; line-height: 1.1; margin: 2px 0 0; }
        .card-price { font-family: var(--serif); font-size: 22px; white-space: nowrap; }

        /* ===== Akordeon (Product overview / Specifications / Delivery) ===== */
        .accordion details { background: var(--panel); border-radius: 8px; margin-top: 8px; }
        .accordion summary { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px;
                             cursor: pointer; list-style: none; font-size: 13px; }
        .accordion summary::-webkit-details-marker { display: none; }
        .accordion summary::after { content: "↗"; font-size: 14px; transition: transform .2s; }
        .accordion details[open] summary::after { transform: rotate(90deg); }
        .accordion .content { padding: 0 16px 16px; font-size: 14px; color: var(--muted); }
        .spec { display: grid; grid-template-columns: 140px 1fr; gap: 6px 12px; margin: 0; }
        .spec dt { color: var(--muted); }
        .spec dd { margin: 0; color: var(--ink); }

        /* ===== Form ===== */
        .form-card { background: var(--surface); border-radius: var(--radius); padding: 28px; max-width: 640px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
        .field input, .field select, .field textarea { width: 100%; padding: 11px 12px; border: 1px solid var(--line);
               border-radius: 8px; background: var(--bg); font: 15px var(--sans); color: var(--ink); }
        .field input:focus, .field select:focus, .field textarea:focus { outline: 2px solid var(--blue-soft); border-color: var(--blue); background: #fff; }
        .field textarea { min-height: 110px; resize: vertical; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ===== Tabel sederhana (keranjang) ===== */
        .table { width: 100%; border-collapse: collapse; background: var(--surface); border-radius: var(--radius); overflow: hidden; }
        .table th, .table td { padding: 14px 16px; text-align: left; border-bottom: 1px solid var(--line); font-size: 14px; }
        .table th { font-weight: 500; color: var(--muted); font-size: 12px; }

        /* ===== Dashboard tables ===== */
        .table td { vertical-align: middle; }
        .table tr:last-child td { border-bottom: 0; }
        .table tbody tr:hover td { background: #f7f9fc; }
        .table .num { font-variant-numeric: tabular-nums; white-space: nowrap; }
        .table .name { font-family: var(--serif); font-size: 19px; line-height: 1.15; }
        .table .desc { color: var(--muted); max-width: 320px; }
        .thumb-cell { width: 76px; }
        .thumb-cell .visual { width: 56px; height: 70px; aspect-ratio: auto; border-radius: 6px; }
        .row-actions { display: flex; gap: 6px; justify-content: flex-end; }
        .row-actions form { margin: 0; }
        .table-wrap { overflow-x: auto; border-radius: var(--radius); }
        .page-head { display: flex; justify-content: space-between; align-items: end; gap: 16px; flex-wrap: wrap; margin-bottom: 4px; }
        .image-field { display: flex; gap: 16px; align-items: flex-start; }
        .image-field .visual { width: 96px; height: 120px; aspect-ratio: auto; flex: none; border-radius: 8px; }

        /* ===== Pagination ===== */
        .pagination { display: flex; justify-content: center; gap: 6px; list-style: none; padding: 0; margin: 36px 0 8px; }
        .pagination .page-link { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px;
                                 border: 1px solid var(--line); border-radius: 8px; background: var(--surface);
                                 color: var(--ink); text-decoration: none; font-size: 13px; }
        .pagination .page-link:hover { border-color: var(--navy); }
        .pagination .active .page-link { background: var(--navy); border-color: var(--navy); color: #fff; }
        .pagination .disabled .page-link { color: #a8b3bf; background: transparent; }
        .page-info { text-align: center; font-size: 12px; color: var(--muted); }

        /* ===== Footer ===== */
        .site-footer { border-top: 1px solid var(--line); padding: 28px 0 40px; font-size: 13px; color: var(--muted); }
        .site-footer .container { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .site-footer nav a { margin-left: 18px; text-decoration: none; }
        .site-footer nav a:hover { color: var(--ink); }

        /* ===== Hero gelap (beranda) ===== */
        body.dark-hero .site-header { position: absolute; left: 0; right: 0; background: none; }
        body.dark-hero .site-header .nav-group { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.16);
                                                 backdrop-filter: blur(8px); }
        body.dark-hero .site-header a { color: #e8f0f8; }
        body.dark-hero .site-header a:hover, body.dark-hero .site-header a.active { background: rgba(255,255,255,.1); }
        body.dark-hero .site-header .count { border-color: rgba(255,255,255,.35); }
        body.dark-hero main { padding-top: 48px; }

        .hero { position: relative; min-height: max(100vh, 680px); overflow: hidden; color: #eaf2fa;
                display: flex; flex-direction: column; justify-content: flex-end; padding-bottom: 18px;
                background: radial-gradient(ellipse 70% 60% at 50% 38%, #2f6189 0%, #173a5a 45%, #0b1f33 100%); }
        .hero-grain { position: absolute; inset: 0; width: 100%; height: 100%; opacity: .14; mix-blend-mode: overlay; pointer-events: none; }
        .hero-product { position: absolute; left: 50%; top: 9vh; transform: translateX(-50%); width: min(560px, 80vw); height: 66vh; }
        /* Hero photo has a near-black studio background: fade its edges into the navy hero
           so it reads as part of the scene instead of a dark rectangle. */
        .hero-product img { display: block; width: 100%; height: 100%; object-fit: contain;
                            -webkit-mask-image: radial-gradient(ellipse 50% 50% at 50% 50%, #000 62%, transparent 100%);
                                    mask-image: radial-gradient(ellipse 50% 50% at 50% 50%, #000 62%, transparent 100%); }
        .hero-product svg { width: 100%; height: 100%; }
        .hero-title { position: relative; text-align: center; margin: 0 16px 22px;
                      font-family: var(--serif); font-weight: 400; line-height: .95; letter-spacing: -.02em;
                      font-size: clamp(46px, 8.4vw, 128px); text-shadow: 0 8px 40px rgba(5,18,32,.55); }
        .hero-bottom { position: relative; }
        .hero-bottom .container { display: grid; grid-template-columns: 1fr 2.6fr; gap: 20px; align-items: center; }
        .hero-bottom p { margin: 0; font-size: 13px; color: #c9d8e6; max-width: 300px; }
        .glass { display: flex; align-items: stretch; border: 1px solid rgba(255,255,255,.18); border-radius: var(--radius);
                 background: rgba(255,255,255,.06); backdrop-filter: blur(10px); overflow: hidden; }
        .glass span { display: flex; align-items: center; padding: 0 16px; font-size: 12px; color: #d6e3ef; white-space: nowrap; }
        .glass .glass-btn { margin-left: auto; display: flex; align-items: center; justify-content: center; min-width: 240px; height: 48px;
                            background: rgba(255,255,255,.14); color: #fff; text-decoration: none; font-size: 14px; }
        .glass .glass-btn:hover { background: rgba(255,255,255,.24); }
        .promo { position: absolute; right: 4vw; top: 46vh; width: 260px; padding: 18px; border-radius: 16px;
                 background: #f4f8fc; color: var(--ink); text-decoration: none; box-shadow: 0 24px 60px rgba(4,15,28,.35); }
        .promo .arrow { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px;
                        border-radius: 10px; background: var(--navy); color: #fff; font-size: 18px; }

        /* ===== Toolbar katalog (sort + kategori) ===== */
        .toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
                   padding: 8px; background: var(--surface); border-radius: 14px; margin: 18px 0 24px; }
        .tabs { display: flex; gap: 4px; }
        .tabs a { padding: 9px 18px; border-radius: 10px; text-decoration: none; font-size: 13px; color: var(--ink); }
        .tabs a.active { background: var(--navy); color: #fff; }
        .cat-links { display: flex; flex-wrap: wrap; gap: 2px; }
        .cat-links a { padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 13px; color: var(--muted); }
        .cat-links a:hover { color: var(--ink); }
        .cat-links a.active { color: var(--ink); font-weight: 600; }

        /* ===== Auth (Laravel Breeze): login, daftar, lupa password ===== */
        .auth { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }
        .auth-side { position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;
                     padding: 28px 36px; color: #eaf2fa;
                     background: radial-gradient(ellipse 80% 70% at 50% 40%, #2f6189 0%, #173a5a 50%, #0b1f33 100%); }
        .auth-side a { color: inherit; text-decoration: none; }
        .auth-side .display { font-size: clamp(40px, 4.6vw, 68px); text-shadow: 0 8px 40px rgba(5,18,32,.5); }
        .auth-side ul { list-style: none; padding: 0; margin: 20px 0 0; display: flex; gap: 8px; flex-wrap: wrap; }
        .auth-side li { padding: 6px 12px; border: 1px solid rgba(255,255,255,.18); border-radius: 999px; font-size: 12px;
                        background: rgba(255,255,255,.06); }
        .auth-main { display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .auth-card { width: 100%; max-width: 420px; }
        .auth-card .field input { background: var(--surface); }
        .auth-links { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 18px; font-size: 13px; }
        .auth-links a { color: var(--muted); }
        .auth-links a:hover { color: var(--ink); }
        .check { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); }
        .check input { width: 16px; height: 16px; accent-color: var(--navy); }
        .field-error { margin: 6px 0 0; padding: 0; list-style: none; font-size: 12px; color: #b42318; }
        .btn-block { width: 100%; }

        /* ===== Halaman akun & profil ===== */
        .section-card { background: var(--surface); border-radius: var(--radius); padding: 28px; }
        .section-card + .section-card { margin-top: 16px; }
        .section-card h2 { font-family: var(--serif); font-weight: 400; font-size: 28px; margin: 0; }
        .section-card .intro { color: var(--muted); font-size: 14px; margin: 4px 0 20px; max-width: 560px; }
        .saved { font-size: 13px; color: var(--blue); }
        summary.btn { list-style: none; }
        summary.btn::-webkit-details-marker { display: none; }

        /* ===== Admin layout with sidebar ===== */
        .admin { display: grid; grid-template-columns: 252px minmax(0, 1fr); min-height: 100vh; }
        .sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; display: flex; flex-direction: column; gap: 18px;
                   padding: 20px 14px; background: var(--surface); border-right: 1px solid var(--line); }
        .side-search { display: flex; align-items: center; gap: 8px; padding: 0 12px; height: 38px; border-radius: 8px;
                       background: var(--bg); color: var(--muted); }
        .side-search input { flex: 1; min-width: 0; border: 0; background: transparent; font: 13px var(--sans); color: var(--ink); outline: none; }
        .side-search:focus-within { outline: 2px solid var(--blue-soft); }
        .side-nav { display: flex; flex-direction: column; gap: 2px; }
        .side-nav a { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 8px;
                      font-size: 14px; color: var(--ink); text-decoration: none; }
        .side-nav a:hover { background: var(--bg); }
        .side-nav a.active { background: var(--navy); color: #fff; }
        .side-nav-small a { font-size: 13px; padding: 7px 10px; color: var(--muted); }
        .side-nav .badge { margin-left: auto; min-width: 24px; text-align: center; font-size: 11px; padding: 1px 7px;
                           border-radius: 999px; background: var(--bg); color: var(--muted); }
        .side-nav a.active .badge { background: rgba(255,255,255,.16); color: #fff; }
        .side-label { margin: 0 0 6px; padding: 0 10px; font-size: 11px; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); }
        .side-user { margin-top: auto; display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 10px; background: var(--bg); }
        .avatar { width: 34px; height: 34px; flex: none; display: inline-flex; align-items: center; justify-content: center;
                  border-radius: 8px; background: var(--navy); color: #fff; font-size: 12px; font-weight: 600; }
        .avatar-sm { width: 28px; height: 28px; border-radius: 50%; background: var(--blue-soft); color: var(--navy); font-size: 11px; }
        .icon-btn { display: inline-flex; padding: 6px; border: 0; border-radius: 6px; background: transparent; color: var(--muted); cursor: pointer; }
        .icon-btn:hover { background: var(--surface); color: var(--ink); }
        .admin-main { min-width: 0; padding: 28px 32px 56px; }
        .admin-main .page-head { margin-bottom: 18px; }

        /* ===== Dashboard ===== */
        .dash-top { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
        .segmented { display: inline-flex; gap: 2px; padding: 4px; border-radius: 10px; background: var(--surface); }
        .segmented a { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; border-radius: 7px;
                       font-size: 13px; color: var(--muted); text-decoration: none; }
        .segmented a:hover { color: var(--ink); background: var(--bg); }
        .segmented a.active { background: var(--navy); color: #fff; font-weight: 600; }
        .dash-card { background: var(--surface); border-radius: 14px; padding: 20px; min-width: 0; }
        .dash-card h2 { margin: 0; font-size: 15px; font-weight: 600; }
        .card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
        .card-head a { font-size: 12px; color: var(--muted); }
        .dash-row { display: grid; gap: 16px; margin-top: 16px; }

        .kpi-strip { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)) 210px; background: var(--surface); border-radius: 14px; }
        .kpi { padding: 18px 16px; border-right: 1px solid var(--line); min-width: 0; }
        .kpi-top { display: flex; align-items: center; gap: 8px; min-height: 28px; font-size: 12px; line-height: 1.2; color: var(--muted); }
        .kpi-value-row { display: flex; align-items: center; gap: 8px; }
        .kpi-value-row .task-mark { margin-left: auto; }
        .kpi-sub { display: block; margin-top: 4px; }
        .kpi-icon { width: 28px; height: 28px; flex: none; display: inline-flex; align-items: center; justify-content: center;
                    border-radius: 8px; background: var(--blue-soft); color: var(--navy); }
        .kpi-value { margin: 12px 0 8px; font-size: 28px; font-weight: 600; letter-spacing: -.01em; line-height: 1.1; white-space: nowrap; }
        .kpi-note { font-size: 11px; color: var(--muted); }
        .task-mark { margin-left: auto; font-size: 10px; padding: 1px 6px; border-radius: 999px; background: var(--bg); color: var(--muted); }
        .delta { display: inline-flex; align-items: center; gap: 3px; padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .delta.up { color: var(--good-text); background: var(--good-bg); }
        .delta.down { color: var(--critical-text); background: var(--critical-bg); }
        .delta.flat { color: var(--muted); background: var(--bg); }

        .meter { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 14px 18px; text-align: center; }
        .meter svg { width: 168px; height: auto; }
        .meter-value { margin-top: -54px; font-size: 26px; font-weight: 600; }
        .meter-label { font-size: 11px; color: var(--muted); }

        .line-chart { position: relative; outline: none; }
        .line-chart svg { width: 100%; height: auto; overflow: visible; }
        .line-chart:focus-visible { outline: 2px solid var(--blue-soft); border-radius: 8px; }
        .line-chart .grid { stroke: var(--chart-grid); stroke-width: 1; }
        .line-chart .grid.base { stroke: var(--line); }
        .line-chart .tick { font: 11px var(--sans); fill: var(--muted); font-variant-numeric: tabular-nums; }
        .line-chart .crosshair { stroke: var(--muted); stroke-width: 1; }
        .chart-tip { position: absolute; transform: translateX(-50%); pointer-events: none; padding: 7px 10px; border-radius: 8px;
                     background: var(--ink); color: #fff; font-size: 12px; line-height: 1.3; white-space: nowrap;
                     display: flex; flex-direction: column; box-shadow: 0 6px 20px rgba(11,27,43,.18); }
        .chart-tip strong { font-size: 14px; }
        .chart-tip span { color: #b9c6d3; }
        .chart-table summary { cursor: pointer; font-size: 12px; color: var(--muted); margin-top: 8px; }
        .chart-table .table { margin-top: 8px; max-height: 260px; display: block; overflow-y: auto; }
        .chart-big { font-size: 30px; font-weight: 600; letter-spacing: -.01em; }

        .hbars { display: flex; flex-direction: column; gap: 12px; }
        .hbar-label { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; }
        .hbar-row { display: flex; align-items: center; gap: 8px; }
        .hbar { height: 18px; min-width: 2px; background: var(--chart-1); border-radius: 0 4px 4px 0; }
        .hbar:hover { filter: brightness(1.12); }
        .hbar-value { font-size: 12px; color: var(--ink); font-variant-numeric: tabular-nums; }
        .track { height: 8px; border-radius: 999px; background: var(--chart-track); overflow: hidden; }
        .track > span { display: block; height: 100%; border-radius: 999px; background: var(--chart-1); }

        .list-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-top: 1px solid var(--line); }
        .list-row:first-child { border-top: 0; }
        .list-thumb .visual { width: 40px; height: 50px; aspect-ratio: auto; border-radius: 6px; }

        .pill-status { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 999px; font-size: 12px;
                       background: var(--bg); color: var(--ink); white-space: nowrap; }
        .pill-status::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--muted); }
        .pill-status.delivered { background: var(--good-bg); }   .pill-status.delivered::before { background: var(--good); }
        .pill-status.pending { background: var(--warning-bg); }  .pill-status.pending::before { background: var(--warning); }
        .pill-status.returned { background: var(--critical-bg); } .pill-status.returned::before { background: var(--critical); }
        .pill-status.processing, .pill-status.shipped { background: var(--blue-soft); }
        .pill-status.processing::before { background: var(--chart-1); }
        .pill-status.shipped::before { background: var(--navy); }
        
        .confirm-delete { display: inline-flex; gap: 6px; align-items: center; } .confirm-delete[open] summary { display: none; }

        /* ===== Cart & checkout ===== */
        /* Steps: Cart -> Checkout -> Payment */
        .steps { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin: 0 0 22px; padding: 0; list-style: none; font-size: 13px; color: var(--muted); }
        .steps li { display: flex; align-items: center; gap: 8px; }
        .steps li + li::before { content: ""; width: 28px; height: 1px; background: var(--line); }
        .steps .n { display: inline-grid; place-items: center; width: 22px; height: 22px; border-radius: 50%; border: 1px solid var(--line); font-size: 11px; }
        .steps .on { color: var(--navy); font-weight: 600; }
        .steps .on .n, .steps .done .n { background: var(--navy); border-color: var(--navy); color: #fff; }

        /* Quantity − 1 + */
        .qty { display: inline-flex; align-items: center; border: 1px solid var(--line); border-radius: 8px; background: var(--surface); height: 46px; }
        .qty button { width: 40px; height: 100%; border: 0; background: none; font: 18px var(--sans); color: var(--navy); cursor: pointer; }
        .qty button:hover:not(:disabled) { background: var(--bg); }
        .qty button:disabled { color: var(--line); cursor: default; }
        .qty input, .qty span { width: 34px; border: 0; background: none; text-align: center; font: 15px var(--sans); color: var(--ink); font-variant-numeric: tabular-nums; -moz-appearance: textfield; }
        .qty input::-webkit-inner-spin-button, .qty input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .qty.sm { height: 34px; }
        .qty.sm button { width: 32px; font-size: 16px; }

        /* "Added to cart" popup */
        .toast { position: fixed; right: 20px; bottom: 20px; z-index: 60; display: flex; gap: 14px; align-items: center; max-width: calc(100vw - 40px);
                 padding: 12px 14px; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius);
                 box-shadow: 0 12px 32px rgba(11,27,43,.14); animation: toast-in .25s ease; }
        .toast .visual { width: 48px; height: 48px; aspect-ratio: auto; border-radius: 8px; flex: none; }
        @keyframes toast-in { from { transform: translateY(140%); } }

        .cart-item { display: grid; grid-template-columns: 72px 1fr auto auto 28px; gap: 16px; align-items: center; padding: 14px 16px; border-bottom: 1px solid var(--line); }
        .cart-item:last-child { border-bottom: 0; }
        .cart-thumb { width: 72px; height: 72px; aspect-ratio: auto; border-radius: 8px; }
        .cart-item .name { font-family: var(--serif); font-size: 20px; line-height: 1.15; color: var(--ink); text-decoration: none; }
        .cart-item .line-total { min-width: 110px; text-align: right; font-variant-numeric: tabular-nums; }
        .remove-btn { border: 0; background: none; color: var(--muted); font-size: 18px; cursor: pointer; border-radius: 6px; width: 28px; height: 28px; }
        .remove-btn:hover { background: #fdecea; color: #b42318; }

        .summary .row { display: flex; justify-content: space-between; gap: 12px; margin: 6px 0; font-size: 14px; }
        .summary .row span:last-child { font-variant-numeric: tabular-nums; }
        .hint { font-size: 12px; color: var(--muted); }
        .ship-bar { height: 4px; border-radius: 4px; background: var(--bg); overflow: hidden; margin-top: 6px; }
        .ship-bar i { display: block; height: 100%; background: var(--blue); }

        .mini-item { display: flex; gap: 10px; align-items: center; font-size: 13px; margin: 8px 0; }
        .mini-item .visual { width: 40px; height: 40px; aspect-ratio: auto; border-radius: 6px; flex: none; }
        .mini-item .grow { flex: 1; }

        .pay-box { border: 1px dashed var(--blue); background: var(--blue-soft); border-radius: var(--radius); padding: 18px 20px; }
        .pay-box .acct { font-family: var(--serif); font-size: 28px; letter-spacing: .02em; }
        .field textarea.prefilled { min-height: 260px; font: 13px/1.55 ui-monospace, Consolas, monospace; }
        .tag { display: inline-block; font-size: 11px; padding: 2px 8px; border-radius: 999px; background: var(--blue-soft); color: var(--navy); }
        .field input[readonly] { color: var(--muted); }

        /* ===== User management ===== */
        .user-filters { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .user-search { flex: 0 1 420px; height: 40px; background: var(--bg); }
        .role-select { height: 38px; min-width: 150px; padding: 0 32px 0 12px; border: 1px solid var(--line); border-radius: 8px;
                       background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235d6d7e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center;
                       font: 14px var(--sans); color: var(--ink); cursor: pointer; -webkit-appearance: none; appearance: none; }
        .role-select:hover { border-color: var(--navy); }
        .role-select:focus { outline: 2px solid var(--blue-soft); border-color: var(--blue); }
        .role-select.is-static { display: inline-flex; align-items: center; background: var(--bg); color: var(--muted); cursor: default; }
        .role-select.is-static:hover { border-color: var(--line); }
        .users-table td { padding-top: 10px; padding-bottom: 10px; }
        .users-table th:last-child, .users-table td:last-child { width: 1%; white-space: nowrap; }
        .sort-link { color: inherit; text-decoration: none; }
        .sort-link:hover { color: var(--ink); }
        .trash-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border: 1px solid var(--line);
                     border-radius: 8px; background: var(--surface); color: var(--muted); cursor: pointer; list-style: none; }
        .trash-btn::-webkit-details-marker { display: none; }
        .trash-btn:hover { border-color: #f0c8c4; background: #fdecea; color: #b42318; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

        /* ===== Responsif ===== */
        @media (max-width: 900px) {
            .grid { grid-template-columns: repeat(2, 1fr); }
            .split { grid-template-columns: 1fr !important; }
            .nav-links { display: none !important; }
            .promo { display: none; }
            .admin { grid-template-columns: 1fr; }
            .sidebar { position: static; height: auto; border-right: 0; border-bottom: 1px solid var(--line); }
            .side-user { margin-top: 0; }
            .admin-main { padding: 20px 16px 48px; }
            .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .kpi { border-bottom: 1px solid var(--line); }
            .dash-row { grid-template-columns: 1fr !important; }
            .auth { grid-template-columns: 1fr; }
            .auth-side { min-height: 240px; }
            .hero { justify-content: flex-start; padding-top: 84px; min-height: 0; }
            .hero-product { position: relative; left: auto; top: auto; transform: none; margin: 0 auto;
                            width: 80vw; height: min(52vh, 420px); min-height: 260px; }
            .hero-bottom .container { grid-template-columns: 1fr; }
            .glass span:not(:first-child) { display: none; }
            .cart-item { grid-template-columns: 60px 1fr auto; gap: 10px 14px; }
            .cart-thumb { width: 60px; height: 60px; grid-row: span 2; }
            .cart-item .qty { grid-column: 2; }
            .cart-item .line-total { grid-column: 3; grid-row: 2; min-width: 0; }
            .cart-item .remove { grid-column: 3; grid-row: 1; justify-self: end; }
        }
        @media (max-width: 560px) {
            .grid { grid-template-columns: 1fr; }
            .row-2 { grid-template-columns: 1fr; }
        }
    </style>
