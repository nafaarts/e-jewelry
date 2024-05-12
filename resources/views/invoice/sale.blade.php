<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ 'Pembelian | #' . $sale->sale_number }}</title>

    <style>
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        @page {
            size: "{{ $config['invoice_sale_paper_size'] }}";
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

        .text-10 {
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="page" id="printable-area">
        <img style="width: 100%; height: auto;"
            src="{{ convertImageToBase64('storage/' . $config['invoice_sale_header_image']) }}" alt="header">

        <div style="{{ $config['invoice_sale_paper_size'] == 'A4' ? 'padding: 20px 40px;' : 'padding: 10px 20px;' }}">
            <h1 class="text-md font-bold my-3 text-center underline">PEMBELIAN</h1>

            <table class="w-full">
                <tr>
                    <td class="w-half align-top">
                        <h3 class="text-xs font-bold mb-2">Detail</h3>
                        <table class="text-xs">
                            <tr>
                                <td class="text-grey">Nomor</td>
                                <td class="px-2">:</td>
                                <td>{{ $sale->sale_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">Tanggal</td>
                                <td class="px-2">:</td>
                                <td>{{ $sale->updated_at->translatedFormat('d F Y') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-half align-top">
                        <h3 class="text-xs font-bold mb-2">Pelanggan</h3>
                        <table class="text-xs">
                            <tr>
                                <td class="text-grey">Nama</td>
                                <td class="px-2">:</td>
                                <td>{{ $sale->costumer->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">Alamat</td>
                                <td class="px-2">:</td>
                                <td>{{ $sale->costumer->address }}</td>
                            </tr>
                            <tr>
                                <td class="text-grey">No. HP</td>
                                <td class="px-2">:</td>
                                <td>{{ $sale->costumer->phone_number }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="w-full border-collapse text-xs my-3">
                <tr>
                    <th class="border-grey bg-grey" scope="row" rowspan="2">Kadar</th>
                    <th class="border-grey bg-grey" scope="row" rowspan="2">Nama</th>
                    <th class="border-grey bg-grey" scope="row" colspan="2">Berat</th>
                    <th class="border-grey bg-grey" scope="row" rowspan="2">Harga</th>
                </tr>
                <tr>
                    <th class="border-grey bg-grey" scope="col">Gram</th>
                    <th class="border-grey bg-grey" scope="col">Milie</th>
                </tr>

                @foreach ($sale->sold_items as $item)
                    <tr>
                        <td class="border-grey p-row text-center">
                            {{ $item['price']['carat'] }} ({{ $item['price']['rate'] }}%)
                        </td>
                        <td class="border-grey p-row">
                            <p>
                                {{ $item['name'] }}
                            </p>
                            <small class="text-grey">
                                {{ $item['jewelry_code'] }}
                            </small>
                        </td>
                        <td class="border-grey p-row text-center">
                            {{ $item['weight'] }}
                        </td>
                        <td class="border-grey p-row text-center">
                            {{ $item['weight'] * 1000 }}
                        </td>
                        <td class="border-grey p-row text-center">
                            Rp. {{ number_format($item['sell_price'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach

                @php
                    $discountPrice = $sale->discount;
                    if ($sale->is_percent_discount) {
                        $discountPrice = $sale->total_amount * ($sale->discount / 100);
                    }
                @endphp

                @if ($sale->discount > 0)
                    <tr>
                        <td colspan="4" class="border-grey p-row text-right">Total</td>
                        <td class="border-grey p-row text-center">
                            Rp. {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="border-grey p-row text-right">Diskon
                            @if ($sale->is_percent_discount)
                                ({{ $sale->discount }}%)
                            @endif
                        </td>
                        <td class="border-grey p-row text-center">
                            Rp. {{ number_format($discountPrice, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td colspan="4" class="border-grey p-row text-right">Total Harga</td>
                    <td class="border-grey p-row text-center">
                        <strong>
                            Rp. {{ number_format($sale->total_amount - $discountPrice, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>

            <table class="w-full">
                <tr>
                    <td style="width: 70%; vertical-align: top">
                        <div style="min-height: 120px">
                            <h3 class="text-xs font-bold mb-2">Catatan</h3>
                            <p class="text-xs text-grey white-space-preline">{{ $sale->remarks ?? '-' }}</p>
                        </div>
                    </td>
                    <td style="width: 5%"></td>
                    <td style="border-bottom: 1px solid grey; vertical-align: bottom">
                        <small class="text-xs text-grey">TTD</small>
                    </td>
                </tr>
            </table>

            <div style="width: 100%; margin-top: 50px">
                <div style="margin-bottom: 1rem">
                    <h3 class="text-10 font-bold mb-2">Perhatian:</h3>
                    <p class="text-10 text-grey white-space-preline">{{ $config['invoice_sale_note'] }}</p>
                </div>
                <i class="text-10 text-grey ">{{ now()->format('Y/m/d H:i:s') }} | {{ auth()->user()->name }}</i>
            </div>

        </div>
    </div>

</body>

</html>
