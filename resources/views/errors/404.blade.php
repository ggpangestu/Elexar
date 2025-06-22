<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Tidak Ditemukan</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f4f8;
            text-align: center;
            padding-top: 10vh;
        }
        h1 {
            font-size: 4rem;
            color: #e53e3e;
        }
        p {
            font-size: 1.2rem;
            margin: 1rem 0;
        }
        a {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.6rem 1.2rem;
            background: #3182ce;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover {
            background: #2b6cb0;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oops! Halaman yang kamu cari tidak ditemukan.</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>
</html>
