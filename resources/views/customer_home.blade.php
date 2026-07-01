@extends('layouts.master')
@section('content')
<style>
.anchor-div a{
    text-align: left!important;
    line-height: 1.5;
}


</style>
<div class="container my-5">
    <div class="row g-4">
        <!-- Customers Registered Today Card -->
        <div class="col-md-4">
            <div class="card text-white" style="background: linear-gradient(to right, #ffb75e, #ed8f03); border-radius: var(--border-radius); transition: transform 0.3s ease;">
                <div class="card-body d-flex align-items-center p-4">
                    <svg width="50" height="50" fill="currentColor" class="bi bi-person-check me-3" viewBox="0 0 16 16">
                        <path d="M15.854 5.146a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708l1.146 1.147 3.146-3.147a.5.5 0 0 1 .708 0z"/>
                        <path d="M1 13s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm11-1c-.235-.479-1.445-2-5-2-3.5 0-4.757 1.481-5 2h10z"/>
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    <div>
                        <h5 class="card-title" style="font-weight: 600;">{{ __('fields.customers_today') }}</h5>
                        <p class="display-5" style="font-size: 2.5rem;">{{ @$totalCustomersToday ??0}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Packages Created Today Card -->
        <div class="col-md-4">
            <div class="card text-white" style="background: linear-gradient(to right, #141e30, #243b55); border-radius: var(--border-radius); transition: transform 0.3s ease;">
                <div class="card-body d-flex align-items-center p-4">
                    <svg width="50" height="50" fill="currentColor" class="bi bi-box-seam me-3" viewBox="0 0 16 16">
                        <path d="M8.354.146a.5.5 0 0 1 .292 0l6.5 3a.5.5 0 0 1 0 .908L8 8.438 1.854 4.054a.5.5 0 0 1 0-.908l6.5-3z"/>
                        <path d="M.5 4.375v7.28c0 .33.232.636.58.72l6.5 1.5a.5.5 0 0 0 .34 0l6.5-1.5a.75.75 0 0 0 .58-.72v-7.28l-7 4.06-7-4.06z"/>
                    </svg>
                    <div>
                        <h5 class="card-title" style="font-weight: 600;">{{ __('fields.package_today') }}</h5>
                        <p class="display-5" style="font-size: 2.5rem;">{{ @$totalPackagesCreatedToday??0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Packages Retired Today Card -->
        <div class="col-md-4">
            <div class="card text-white" style="background: linear-gradient(to right, #56ab2f, #a8e063);; border-radius: var(--border-radius); transition: transform 0.3s ease;">
                <div class="card-body d-flex align-items-center p-4">
                    <svg width="50" height="50" fill="currentColor" class="bi bi-box-arrow-down me-3" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.5 3a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5V8h-1V3.5H4v8.1l-.547.073L2.5 12.1v-9.1H3.5zm4.854 5.854a.5.5 0 0 1-.708 0L6 7.707V12.5a.5.5 0 0 1-1 0V7.707l-1.646 1.647a.5.5 0 0 1-.708-.708l2.5-2.5a.5.5 0 0 1 .708 0l2.5 2.5a.5.5 0 0 1 0 .708z"/>
                    </svg>
                    <div>
                        <h5 class="card-title" style="font-weight: 600;">{{ __('fields.package_retire') }}</h5>
                        <p class="display-5" style="font-size: 2.5rem;">{{ @$totalPackagesRetiredToday??0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row d-flex justify-content-center">
        <div class="col-11 text-start">
            <div class="card shadow-lg p-4 text-white" style="background: linear-gradient(135deg, #007bff, #00c6ff); border-radius: var(--border-radius);">
                <h5 class="card-title" style="font-weight: 600;">{{ __('fields.quick_actions') }}</h5>
                <div class="d-flex flex-wrap gap-3 mt-3 anchor-div">
                <a href="{{ route('create-package') }}" class="btn btn-outline-light btn-lg">{{ __('fields.create_package') }}</a>
                    <a href="{{ route('create-origin') }}" class="btn btn-outline-light btn-lg">{{ __('fields.create_origin') }}</a>
                    <a href="/admin/freight-rates" class="btn btn-outline-light btn-lg">{{ __('fields.create_rate') }}</a>
                    <a href="/admin/print-packages-label" class="btn btn-outline-light btn-lg">{{ __('fields.print_label') }}</a>
                    <a href="/admin/package-tracking" class="btn btn-outline-light btn-lg">{{ __('fields.update_package_status') }}</a>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: scale(1.05);
    }
    .btn-lg {
        padding: 0.5rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }
</style>
        @endsection