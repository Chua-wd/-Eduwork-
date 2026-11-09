<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toko Online</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; }
        nav { display: flex; gap: 16px; padding: 12px 0; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        nav a { text-decoration: none; color: #2563eb; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <nav>
        <a href="/">Home</a>
        <a href="/products">Produk</a>
        <a href="/cart">Keranjang</a>
        <a href="/checkout">Checkout</a>
        <a href="/about">Tentang</a>
        <a href="/contact">Kontak</a>
    </nav>

    @yield('content')
</body>
</html>
