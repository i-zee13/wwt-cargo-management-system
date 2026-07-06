@extends('layouts.master')
@section('content')
<style>
    a {
        text-decoration: none;
    }

    .btn-outline-primary {
        color: #333;
    }

    .anchor-div a {
        text-align: left !important;
        line-height: 1.5;
        text-decoration: none !important;
    }

    .dashboard-date {
        width: 250px;
        float: left;
    }

    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
        margin: 0 auto;
        max-width: 1200px;
    }

    .custom-card {
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #ffffff, #f1f1f1);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        flex: 1 1 calc(33.333% - 20px);
        /* Three cards per row */
        max-width: calc(50% - 20px);
    }

    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    }

    .custom-card-icon {
        font-size: 3rem;
        color: #fff;
        padding: 15px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .icon-blue {
        background-color: #eb973c;
    }

    .icon-lightblue {
        background-color: #17a2b8;
    }

    .icon-red {
        background-color: #dc3545;
    }

    .custom-card-content {
        flex: 1;
    }

    .custom-card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: grey;
        margin-bottom: 5px;
    }

    .custom-card-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
    }

    @media (max-width: 768px) {
        .custom-card {
            flex: 1 1 100%;
            /* Full width on mobile */
            max-width: 100%;
        }

        .card-container {
            gap: 15px;
        }
    }
</style>
<div class="row ">
    <div class="col">
        <input type="text" autocomplete="off" name="date" class="date dashboard-date form-control" id="date"
            placeholder="Select Date *" value="">
    </div>
</div>
<div class="card-container mt-4">
    <!-- Card 1 -->
    <a class="custom-card setDateToSession" href="/admin/clients">

        <div class="custom-card-icon icon-blue">
            <i class="bi bi-person-check"></i>
        </div>
        <div class="custom-card-content">
            <div class="custom-card-number dashboard-packages-clients">--</div>
            <div class="custom-card-title">{{ __('fields.active_clients') }}</div>
        </div>
    </a>
    <a class="custom-card setDateToSession" href="/admin/packages/in-progress">

        <div class="custom-card-icon icon-blue">
            <i class="bi bi-bounding-box"></i>
        </div>
        <div class="custom-card-content">
            <div class="custom-card-number dashboard-packages-in-progress">--</div>
            <div class="custom-card-title">{{ __('fields.package_processing') }}</div>
        </div>
    </a>
    <a class="custom-card setDateToSession" href="/admin/packages/package_today">

        <div class="custom-card-icon icon-blue">
            <i class="bi bi-plus-square"></i>
        </div>
        <div class="custom-card-content">
            <div class="custom-card-number dashboard-packages-create">--</div>
            <div class="custom-card-title">{{ __('fields.package_today') }}</div>
        </div>
    </a>
    <a class="custom-card setDateToSession" href="/admin/packages/retired">
        <div class="custom-card-icon icon-blue">
            <i class="bi bi-box"></i>
        </div>
        <div class="custom-card-content">
            <div class="custom-card-number dashboard-packages-retired">--</div>
            <div class="custom-card-title">{{ __('fields.packages_retired_today') }}</div>
        </div>
    </a>
    <div class="custom-card">
        <div class="custom-card-icon icon-blue">
            <i class="bi bi-arrow-bar-right"></i>
        </div>
        <div class="custom-card-content">
            <div class="custom-card-number dashboard-delivered-packages-weight">--</div>
            <div class="custom-card-title">{{ __('fields.kilo_processed') }}</div>
        </div>
    </div>
</div>
{{-- DEMO: Quick Actions hidden — uncomment block below after demo --}}
{{--
<div class="row d-flex justify-content-center mt-3">
    <div class="col-11 text-start">
        <div class="card shadow-lg p-4 text-white"
            style="  background: linear-gradient(135deg, #ffffff, #f1f1f1); border-radius: var(--border-radius); overflow: hidden;">
            <h5 class="card-title fw-bold text-dark">{{ __('fields.quick_actions') }}</h5>
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                <a href="{{ route('create-package') }}"
                    class="btn btn-outline-primary   btn-lg">{{ __('fields.create_package') }}</a>
                <a href="{{ route('create-origin') }}"
                    class="btn btn-outline-primary   btn-lg">{{ __('fields.create_origin') }}</a>
                <a href="/admin/freight-rates"
                    class="btn btn-outline-primary   btn-lg">{{ __('fields.create_rate') }}</a>
                <a href="/admin/print-packages-label"
                    class="btn btn-outline-primary   btn-lg">{{ __('fields.print_label') }}</a>
                <a href="/admin/package-tracking"
                    class="btn btn-outline-primary   btn-lg">{{ __('fields.update_package_status') }}</a>
            </div>
        </div>
    </div>
</div>
--}}

@endsection
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
@push('js')
    <script>
        $(document).ready(function () {
            let carbonDate = "{{ $todayDate }}";
            $(".date").datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
            })
                .datepicker("setDate", carbonDate)
                .on("changeDate", function (e) {
                    $(this).datepicker("hide");
                });
        });
        $(document).on('click', '.setDateToSession', function (e) {
            e.preventDefault();
            let date = $('.dashboard-date').val();
            sessionStorage.setItem('selectedDate', date);
            console.log(sessionStorage.getItem('selectedDate'), 'date');
            window.location.href = $(this).attr('href');
        });
        $('.dashboard-date').on('change', function () {
            let date = $(this).val();
            if (date) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/home',
                    data: {
                        date: date,
                    },
                    success: function (response) {
                        console.log(response);
                        $('.dashboard-packages-create').text(response.packagesCreated);
                        $('.dashboard-delivered-packages-weight').text(response.deliveredPackagesWeight);
                        $('.dashboard-packages-retired').text(response.packagesDelivered);
                        $('.dashboard-packages-in-progress').text(response.packagesProcessing);
                        $('.dashboard-packages-clients').text(response.activeClients);
                    }
                });
            }
        });
    </script>
@endpush