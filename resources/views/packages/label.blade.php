<html> 
<head>
    <style>
        th,
        td,
        p,
        div,
        b {
            margin: 0;
            padding: 0
        }

        @page {
            margin: 0px 0px 0px 0px !important;
            padding: 0px 0px 0px 0px !important;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; ">
    <div style="margin: 0 auto; background: #FFFFFF; height: 100%; width: 100%; color: black;">
        <div style="padding-left: 10px; padding-top: 20px; padding-right: 10px;">
            <div style="font-size:0;position:relative;">
                <img src="{{ $package->barcodeImageBase64 }}" alt="Barcode Image" style="max-width: 100%;">
            </div>
        </div>
        <div style="text-align: center;font-family: Arial, Helvetica, sans-serif;">
            <div style="width: 100%; margin-top: 5px; font-size: 12px">{{ $package->waybill ?? 'N/A' }}</div>
        </div>
        <div style="padding-left: 20px; padding-top: 20px">
            <div id="labelsuite" style="width: 100%; margin-top: 0px; font-weight: bold; font-size: 12px">
                {{@$package->client_suite ?? 'N/A'}}
            </div>
            <div id="labelname" style="width: 100%; margin-top: 0px; font-weight: bold; font-size: 12px">
                {{ trim($package->first_name . ' ' . $package->last_name) ?: 'N/A' }}
            </div>
            <div id="labeldescription" style="width: 100%; margin-top: 0px; font-size: 10px">Descripción:
                {{ $package->description ?? 'N/A' }}
            </div>
            <div id="labeltracking" style="width: 100%; margin-top: 5px; font-size: 10px">
                {{$package->original_tracking ?? ''}}</div>
            <div style="width: 100%; margin-top: 5px; font-size: 10px">Peso: <span
                    id="labelkg">{{$package->grand_total ?? 0.0}}</span> Volumen:
                <span id="labelcbm">{{$package->kg ?? 0.0}}</span>
            </div>
        </div>
    </div>


</body>

</html>