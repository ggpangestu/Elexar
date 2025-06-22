<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 30%;
            left: 10%;
            font-size: 160px;
            color: rgba(100, 100, 100, 0.07);
            transform: rotate(-45deg);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        h2 {
            margin-bottom: 16px;
        }

        img {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .total {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">ELEXAR</div>
    <div class="content">
        @php
            $typeLabel = match ($dataType) {
                'users' => 'Data User',
                'bookings' => 'Data Booking',
                default => 'Laporan'
            };

            $rangeLabelFormatted = match ($rangeLabel) {
                'daily' => 'Harian',
                'monthly' => 'Bulanan',
                'yearly' => 'Tahunan',
                default => ucfirst($rangeLabel)
            };
        @endphp

        <h2>Laporan {{ $typeLabel }} - {{ strtoupper($rangeLabelFormatted) }}</h2>

        @if($chartBase64)
            <img src="{{ $chartBase64 }}" style="width: 100%;">
        @endif

        @if($dataType === 'users')
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users ?? [] as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total">Total User: {{ count($users ?? []) }}</p>

        @elseif($dataType === 'bookings')
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jadwal Pengajuan Meeting</th>
                        <th>Kategori</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings ?? [] as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->user->name ?? '-' }}</td>
                            <td>{{ $b->user->email ?? '-' }}</td>
                            <td>{{ $b->booking_date_time->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($b->category) }}</td>
                            <td>{{ ucfirst($b->status->value) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total">Total Booking: {{ count($bookings ?? []) }}</p>

        @else
            <p>Data tidak tersedia.</p>
        @endif
    </div>
</body>
</html>
