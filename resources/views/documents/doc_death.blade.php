<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Keterangan Kematian - {{ $citizen->nik }}</title>

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
            margin-bottom: 5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .content p.doc-number {
            text-align: center;
            font-size: 12px;
            margin-bottom: 30px;
            color: #4a5568;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px 0;
            vertical-align: top;
        }

        th {
            width: 180px;
            text-align: left;
            color: #1a202c;
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

        .signature {
            text-align: center;
            font-size: 12px;
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
        <p>Kecamatan .......................... Desa {{ $citizen->village->name ?? '..........................' }}</p>
        <p style="font-size: 10px; color: #718096;">Alamat: {{ $citizen->village->address ?? 'Jl. Raya Desa No. 1' }}</p>
    </div>

    <div class="content">
        <h2>SURAT KETERANGAN KEMATIAN</h2>
        <p class="doc-number">Nomor: 474.3 / {{ date('Y') }} / {{ rand(100, 999) }}</p>

        <p style="font-size: 13px; margin-bottom: 20px; text-align: justify;">
            Yang bertanda tangan di bawah ini, Kepala Desa
            {{ $citizen->village->name ?? '..........................' }},
            menerangkan dengan sebenarnya bahwa:
        </p>

        <table>
            <tr>
                <th>NAMA LENGKAP</th>
                <td>: <strong>{{ $citizen->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <th>NOMOR INDUK KEPENDUDUK</th>
                <td>: {{ $citizen->nik }}</td>
            </tr>
            <tr>
                <th>TEMPAT, TGL LAHIR</th>
                <td>: {{ $citizen->tempat_lahir ?? '-' }}, {{ $citizen->tgl_lahir->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>ALAMAT TERAKHIR</th>
                <td>: {{ $citizen->alamat_desa }}</td>
            </tr>
        </table>

        <p style="font-size: 13px; margin-bottom: 20px; text-align: justify;">
            Orang tersebut di atas telah meninggal dunia pada:
        </p>

        <table>
            <tr>
                <th>TANGGAL KEMATIAN</th>
                <td>:
                    <strong>{{ $record && $record->date_of_death ? $record->date_of_death->format('d F Y') : '-' }}</strong>
                </td>
            </tr>
            <tr>
                <th>TEMPAT KEMATIAN</th>
                <td>: {{ $record->place_of_death ?? '-' }}</td>
            </tr>
            <tr>
                <th>PENYEBAB KEMATIAN</th>
                <td>: {{ $record->cause_of_death ?? '-' }}</td>
            </tr>
            <tr>
                <th>STATUS VERIFIKASI</th>
                <td>:
                    <span class="status-badge {{ $record && $record->status === 'ACTIVE' ? 'active' : '' }}">
                        {{ $record && $record->status === 'ACTIVE' ? 'TERVERIFIKASI / SAH' : 'PENDING / PROSES' }}
                    </span>
                </td>
            </tr>
        </table>

        <p style="font-size: 13px; text-align: justify; margin-top: 20px;">
            Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dapat dipergunakan sebagaimana mestinya,
            seperti untuk pengurusan ahli waris, asuransi, dan dokumen administrasi lainnya.
        </p>
    </div>

    <div class="footer" style="display: block; text-align: right;">
        <div class="signature" style="display: inline-block; width: 220px; text-align: left;">
            <p>Dikeluarkan di: {{ $citizen->village->name ?? 'Desa' }}</p>
            <p>Pada Tanggal: {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 10px;">Kepala Desa {{ $citizen->village->name ?? 'Setempat' }}</p>

            <div id="tte-signature-marker" class="box"
                style="margin: 15px 0; position: relative; display: flex; justify-content: center; align-items: center; border: 1px dashed #cbd5e0; height: 110px; border-radius: 8px; background: #fafafa;">
                <!-- Official TTE QR marker for digital signing coordinate detection -->
                <span style="color: transparent; position: relative; font-size: 1px;">$TTE_MARKER</span>
            </div>

            <script>
                (function() {
                    const el = document.getElementById('tte-signature-marker');
                    if (el) {
                        const bodyRect = document.body.getBoundingClientRect();
                        const boxRect = el.getBoundingClientRect();

                        // Scale calculation for A4 (210mm width)
                        const mmPerPx = 210 / document.documentElement.scrollWidth;

                        // Absolute dimensions and positions in mm
                        const boxWidthMm = boxRect.width * mmPerPx;
                        const boxHeightMm = boxRect.height * mmPerPx;
                        const boxTopMm = (boxRect.top - bodyRect.top) * mmPerPx;
                        const boxLeftMm = (boxRect.left - bodyRect.left) * mmPerPx;

                        // Center point calculation
                        const boxCenterX = boxLeftMm + (boxWidthMm / 2);
                        const boxCenterY = boxTopMm + (boxHeightMm / 2);

                        // QR Size: 80% of the smallest dimension
                        const qrSize = Math.min(boxWidthMm, boxHeightMm) * 0.8;

                        // Final coordinates relative to the page (A4 height 297mm)
                        const page = Math.floor(boxCenterY / 297) + 1;
                        const xMm = boxCenterX - (qrSize / 2);
                        const yOnPage = (boxCenterY % 297) - (qrSize / 2) + 1.0; // Small offset for visual balance

                        document.body.setAttribute('data-tte-page', page);
                        document.body.setAttribute('data-tte-x', xMm.toFixed(2));
                        document.body.setAttribute('data-tte-y', yOnPage.toFixed(2));
                        document.body.setAttribute('data-tte-w', qrSize.toFixed(2));
                    }
                })();
            </script>

            <p><strong>{{ $leader->name ?? '..........................' }}</strong></p>
            @if (isset($leader->nip) && $leader->nip)
                <p>NIP. {{ $leader->nip }}</p>
            @endif
            <p
                style="font-size: 8px; color: #718096; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">
                Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan
                oleh sistem</p>
        </div>
    </div>

    <p
        style="font-size: 9px; color: #a0aec0; margin-top: 60px; text-align: center; border-top: 1px solid #edf2f7; padding-top: 10px;">
        Keaslian dokumen ini dapat diverifikasi dengan memindai kode QR yang tertera pada area tanda tangan.<br>
        Sistem Verifikasi Digital - {{ date('Y') }}
    </p>
</body>

</html>
