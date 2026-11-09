<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toko Online</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; color: #1f2937; }
        main { max-width: 960px; margin: 0 auto; padding: 24px; min-height: 70vh; }

        /* Navbar */
        .navbar { display: flex; justify-content: space-between; align-items: center;
                  padding: 14px 24px; background: #1e40af; }
        .navbar a { color: #fff; text-decoration: none; }
        .navbar .brand { font-weight: 700; font-size: 1.2rem; }
        .navbar .links a { margin-left: 18px; opacity: .8; }
        .navbar .links a.active { opacity: 1; font-weight: 700; border-bottom: 2px solid #fff; }

        /* Footer */
        .footer { text-align: center; padding: 20px; background: #f3f4f6; color: #6b7280; }
        .footer a { color: #1e40af; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }

        /* Kartu produk */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; }
        .card h3 { margin: 0 0 8px; }
        .price { color: #1e40af; font-weight: 700; }
    </style>
</head>
<body>
    <x-navbar />

    <main>
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
