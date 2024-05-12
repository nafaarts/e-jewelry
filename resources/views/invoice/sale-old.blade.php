<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'Pembelian | #' . $sale->sale_number }}</title>

    <style>
        :root {
            --width: 210mm;
            --height: 297mm;
            --padding-header: 140px;
            --padding: 40px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, serif;
            visibility: visible;
            display: grid;
            place-items: center;
            padding: 20px;
            background: #d9d9d9;
        }

        .page {
            display: table;
        }

        .page {
            position: relative;
            background: #fff;
            padding: var(--padding-header) var(--padding) var(--padding) var(--padding);
            min-width: var(--width);
            max-width: var(--width);
            min-height: var(--height);
        }

        .page .header {
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
        }

        .page .content .title {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
            text-decoration: underline;
        }

        .page .content .sale-detail {
            margin-top: 1rem;
        }

        .page .content .sale-detail .sale-detail-item {
            width: 50%;
        }

        .page .content .sale-detail .sale-detail-item h3 {
            font-weight: 600;
            font-size: 0.75rem;
            line-height: 1rem;
            margin-bottom: 4px !important;
        }

        .page .content .sale-detail .sale-detail-item table {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .page .content .sale-item {
            margin: 1rem 0;
        }

        .page .content .sale-item table {
            font-size: 0.75rem;
            line-height: 1rem;
            width: 100%;
            border-collapse: collapse;
        }

        .page .content .sale-item table th {
            font-weight: 600;
            text-align: center;
            border: 1px solid #a0a0a0;
            background: #f0f0f0;
        }

        .page .content .sale-item table td {
            border: 1px solid #a0a0a0;
            padding: 0.2rem 0.5rem;
        }

        .page .content .remarks {
            display: flex;
            flex-direction: column;
            margin-top: 1rem;
        }

        .page .content .remarks h3 {
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 600;
            margin-bottom: 4px !important;
        }

        .page .content .remarks p {
            font-size: 10px;
            color: #a0a0a0;
            white-space: pre-line;
        }

        .page .footer .footer-information {
            width: 70%;
        }

        .page .footer .footer-information .note-title {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 4px !important;
        }

        .page .footer .footer-information .note-description {
            font-size: 10px;
            color: #a0a0a0;
            white-space: pre-line;
        }

        .page .footer .footer-information .created_by {
            font-size: 10px;
            color: #a0a0a0;
        }

        .page .footer .footer-signature {
            width: 30%;
        }

        .page .footer .footer-signature .signature {
            border-bottom: 1px solid #a0a0a0;
            width: 80%;
            margin-bottom: 6px;
            color: #a0a0a0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }


        @media print {
            body {
                visibility: hidden;
            }

            #print-area {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
                min-width: var(--width);
                min-height: var(--height);
            }
        }
    </style>
</head>

<body>
    <div class="page" id="print-area">
        <img class="header" src="{{ convertImageToBase64('storage/' . $config['invoice_sale_header_image']) }}"
            alt="header">

        <div class="content">
            <h1 class="title">PEMBELIAN</h1>

            <div class="sale-detail">
                <div class="sale-detail-item">
                    <h3>Detail</h3>
                    <table>
                        <tr>
                            <td class="sale-detail-title">Nomor</td>
                            <td class="gap">:</td>
                            <td>{{ $sale->sale_number }}</td>
                        </tr>
                        <tr>
                            <td class="sale-detail-title">Tanggal</td>
                            <td class="gap">:</td>
                            <td>{{ $sale->updated_at->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="sale-detail-item">
                    <h3>Pelanggan</h3>
                    <table>
                        <tr>
                            <td class="sale-detail-title">Nama</td>
                            <td class="gap">:</td>
                            <td>{{ $sale->costumer->name }}</td>
                        </tr>
                        <tr>
                            <td class="sale-detail-title">Alamat</td>
                            <td class="gap">:</td>
                            <td>{{ $sale->costumer->address }}</td>
                        </tr>
                        <tr>
                            <td class="sale-detail-title">No. HP</td>
                            <td class="gap">:</td>
                            <td>{{ $sale->costumer->phone_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="sale-item">
                <table>
                    <tr>
                        <th scope="row" rowspan="2">Kadar</th>
                        <th scope="row" rowspan="2">Nama</th>
                        <th scope="row" colspan="2">Berat</th>
                        <th scope="row" rowspan="2">Harga</th>
                    </tr>
                    <tr>
                        <th scope="col">Gram</th>
                        <th scope="col">Milie</th>
                    </tr>

                    @foreach ($sale->sold_items as $item)
                        <tr>
                            <td class="text-center">
                                {{ $item['price']['carat'] }} ({{ $item['price']['rate'] }}%)
                            </td>
                            <td>
                                <p class="font-medium text-gray-900 whitespace-nowrap">
                                    {{ $item['name'] }}
                                </p>
                                <small class="font-normal text-gray-500">
                                    {{ $item['jewelry_code'] }}
                                </small>
                            </td>
                            <td class="text-center">
                                {{ $item['weight'] }}
                            </td>
                            <td class="text-center">
                                {{ $item['weight'] * 1000 }}
                            </td>
                            <td class="text-center">
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
                            <td colspan="4" class="text-right">Total</td>
                            <td class="text-center">
                                Rp. {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Diskon
                                @if ($sale->is_percent_discount)
                                    ({{ $sale->discount }}%)
                                @endif
                            </td>
                            <td class="text-center">
                                Rp. {{ number_format($discountPrice, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="4" class="text-right">Total Harga</td>
                        <td class="text-center">
                            <strong>
                                Rp. {{ number_format($sale->total_amount - $discountPrice, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="remarks">
                <h3>Catatan:</h3>
                <p>{{ $sale->remarks ?? '-' }}</p>
            </div>
        </div>

        <div class="footer">
            <div class="footer-information">
                <div style="margin-bottom: 1rem">
                    <h3 class="note-title">Perhatian:</h3>
                    <p class="note-description">
                        {{ $config['invoice_sale_note'] }}
                    </p>
                </div>
                <i class="created_by">{{ now()->format('Y/m/d H:i:s') }} | {{ auth()->user()->name }}</i>
            </div>
            <div class="footer-signature">
                <div class="signature">
                    <small>TTD</small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
