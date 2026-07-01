<html>
<head>
    <style>
        @page {
            size: 150mm 80mm landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        @media print {
            .thermal-label {
                width: 150mm;
                height: 80mm;
                padding: 5mm;
                box-sizing: border-box;
            }
            body {
                margin: 0;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }
        .barcode img {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            height: auto;
        }

        .bold {
            text-align:center;
            font-weight: bold;
        }

        .small {
            text-align:center;
            font-size: 14px;
        }

        .medium {
            text-align:center;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="thermal-label">
        <table>
            <tr>
                <td class="barcode" style="width:120mm;text-align:center;">
                    <img src="{{ @$package->barcodeImageBase64 }}" alt="Barcode Image" style="width:100%; max-width: 120mm; height:40px;" />
                </td>
            </tr>
            <tr>
                <td style="text-align: center;" class="medium">
                    {{ @$package->waybill ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="bold medium" style="text-align:center;">{{ @$package->client_suite ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="bold medium">{{ trim(@$package->first_name . ' ' . @$package->last_name) ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="small">descripción: {{ @$package->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="small">{{ @$package->original_tracking ?? '' }}</td>
            </tr>
            <tr>
                <td class="small">Peso: {{ @$package->grand_total ?? 0.0 }} Volumen: {{ @$package->kg ?? 0.0 }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
