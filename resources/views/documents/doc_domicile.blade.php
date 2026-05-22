<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Keterangan Domisili - {{ $citizen->nik }}</title>

    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: white;
            margin: 0;
            line-height: 1.5;
            font-size: 12pt;
            box-sizing: border-box;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-surat h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kop-surat h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kop-surat h3 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.3;
        }

        .kop-surat p {
            margin: 4px 0 0 0;
            font-size: 10pt;
            font-style: italic;
            line-height: 1.2;
        }

        .line-double {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 8px;
            margin-bottom: 25px;
        }

        .title-block {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-block h4 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }

        .title-block p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }

        .opening-text {
            text-align: justify;
            text-indent: 0;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 15px;
        }

        .data-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-table td.num {
            width: 25px;
            text-align: left;
        }

        .data-table td.label {
            width: 175px;
            text-align: left;
        }

        .data-table td.colon {
            width: 15px;
            text-align: center;
        }

        .closing-text {
            text-align: justify;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .purpose-box {
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .footer-table {
            width: 100%;
            margin-top: 30px;
        }

        .footer-table td {
            vertical-align: top;
        }

        .signature-block {
            width: 250px;
            text-align: center;
            float: right;
        }

        .signature-block p {
            margin: 0;
            font-size: 11pt;
            line-height: 1.3;
        }

        .signature-box {
            height: 105px;
            margin: 10px auto;
            border: 1px dashed #ccc;
            width: 180px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fafafa;
            border-radius: 6px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1e293b;
            color: white;
            padding: 10px 20px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            font-family: sans-serif;
            z-index: 999;
        }

        .no-print:hover {
            transform: translateY(-2px);
            background: #0f172a;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    @php
        $regency = $citizen->village->district->regency_name ?? 'Tulang Bawang Barat';
        $isTubaba = str_contains(strtolower($regency), 'tulang bawang barat');

        $villageType = $isTubaba ? 'TIYUH' : 'DESA';
        $leaderType = $isTubaba ? 'Kepalo Tiyuh' : 'Kepala Desa';

        $regencyName = strtoupper(str_replace(['kabupaten ', 'kab '], '', strtolower($regency)));
        $districtName = strtoupper(str_replace('kecamatan ', '', strtolower($citizen->village->district->name ?? '')));
        $villageName = strtoupper(str_replace(['desa ', 'tiyuh '], '', strtolower($citizen->village->name ?? '')));

        // Deterministic fields based on NIK
        $nik = $citizen->nik;
        $genderNum = (int) substr($nik, 6, 2);
        $gender = $genderNum > 40 ? 'Perempuan' : 'Laki-laki';

        $religion = 'Islam';

        $birthDateStr = $citizen->tgl_lahir ? $citizen->tgl_lahir->translatedFormat('d F Y') : '-';
        $birthPlace = $isTubaba ? '' : '';

        $age = $citizen->tgl_lahir ? $citizen->tgl_lahir->age : 22;
        $status = $age < 22 ? 'Belum Kawin' : 'Kawin';

        if ($age < 7) {
            $education = 'Belum Sekolah';
            $job = 'Belum/Tidak Bekerja';
        } elseif ($age < 12) {
            $education = 'SD / Sederajat';
            $job = 'Pelajar/Mahasiswa';
        } elseif ($age < 15) {
            $education = 'SLTP / Sederajat';
            $job = 'Pelajar/Mahasiswa';
        } elseif ($age < 19) {
            $education = 'SLTA / Sederajat';
            $job = 'Belum/Tidak Bekerja';
        } else {
            $education = 'SLTA / Sederajat';
            $job = $gender === 'Perempuan' ? 'Mengurus Rumah Tangga' : 'Karyawan Swasta';
        }

        $rt = $citizen->householdCard->rt ?? '003';
        $rw = $citizen->householdCard->rw ?? '003';
        $fullAddress = "RT {$rt} / RW {$rw}, {$villageType} {$citizen->village->name}, Kecamatan {$citizen->village->district->name}, Kabupaten {$citizen->village->district->regency_name}";
    @endphp

    <a href="javascript:window.print()" class="no-print">Cetak Dokumen</a>

    <!-- Kop Surat -->
    <table class="kop-table" style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td style="width: 80px; vertical-align: middle; text-align: left; padding: 0;">
                @if (file_exists(public_path('assets/images/logo-tubaba.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/logo-tubaba.png'))) }}"
                        style="width: 70px; height: auto;" />
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle; padding: 0 0 0 10px;">
                <div style="font-size: 14pt; font-weight: bold; line-height: 1.2;">PEMERINTAH KABUPATEN
                    {{ $regencyName }}</div>
                <div style="font-size: 13pt; font-weight: bold; line-height: 1.2;">KECAMATAN {{ $districtName }}</div>
                <div style="font-size: 16pt; font-weight: bold; line-height: 1.3;">{{ $villageType }}
                    {{ $villageName }}</div>
                <div
                    style="font-size: 8.5pt; font-weight: normal; margin-top: 3px; font-family: Arial, sans-serif; font-style: italic;">
                    Jl. Raya {{ $citizen->village->name }} Email:
                    {{ strtolower(str_replace(' ', '', $villageName ?? '')) }}@tubaba.go.id
                </div>
            </td>
        </tr>
    </table>

    <div class="line-double"></div>

    <div class="title-block">
        <h4>SURAT KETERANGAN DOMISILI</h4>
        <p>Nomor : {{ $record->letter_number ?? '___________________' . date('Y') }}</p>
    </div>

    <div class="opening-text">
        Yang bertanda tangan di bawah ini {{ $leaderType }} {{ $citizen->village->name ?? '' }},
        Kecamatan {{ $citizen->village->district->name ?? '' }},
        Kabupaten {{ $citizen->village->district->regency_name ?? '' }}, Provinsi Lampung
        menerangkan dengan sebenarnya bahwa :
    </div>

    <table class="data-table">
        <tr>
            <td class="num">1.</td>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td><strong>{{ strtoupper($citizen->nama_lengkap) }}</strong></td>
        </tr>
        <tr>
            <td class="num">2.</td>
            <td class="label">NIK / No. KTP</td>
            <td class="colon">:</td>
            <td>{{ $citizen->nik }}</td>
        </tr>
        <tr>
            <td class="num">3.</td>
            <td class="label">Tanggal Lahir</td>
            <td class="colon">:</td>
            <td>{{ $birthDateStr }}</td>
        </tr>
        <tr>
            <td class="num">4.</td>
            <td class="label">Jenis Kelamin</td>
            <td class="colon">:</td>
            <td>{{ $gender }}</td>
        </tr>
        <tr>
            <td class="num">5.</td>
            <td class="label">Alamat / Tempat Tinggal</td>
            <td class="colon">:</td>
            <td>RT {{ $rt }} / RW {{ $rw }}, {{ $villageType }} {{ $citizen->village->name }},
                Kecamatan {{ $citizen->village->district->name }}, Kabupaten
                {{ $citizen->village->district->regency_name }}</td>
        </tr>
    </table>

    <div class="closing-text">
        Orang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di RT {{ $rt }} / RW
        {{ $rw }}, {{ $villageType }}
        {{ $citizen->village->name }}, Kecamatan {{ $citizen->village->district->name ?? 'Tulang Bawang Tengah' }},
        Kabupaten {{ $citizen->village->district->regency_name ?? 'Tulang Bawang Barat' }}.
    </div>

    <div class="purpose-box">
        Surat Keterangan ini dibuat untuk Keperluan :
        <strong>{{ $record->purpose ?? 'Melengkapi Persyaratan Administrasi' }}</strong>.
    </div>

    <div class="closing-text" style="margin-bottom: 40px;">
        Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.
    </div>

    <table class="footer-table">
        <tr>
            <td></td>
            <td style="width: 250px;">
                <div class="signature-block">
                    <p>{{ $citizen->village->name ?? '' }}, {{ now()->translatedFormat('d F Y') }}</p>
                    <p>{{ $leaderType }} {{ $citizen->village->name ?? '' }}</p>

                    <div id="tte-signature-marker" class="signature-box">
                        <span style="color: transparent; font-size: 1px;">TTEMARKERS</span>
                    </div>

                    <script>
                        (function() {
                            const el = document.getElementById('tte-signature-marker');
                            if (el) {
                                const bodyRect = document.body.getBoundingClientRect();
                                const boxRect = el.getBoundingClientRect();
                                const mmPerPx = 210 / bodyRect.width;
                                const boxWidthMm = boxRect.width * mmPerPx;
                                const boxHeightMm = boxRect.height * mmPerPx;
                                const boxTopMm = (boxRect.top - bodyRect.top) * mmPerPx;
                                const boxLeftMm = (boxRect.left - bodyRect.left) * mmPerPx;
                                const boxCenterX = boxLeftMm + (boxWidthMm / 2);
                                const boxCenterY = boxTopMm + (boxHeightMm / 2);
                                const qrSize = Math.min(boxWidthMm, boxHeightMm) * 0.75;
                                const page = Math.floor(boxCenterY / 297) + 1;
                                const xMm = boxCenterX - (qrSize / 2);
                                const verticalOffset = (page > 1) ? 15.0 : 0.0;
                                const yOnPage = (boxCenterY % 297) - (qrSize / 2) + verticalOffset;

                                document.body.setAttribute('data-tte-page', page);
                                document.body.setAttribute('data-tte-x', xMm.toFixed(2));
                                document.body.setAttribute('data-tte-y', yOnPage.toFixed(2));
                                document.body.setAttribute('data-tte-w', qrSize.toFixed(2));
                            }
                        })();
                    </script>

                    <p><strong><u>{{ $leader->name ?? '..........................' }}</u></strong></p>
                    @if (isset($leader->nip) && $leader->nip)
                        <p>NIP. {{ $leader->nip }}</p>
                    @endif
                    <p
                        style="font-size: 7.5pt; color: #555; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">
                        Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang
                        diterbitkan oleh sistem
                    </p>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
