<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Respon Jadwal Ulang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="10;url={{ url('/') }}">
    <style>
        :root {
            --green: #38a169;
            --red: #e53e3e;
            --gray-dark: #2d3748;
            --bg-light: #f7fafc;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            padding: 2rem;
            color: var(--gray-dark);
        }

        .container {
            max-width: 600px;
            margin: 3rem auto;
            background: var(--white);
            padding: 2rem 2.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        h2 {
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        .status {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 1rem;
            line-height: 1.6;
        }

        .accepted {
            color: var(--green);
        }

        .rejected {
            color: var(--red);
        }

        .note {
            font-size: 0.9rem;
            margin-top: 1.5rem;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📩 Respon Anda Terkirim</h2>

        @if($status === 'accept')
            <p class="status accepted">
                Anda telah <strong>menerima</strong> jadwal baru.<br>
                Terima kasih atas konfirmasinya.<br>
                Silakan periksa email Anda untuk mendapatkan link meeting.
            </p>
        @elseif($status === 'reject')
            <p class="status rejected">
                Kami mohon maaf Anda <strong>menolak</strong> jadwal baru.<br>
                Jika Anda ingin menjadwalkan ulang kembali, silakan kunjungi halaman utama dan lakukan booking ulang.
            </p>
        @else
            <p class="status">
                Status tidak dikenali.
            </p>
        @endif

        <p class="note">Anda akan diarahkan ke halaman utama...</p>
    </div>
</body>
</html>
