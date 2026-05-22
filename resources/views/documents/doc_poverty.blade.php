<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Keterangan Kurang Mampu - {{ $citizen->nik }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: white;
            margin: 0;
            line-height: 1.4;
            box-sizing: border-box;
        }

        /* Double border line for Kop Surat */
        .line-double {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12.5pt;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 15px 0 3px;
            letter-spacing: 0.5px;
        }

        .number {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        .opening {
            text-align: justify;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .data-table {
            width: 100%;
            margin: 10px 0 15px 15px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }

        .statement {
            text-align: justify;
            margin: 12px 0;
            line-height: 1.5;
        }

        .family-title {
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0 5px;
            text-align: center;
            font-size: 10.5pt;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .family-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin: 5px 0 15px;
        }

        .family-table th,
        .family-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
        }

        .family-table th {
            font-weight: bold;
            text-transform: uppercase;
        }

        .family-table td.left {
            text-align: left;
            text-transform: uppercase;
        }

        .purpose {
            text-align: justify;
            margin: 12px 0;
            line-height: 1.5;
        }

        .closing {
            text-align: justify;
            margin: 12px 0 20px;
            line-height: 1.5;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .sig-block {
            width: 48%;
            text-align: center;
        }

        .sig-block .know {
            font-weight: normal;
            margin-bottom: 5px;
            text-align: left;
        }

        .sig-block .date {
            margin-bottom: 5px;
            text-align: center;
        }

        .sig-block .position {
            font-weight: normal;
            margin: 0 0 0px;
            min-height: 35px;
            line-height: 1.3;
        }

        .signature-box {
            height: 75px;
            width: 130px;
            border: 1px dashed #ccc;
            margin: 0px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fafafa;
            border-radius: 4px;
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

            body {
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>
    <a href="javascript:window.print()" class="no-print">Cetak Sekarang</a>

    @php
        $regency = $citizen->village->district->regency_name ?? 'Tulang Bawang Barat';
        $isTubaba = str_contains(strtolower($regency), 'tulang bawang barat');
        $villageType = $isTubaba ? 'tiyuh' : 'desa';
        $leaderType = $isTubaba ? 'Kepalo' : 'Kepala';
        $regencyName = strtoupper(str_replace(['kabupaten ', 'kab '], '', strtolower($regency)));
        $districtName = strtoupper(
            str_replace('kecamatan ', '', strtolower($citizen->village->district->name ?? 'Tulang Bawang Tengah')),
        );
        $villageName = strtoupper(str_replace(['desa ', 'tiyuh '], '', strtolower($citizen->village->name ?? '')));
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
        $rt = $citizen->householdCard->rt ?? '001';
        $rw = $citizen->householdCard->rw ?? '006';
        $fullAddress = "RT {$rt} / RW {$rw}, {$villageType} {$citizen->village->name}, Kecamatan {$citizen->village->district->name}, Kabupaten {$citizen->village->district->regency_name}";

        // Load family members sharing the same household card
        $family_members = collect();
        if ($citizen->household_card_id) {
            $family_members = \App\Models\Citizen::where('household_card_id', $citizen->household_card_id)
                ->orderBy('tgl_lahir', 'asc')
                ->get();
        }

        if ($family_members->isEmpty()) {
            $family_members = collect([$citizen]);
        }
    @endphp

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
                <div style="font-size: 16pt; font-weight: bold; line-height: 1.3; text-transform: uppercase">
                    {{ $villageType }}
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

    <!-- Title -->
    <div class="title">SURAT KETERANGAN KURANG MAMPU</div>
    <div class="number">Nomor: {{ $record->letter_number ?? '___________________' }}</div>

    <!-- Opening -->
    <div class="opening">
        Yang bertanda tangan di bawah ini {{ ucwords($leaderType) }} {{ ucwords($villageType) }}
        {{ ucwords($citizen->village->name) }},
        Kecamatan {{ ucwords($citizen->village->district->name) }},
        Kabupaten {{ ucwords($citizen->village->district->regency_name) }},
        Provinsi Lampung menerangkan dengan sebenarnya bahwa:
    </div>

    <!-- Personal Data -->
    <table class="data-table">
        <tr>
            <td style="width: 25px; padding: 2.5px 0; vertical-align: top;">1.</td>
            <td style="width: 190px; padding: 2.5px 0; vertical-align: top;">Nama Lengkap</td>
            <td style="width: 15px; padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">
                <strong>{{ strtoupper($citizen->nama_lengkap) }}</strong>
            </td>
        </tr>
        <tr>
            <td style="padding: 2.5px 0; vertical-align: top;">2.</td>
            <td style="padding: 2.5px 0; vertical-align: top;">No. KTP</td>
            <td style="padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">{{ $nik }}</td>
        </tr>
        <tr>
            <td style="padding: 2.5px 0; vertical-align: top;">3.</td>
            <td style="padding: 2.5px 0; vertical-align: top;"> Tanggal Lahir</td>
            <td style="padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">{{ $birthDateStr }}
            </td>
        </tr>
        <tr>
            <td style="padding: 2.5px 0; vertical-align: top;">4.</td>
            <td style="padding: 2.5px 0; vertical-align: top;">Jenis Kelamin</td>
            <td style="padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">{{ $gender }}</td>
        </tr>
        <tr>
            <td style="padding: 2.5px 0; vertical-align: top;">5.</td>
            <td style="padding: 2.5px 0; vertical-align: top;">Pekerjaan</td>
            <td style="padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">{{ $job }}</td>
        </tr>
        <tr>
            <td style="padding: 2.5px 0; vertical-align: top;">6.</td>
            <td style="padding: 2.5px 0; vertical-align: top;">Alamat / Tempat Tinggal</td>
            <td style="padding: 2.5px 0; vertical-align: top; text-align: center;">:</td>
            <td style="padding: 2.5px 0; vertical-align: top;">{{ $fullAddress }}</td>
        </tr>
    </table>

    <!-- Statement -->
    <div class="statement">
        Bahwa yang tersebut namanya di atas, sepanjang pengetahuan dan penelitian kami hingga saat
        dikeluarkannya surat keterangan ini memang benar Keluarga yang <strong>KURANG MAMPU</strong>
        dan tidak memiliki penghasilan tetap.
    </div>

    <!-- Family Table Title -->
    <div class="family-title">DAFTAR TANGGUNGAN KELUARGA</div>

    <!-- Family Table -->
    <table class="family-table">
        <thead>
            <tr>
                <th style="width:5%">NO.</th>
                <th style="width:20%">NIK</th>
                <th style="width:25%">NAMA</th>
                <th style="width:12%">L/P</th>
                <th style="width:23%">TANGGAL LAHIR</th>
                <th style="width:15%">SHDK</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($family_members as $member)
                @php
                    $mGenderNum = (int) substr($member->nik ?? $nik, 6, 2);
                    $mGender = $mGenderNum > 40 ? 'PEREMPUAN' : 'LAKI-LAKI';
                    $mBirth = $member->tgl_lahir
                        ? strtoupper($member->tempat_lahir ?? $birthPlace) .
                            '' .
                            strtoupper($member->tgl_lahir->translatedFormat('d F Y'))
                        : strtoupper($birthPlace) . ', -';
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $member->nik ?? $nik }}</td>
                    <td class="left">{{ strtoupper($member->nama_lengkap) }}</td>
                    <td>{{ $mGender }}</td>
                    <td class="left">{{ $mBirth }}</td>
                    <td>{{ strtoupper($member->status_keluarga ?? ($loop->first ? 'KEPALA KELUARGA' : 'ANAK')) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Closing -->
    <div class="closing">
        Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <!-- Signature -->
    <div class="signature" style="display: flex; justify-content: flex-end; margin-top: 30px;">
        <div class="sig-block" style="width: 220px; text-align: right;">
            <div class="date" style="text-align: left; line-height: 1.3;">
                {{ $citizen->village->name }}, {{ now()->translatedFormat('d F Y') }}
            </div>

            <!-- Position: Right aligned, same text alignment as date -->
            <div class="position" style="text-align: left; min-height: 45px; line-height: 1.4;">
                Kepalo {{ $citizen->village->name }}<br>
            </div>

            <!-- ✅ TTE Marker: Invisible text for backend PDF parsing -->
            <div id="tte-signature-marker" class="signature-box"
                style="height: 80px; width: 140px; border: 1px dashed #ccc;
                    margin: 0px auto 10px; display: flex; justify-content: center; align-items: center;">
                <span style=" font-size: 1px;">TTEMARKERS</span>
            </div>

            <div class="name"
                style="font-weight: bold; text-decoration: underline; margin: 10px 0 3px; text-align: center;">
                {{ $leader->name ?? '..........................' }}
            </div>

            <!-- NIP: Centered, smaller font -->
            @if (isset($leader->nip) && $leader->nip)
                <div class="nip" style="font-size: 10pt; color: #555; text-align: center;">
                    NIP. {{ $leader->nip ?? '-' }}
                </div>
            @endif
        </div>
    </div>

</body>

</html>
