<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
       :root {
    --bs-font-sans-serif: "Poppins", sans-serif;
    --bs-body-font-family: "Poppins", sans-serif;
    --bs-body-font-size: 0.875rem;
    --bs-body-font-weight: 300;
    --bs-body-line-height: 1.5;
    --bs-body-color: #242424;
    --bs-body-bg: #f6f6f6;
    --bs-primary: #eb973c;
    --bs-primary-rgb: rgb(200, 198, 57);
    --bs-secondary: #d4842e;
    --bs-secondary-rgb: rgb(181, 148, 19);
    --shadow-rgba: rgba(0, 0, 0, 0.15);
    --bs-white: #fff;
    --border-radius: 0.75rem;
}

body {
    background-color: #f6f6f6;
    font-family: var(--bs-font-sans-serif);
    color: var(--bs-body-color);
}

.container {
    max-width: 900px;
    margin: 30px auto;
    padding: 20px;
    background-color: var(--bs-white);
    border-radius: var(--border-radius);
    box-shadow: 0 10px 20px var(--shadow-rgba);
    transition: all 0.3s ease;
}

.container:hover {
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
}

h2 {
    color: grey;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
    font-size: 20px;
}

.detail {
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    font-size: 1rem;
    color: #333;
}

.detail strong {
    color: var(--bs-primary);
    font-weight: 500;
}

.detail span {
    color: #555;
}

.status {
    font-size: 1.2rem;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: var(--border-radius);
    text-transform: capitalize;
    display: inline-block;
}

.status.received {
    background-color: #28a745;
    color: #fff;
}

.status.embarked {
    background-color: #ffc107;
    color: #333;
}

.status['in-progress'] {
    background-color: #17a2b8;
    color: #fff;
}

.status.arrived {
    background-color: #007bff;
    color: #fff;
}

.status.retired {
    background-color: #6c757d;
    color: #fff;
}

.package-details {
    padding: 20px;
    background: linear-gradient(135deg, #fff, #f7f7f7);
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    transition: transform 0.3s ease;
}

.package-details:hover {
    transform: translateY(-5px);
}

.no-details {
    font-size: 1.5rem;
    font-weight: bold;
    color: #dc3545;
    text-align: center;
}

@media (max-width: 768px) {
    .detail {
        display: block;
    }

    .detail span {
        display: block;
        margin-top: 5px;
    }
}

    </style>
</head>
<body>

<div class="container">
    @if ($details)
        <div class="package-details">
            <h2>Package Details for Waybill: {{ $details->waybill }}</h2>

            <div class="detail"><strong>Client Suite:</strong> {{ $details->client_suite }}</div>
            <div class="detail"><strong>Client Name:</strong> {{ $details->first_name }} {{ $details->last_name }}</div>
            <div class="detail"><strong>Package Description:</strong> {{ $details->description }}</div>
            <div class="detail"><strong>Weight (kg):</strong> {{ $details->kg }}</div>
            <div class="detail"><strong>CBM:</strong> {{ $details->cbm }}</div>
            <div class="detail"><strong>Grand Total:</strong> {{ $details->grand_total }}</div> 
            <div class="detail">
                <strong>Status:</strong> 
                <span class="status {{ strtolower($details->status) }}">{{ ucfirst($details->status) }}</span>
            </div>
        </div>
    @else
        <div class="package-details">
            <h2 class="no-details">No Package Details Found</h2>
        </div>
    @endif
</div>

</body>
</html>
