<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Jadwal Ulang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --color-bg: #f7fafc;
            --color-text: #2d3748;
            --color-white: #ffffff;
            --color-green: #38a169;
            --color-green-hover: #2f855a;
            --color-red: #e53e3e;
            --color-red-hover: #c53030;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .container {
            max-width: 600px;
            margin: 2rem auto;
            background: var(--color-white);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #1a202c;
        }

        p {
            margin: 0.5rem 0;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        li {
            margin-bottom: 0.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            margin-top: 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .btn-accept {
            background-color: var(--color-green);
            color: white;
            margin-right: 10px;
        }

        .btn-accept:hover {
            background-color: var(--color-green-hover);
            transform: scale(1.02);
        }

        .btn-reject {
            background-color: var(--color-red);
            color: white;
        }

        .btn-reject:hover {
            background-color: var(--color-red-hover);
            transform: scale(1.02);
        }

        .footer-note {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📅 Konfirmasi Jadwal Ulang</h2>

        <p>Halo, <strong>{{ $bookings->user->name }}</strong></p>
        <p>Admin telah mengajukan perubahan jadwal booking Anda.</p>

        <ul>
            <li><strong>Jadwal Baru:</strong> {{ $bookings->booking_date_time->format('d M Y, H:i') }}</li>
            <li><strong>Alasan Reschedule:</strong> {{ $bookings->reschedule_reason }}</li>
        </ul>

        <form method="POST" action="{{ route('reschedule.respond', ['token' => $bookings->reschedule_token]) }}" style="display:inline;">
            @csrf
            <input type="hidden" name="action" value="accept">
            <button type="submit" class="btn btn-accept">✅ Terima Jadwal Baru</button>
        </form>

        <form method="POST" action="{{ route('reschedule.respond', ['token' => $bookings->reschedule_token]) }}" style="display:inline;">
            @csrf
            <input type="hidden" name="action" value="reject">
            <button type="submit" class="btn btn-reject">❌ Tolak Jadwal Baru</button>
        </form>

        <p class="footer-note">Harap konfirmasi sebelum: {{ $bookings->reschedule_expires_at->format('d M Y, H:i') }}</p>
    </div>
</body>
</html>
