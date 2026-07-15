<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Warehouse Receipt - {{ $package->waybill ?? $package->id }}</title>
    <style>
        @page { margin: 12mm 10mm; }
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        .bordered td, .bordered th, .box {
            border: 1px solid #222;
        }
        .pad { padding: 4px 6px; }
        .pad-sm { padding: 3px 5px; }
        .bold { font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { color: #444; }
        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.5px;
            margin: 4px 0 8px;
        }
        .section-label {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }
        .header-logo {
            max-height: 48px;
            max-width: 110px;
        }
        .org-name { font-size: 12px; font-weight: bold; }
        .org-meta { font-size: 8px; line-height: 1.35; }
        .mt-4 { margin-top: 6px; }
        .mt-8 { margin-top: 10px; }
        .legal {
            font-size: 7.5px;
            line-height: 1.35;
            text-align: justify;
        }
        .sig-line {
            border-top: 1px solid #222;
            margin-top: 28px;
            padding-top: 3px;
            width: 45%;
        }
        .no-top { border-top: none !important; }
        .val { min-height: 12px; }
        .w-50 { width: 50%; }
        .w-55 { width: 55%; }
        .w-45 { width: 45%; }
        .w-25 { width: 25%; }
        .w-20 { width: 20%; }
        .w-15 { width: 15%; }
        .w-12 { width: 12%; }
        .w-10 { width: 10%; }
        .w-8 { width: 8%; }
    </style>
</head>
@php
    $clientName = trim(($package->first_name ?? '') . ' ' . ($package->last_name ?? ''));
    if ($clientName === '' && !empty($package->client_name)) {
        $clientName = $package->client_name;
    }
    if ($clientName === '' && !empty($package->company_name)) {
        $clientName = $package->company_name;
    }
    $suite = $package->client_suite ?? $package->client_suite_resolved ?? '';
    $receiptNo = $package->waybill ?: ('PKG-' . $package->id);
    $created = !empty($package->created_at)
        ? \Carbon\Carbon::parse($package->created_at)->format('m/d/Y h:i A')
        : now()->format('m/d/Y h:i A');
    $status = strtoupper((string) ($package->status ?? 'OPEN'));
    $type = strtoupper((string) ($package->type ?? ''));
    $kg = is_numeric($package->kg ?? null) ? number_format((float) $package->kg, 2) : ($package->kg ?? '');
    $cbm = is_numeric($package->cbm ?? null) ? number_format((float) $package->cbm, 4) : ($package->cbm ?? '');
    $lbs = is_numeric($package->kg ?? null) ? number_format((float) $package->kg * 2.20462, 2) : '';
    $insured = !empty($package->is_insured) ? 'YES' : 'NO';
    $orgPhone = $orgPhones ?: ($organization->phone ?? '');
    $pieces = 1;
@endphp
<body>
    {{-- Header --}}
    <table>
        <tr>
            <td style="width:22%; vertical-align:middle;">
                @if(!empty($organization->logo_base64))
                    <img class="header-logo" src="{{ $organization->logo_base64 }}" alt="Logo">
                @endif
            </td>
            <td style="width:56%; vertical-align:middle;" class="center">
                <div class="org-name">{{ $organization->name ?? config('brand.name') }}</div>
                <div class="org-meta">
                    @if(!empty($organization->address)){{ $organization->address }}<br>@endif
                    @if(!empty($organization->postal_code)){{ $organization->postal_code }}<br>@endif
                    @if($orgPhone)Tel: {{ $orgPhone }}<br>@endif
                    @if(!empty($organization->email)){{ $organization->email }}@endif
                </div>
            </td>
            <td style="width:22%; vertical-align:top;" class="right muted">
                PAGE 1 OF 1
            </td>
        </tr>
    </table>

    <div class="title">Warehouse Receipt</div>

    <table class="bordered mt-4">
        <tr>
            <td class="pad w-50"><span class="bold">Date:</span> {{ $created }}</td>
            <td class="pad w-50 right"><span class="bold">Number:</span> {{ $receiptNo }}</td>
        </tr>
    </table>

    {{-- Shipper / W/R Status --}}
    <table class="bordered mt-4">
        <tr>
            <td class="section-label pad-sm w-55">Shipper</td>
            <td class="section-label pad-sm w-45">W/R Status Details</td>
        </tr>
        <tr>
            <td class="pad" style="vertical-align:top;">
                <div class="bold">{{ $organization->name ?? config('brand.name') }}</div>
                <div class="val">{{ $organization->address ?? '' }}</div>
                <div class="val">{{ $organization->postal_code ?? '' }}</div>
                <div class="val">{{ $organization->email ?? '' }}</div>
                @if($orgPhone)<div class="val">{{ $orgPhone }}</div>@endif
            </td>
            <td class="pad" style="vertical-align:top; padding:0;">
                <table>
                    <tr>
                        <td class="pad-sm bold" style="width:35%; border-bottom:1px solid #222; border-right:1px solid #222;">Status</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $status }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Customer Ref</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $suite }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Origin</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->origin_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Import Ref</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->original_tracking ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-right:1px solid #222;">Type / Service</td>
                        <td class="pad-sm">{{ $type }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Consignee / Charges --}}
    <table class="bordered mt-4">
        <tr>
            <td class="section-label pad-sm w-55">Consignee</td>
            <td class="section-label pad-sm w-45">Charges</td>
        </tr>
        <tr>
            <td class="pad" style="vertical-align:top;">
                <div class="bold">{{ $clientName ?: '—' }}</div>
                <div class="val">Suite: {{ $suite ?: '—' }}</div>
                <div class="val">{{ $package->client_address ?? '' }}</div>
                <div class="val">{{ $package->client_postal_code ?? '' }}</div>
                <div class="val">{{ $package->client_phone ?? '' }}</div>
                <div class="val">{{ $package->client_email ?? '' }}</div>
            </td>
            <td class="pad" style="vertical-align:top;">
                <table>
                    <tr>
                        <td class="pad-sm">Freight / Handling</td>
                        <td class="pad-sm right">{{ is_numeric($package->grand_total ?? null) ? '$'.number_format((float)$package->grand_total, 2) : ($package->grand_total ?? '—') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Delivery / Documents --}}
    <table class="bordered mt-4">
        <tr>
            <td class="section-label pad-sm w-55">Delivery</td>
            <td class="section-label pad-sm w-45">Documents</td>
        </tr>
        <tr>
            <td class="pad" style="vertical-align:top; padding:0;">
                <table>
                    <tr>
                        <td class="pad-sm bold" style="width:32%; border-bottom:1px solid #222; border-right:1px solid #222;">Delivered by</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->origin_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Tracking / VIN #</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->original_tracking ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Waybill</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->waybill ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Description</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->description ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-right:1px solid #222;">Received by</td>
                        <td class="pad-sm">{{ $organization->name ?? '' }}</td>
                    </tr>
                </table>
            </td>
            <td class="pad" style="vertical-align:top; padding:0;">
                <table>
                    <tr>
                        <td class="pad-sm bold" style="width:40%; border-bottom:1px solid #222; border-right:1px solid #222;">Value</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ is_numeric($package->grand_total ?? null) ? number_format((float)$package->grand_total, 2) : '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Inv #</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">{{ $package->waybill ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">Hazardous</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;">NO</td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-bottom:1px solid #222; border-right:1px solid #222;">U.N. Number</td>
                        <td class="pad-sm" style="border-bottom:1px solid #222;"></td>
                    </tr>
                    <tr>
                        <td class="pad-sm bold" style="border-right:1px solid #222;">Insurance</td>
                        <td class="pad-sm">{{ $insured }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Reference row --}}
    <table class="bordered mt-4">
        <tr>
            <td class="pad w-50"><span class="bold">P.O. # / Suite:</span> {{ $suite }}</td>
            <td class="pad w-50"><span class="bold">INSURANCE:</span> {{ $insured }}</td>
        </tr>
    </table>

    {{-- Items table --}}
    <table class="bordered mt-8">
        <thead>
            <tr class="section-label">
                <th class="pad-sm center w-8">Pieces</th>
                <th class="pad-sm center w-12">Type</th>
                <th class="pad-sm center w-12">Unit Wt (KG)</th>
                <th class="pad-sm center w-15">Dimensions</th>
                <th class="pad-sm center w-12">Volume (CBM)</th>
                <th class="pad-sm center w-15">Total Wt (KG)</th>
                <th class="pad-sm center w-15">Total Wt (LBS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="pad-sm center">{{ $pieces }}</td>
                <td class="pad-sm center">{{ $type ?: 'PKG' }}</td>
                <td class="pad-sm center">{{ $kg }}</td>
                <td class="pad-sm center">—</td>
                <td class="pad-sm center">{{ $cbm }}</td>
                <td class="pad-sm center">{{ $kg }}</td>
                <td class="pad-sm center">{{ $lbs }}</td>
            </tr>
            <tr>
                <td class="pad" colspan="7">
                    <span class="bold">Description:</span>
                    {{ $package->description ?? '' }}
                    @if(!empty($package->original_tracking))
                        <br><span class="muted">(Tracking: {{ $package->original_tracking }} | Waybill: {{ $package->waybill ?? '' }})</span>
                    @endif
                </td>
            </tr>
            <tr class="section-label">
                <td class="pad-sm center bold">{{ $pieces }} PC</td>
                <td class="pad-sm" colspan="3"></td>
                <td class="pad-sm center bold">{{ $cbm }}</td>
                <td class="pad-sm center bold">{{ $kg }} KG</td>
                <td class="pad-sm center bold">{{ $lbs }} LBS</td>
            </tr>
        </tbody>
    </table>

    {{-- Legal --}}
    <table class="bordered mt-8">
        <tr>
            <td class="pad legal">
                The goods described herein are received in apparent good order except as noted. This Warehouse Receipt
                confirms custody of the merchandise by {{ $organization->name ?? config('brand.name') }} subject to the
                applicable terms and conditions of service. Merchandise left over thirty (30) days may be subject to
                storage charges. Claims must be reported within the timeframe required by the carrier/warehouse terms.
                Unauthorized removal or alteration of this document is prohibited.
            </td>
        </tr>
    </table>

    <div class="sig-line">
        Authorized Signature
    </div>

    <div class="mt-8 right muted">PAGE 1 OF 1</div>
</body>
</html>
