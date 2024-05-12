<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ 'Perbaikan | #' . $service->service_number }}</title>

    <style>
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        @page {
            size: "{{ $config['invoice_service_paper_size'] }}";
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .align-top {
            vertical-align: top;
        }

        .my-3 {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .underline {
            text-decoration: underline;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-md {
            font-size: 16px;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-grey {
            color: rgb(107 114 128);
        }

        .border-collapse {
            border-collapse: collapse;
        }

        .border-grey {
            border: 1px solid #a0a0a0;
        }

        .bg-grey {
            background-color: #f0f0f0;
        }

        .p-row {
            padding: 0.2rem 0.5rem;
        }

        .white-space-preline {
            white-space: pre-line;
        }

        .relative {
            position: relative;
        }

        .text-10 {
            font-size: 10px;
        }


        /* list */
        .list {
            color: #555;
            padding: 0 !important;
            width: 100%;
        }

        .list li {
            list-style: none;
            border-bottom: 1px dotted rgb(107 114 128);
            text-indent: 25px;
            height: 20px;
            text-transform: capitalize;
        }

        .text {
            position: absolute;
            top: 0;
        }

        .text p {
            white-space: pre-line !important;
            line-height: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="page" id="printable-area">
        <img style="width: 100%; height: auto;"
            src="{{ convertImageToBase64('storage/' . $config['invoice_service_header_image']) }}" alt="header">

        <div
            style="{{ $config['invoice_service_paper_size'] == 'A4' ? 'padding: 20px 40px;' : 'padding: 10px 20px;' }}">
            <h1 class="text-md font-bold my-3 text-center underline">PERBAIKAN</h1>

            <table class="w-full">
                <tr>
                    <td class="w-half align-top">
                        <h3 class="text-xs font-bold mb-2">Detail</h3>
                        <table class="text-xs">
                            <tr>
                                <td class="text-grey">Nomor</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->service_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">Tanggal</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->updated_at->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">Est. Selesai</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->estimated_date? now()->parse($service->estimated_date)->translatedFormat('d F Y'): '-' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-half align-top">
                        <h3 class="text-xs font-bold mb-2">Pelanggan</h3>
                        <table class="text-xs">
                            <tr>
                                <td class="text-grey">Nama</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->costumer->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">Alamat</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->costumer->address }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">No. HP</td>
                                <td class="px-2">:</td>
                                <td>{{ $service->costumer->phone_number }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="my-3">
                <h3 class="text-xs font-bold mb-2">Detail Perbaikan</h3>
                <div class="relative">
                    <div class="text text-grey">
                        <p>{{ $service->remarks }}</p>
                    </div>
                    <ul class="list">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>

            <table class="w-full my-3">
                <tr>
                    <td class="w-half align-top">
                        <table>
                            <tr>
                                <td class="text-xs text-grey">Kategori</td>
                                <td class="text-xs px-2">:</td>
                                <td class="text-xs">{{ $service->category?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-xs text-grey">Berat</td>
                                <td class="text-xs px-2">:</td>
                                <td class="text-xs">{{ $service->weight }} Gr</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-half align-top">
                        <table>
                            <tr>
                                <td class="text-xs text-grey">Jumlah Biaya</td>
                                <td class="text-xs px-2">:</td>
                                <td class="text-xs font-bold">
                                    Rp. {{ number_format($service->cost, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if ($service->paid_amount !== $service->cost)
                                <tr>
                                    <td class="text-xs text-grey">Panjar</td>
                                    <td class="text-xs px-2">:</td>
                                    <td class="text-xs">
                                        Rp. {{ number_format($service->paid_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-xs text-grey">Sisa Bayar</td>
                                    <td class="text-xs px-2">:</td>
                                    <td class="text-xs">
                                        Rp.
                                        {{ number_format($service->cost - $service->paid_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>

            <table class="w-full" style="margin-top: 50px">
                <tr>
                    <td style="width: 70%;">
                        <div style="margin-bottom: 1rem">
                            <h3 class="text-10 font-bold mb-2">Perhatian:</h3>
                            <p class="text-10 text-grey white-space-preline">{{ $config['invoice_service_note'] }}
                            </p>
                        </div>
                        <i class="text-10 text-grey ">{{ now()->format('Y/m/d H:i:s') }} |
                            {{ auth()->user()->name }}</i>
                    </td>
                    <td style="border-bottom: 1px solid grey; vertical-align: bottom">
                        <small class="text-xs text-grey">TTD</small>
                    </td>
                    <td style="width: 5%"></td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
