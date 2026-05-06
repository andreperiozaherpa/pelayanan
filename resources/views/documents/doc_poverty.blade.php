<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Bukti Verifikasi - {{ $citizen->nik }}</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: #1a202c;
            background: white;
            margin: 0;
            padding: 2cm;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2.5px double #1a202c;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #4a5568;
            font-weight: 500;
        }

        .content {
            margin-bottom: 40px;
        }

        .content h2 {
            font-size: 16px;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 30px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 8px 0;
            vertical-align: top;
        }

        th {
            width: 140px;
            text-align: left;
            color: #718096;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            background: #edf2f7;
            border: 1px solid #e2e8f0;
        }

        .active {
            color: #2f855a;
            background: #f0fff4;
            border-color: #c6f6d5;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .qr-code {
            text-align: center;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
            border: 1px solid #e2e8f0;
            padding: 5px;
        }

        .signature {
            text-align: center;
            font-size: 11px;
        }

        .signature .box {
            height: 60px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3182ce;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <a href="javascript:window.print()" class="no-print">Cetak Sekarang</a>

    <div class="header">
        <h1>Pemerintah Kabupaten/Kota</h1>
        <p>Sistem Verifikasi ------</p>
        <p style="font-style: italic;">"Digital-First Citizen Empowerment"</p>
    </div>

    <div class="content">
        <h2>SURAT KETERANGAN VERIFIKASI DIGITAL</h2>

        <p style="font-size: 11px; margin-bottom: 15px;">Diterangkan bahwa data penduduk di bawah ini telah diverifikasi
            melalui Sistem Verifikasi --------- dengan status sebagai berikut:</p>

        <table>
            <tr>
                <th>NOMOR INDUK KEPENDUDUK</th>
                <td>: <strong>{{ $citizen->nik }}</strong></td>
            </tr>
            <tr>
                <th>NAMA LENGKAP</th>
                <td>: {{ $citizen->nama_lengkap }}</td>
            </tr>
            <tr>
                <th>ALAMAT ASAL</th>
                <td>: {{ $citizen->alamat_desa }}</td>
            </tr>
            <tr>
                <th>STATUS KEMISKINAN</th>
                <td>:
                    <span class="status-badge {{ $record && $record->valid_until >= now() ? 'active' : '' }}">
                        {{ $record && $record->valid_until >= now() ? 'TERDAFTAR / AKTIF' : 'TIDAK AKTIF / PENDING' }}
                    </span>
                </td>
            </tr>
            @if ($record)
                <tr>
                    <th>MASA BERLAKU DATA</th>
                    <td>: {{ now()->format('d F Y') }} s/d {{ $record->valid_until->format('d F Y') }}</td>
                </tr>
            @endif
            <tr>
                <th>TANGGAL VERIFIKASI</th>
                <td>: {{ now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer" style="display: block; text-align: right;">
        <div class="signature" style="display: inline-block; width: 180px;">
            <p>Mengetahui,</p>
            <p>Kepala Desa {{ $citizen->village->name ?? 'Setempat' }}</p>
            <div id="tte-signature-marker" class="box"
                style="margin: 15px 0; position: relative; display: flex; justify-content: center; align-items: center; border: 1px dashed #e2e8f0; height: 110px; border-radius: 8px; background: #fcfcfc;">
                <!-- Official TTE QR marker -->
                <span style="color: transparent; position: relative;">$TTE_1</span>
            </div>

            <script>
                (function() {
                    const el = document.getElementById('tte-signature-marker');
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        // Use absolute position relative to document
                        const scrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
                        const scrollX = window.scrollX || window.pageXOffset || document.documentElement.scrollLeft;

                        // High-precision scale calculation
                        // 1 inch = 25.4mm. Standard Puppeteer DPI is 96.
                        // However, we still use the dynamic ratio for safety
                        const mmPerPx = 210 / document.documentElement.scrollWidth;

                        // Get position relative to the document root
                        const bodyRect = document.body.getBoundingClientRect();
                        const boxRect = el.getBoundingClientRect();

                        // Absolute dimensions of the box in mm
                        const boxWidthMm = boxRect.width * mmPerPx;
                        const boxHeightMm = boxRect.height * mmPerPx;

                        // Position of box relative to body start in mm
                        const boxTopMm = (boxRect.top - bodyRect.top) * mmPerPx;
                        const boxLeftMm = (boxRect.left - bodyRect.left) * mmPerPx;

                        // QR Size: Use 75% of the box height to ensure it's comfortably centered
                        const qrSize = Math.min(boxWidthMm, boxHeightMm) * 0.75;

                        // Calculate the CENTER of the box in mm
                        const boxCenterX = boxLeftMm + (boxWidthMm / 2);
                        const boxCenterY = boxTopMm + (boxHeightMm / 2);

                        // Page calculation based on center point
                        const page = Math.floor(boxCenterY / 297) + 1;

                        // Final coordinates for QR Top-Left:
                        // CenterPoint - (QRSize / 2)
                        // Adding a tiny +1.5mm vertical offset to compensate for PDF rendering quirks
                        const xMm = boxCenterX - (qrSize / 2);
                        const yOnPage = (boxCenterY % 297) - (qrSize / 2) + 1.5;

                        // Store in body attributes for PHP to read
                        document.body.setAttribute('data-tte-page', page);
                        document.body.setAttribute('data-tte-x', xMm.toFixed(2));
                        document.body.setAttribute('data-tte-y', yOnPage.toFixed(2));
                        document.body.setAttribute('data-tte-w', qrSize.toFixed(2));
                        document.body.classList.add('tte-calculated');
                    }
                })();
            </script>
            <p><strong>__________________________</strong></p>
            <p style="font-size: 8px; color: #718096; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px;">
                Dokumen ini ditandatangani secara elektronik</p>
        </div>
    </div>

    <p style="font-size: 9px; color: #a0aec0; margin-top: 40px; text-align: center;">
        Dokumen ini diterbitkan secara otomatis oleh Sistem Verifikasi --------- dan sah sebagai bukti verifikasi
        digital.<br>
        Keaslian dokumen dapat dipastikan dengan memindai kode QR di atas.
    </p>

</body>

</html>
