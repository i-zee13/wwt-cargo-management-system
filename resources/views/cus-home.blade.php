@extends('layouts.master')
@section('content')
<style>
    .anchor-div a {
        text-align: left !important;
        line-height: 1.5;
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
      flex: 1 1 calc(33.333% - 20px); /* Three cards per row */
      max-width: calc(33.333% - 20px);
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
      background-color: #0056b3;
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
      color: #333;
      margin-bottom: 5px;
    }

    .custom-card-number {
      font-size: 2.5rem;
      font-weight: bold;
      color: #333;
    }

    @media (max-width: 768px) {
      .custom-card {
        flex: 1 1 100%; /* Full width on mobile */
        max-width: 100%;
      }

      .card-container {
        gap: 15px;
      }
    }
  

.flash-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    padding: 15px 30px;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: bold;
    color: var(--bs-white);
    background: linear-gradient(135deg, black, #333);
    box-shadow: 0px 8px 20px var(--shadow-rgba);
    display: flex;
    align-items: center;
    gap: 10px;
    animation: bounce-in 1s ease, fade-out 1s ease 9s forwards;
    transition: transform 0.3s ease;
}

.flash-message i {
    font-size: 1.5rem;
    animation: pulse 1.5s infinite; /* Pulsating icon */
}

.flash-message span {
    font-size: 1.2rem;
}

@keyframes bounce-in {
    0% {
        transform: translateX(-50%) translateY(-100px) scale(0.5);
        opacity: 0;
    }
    50% {
        transform: translateX(-50%) translateY(20px) scale(1.2);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) translateY(0) scale(1);
    }
}

@keyframes fade-out {
    from {
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }
    to {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px) scale(0.8);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
}

</style>
@if (session('success'))
    <div id="flash-message" class="flash-message">
    <i class="bi bi-emoji-smile"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif 
<div class="card-container">
    <!-- Card 1 -->
    <div class="custom-card">
      <div class="custom-card-icon icon-blue">
        <i class="bi bi-building"></i>
      </div>
      <div class="custom-card-content">
        <div class="custom-card-number">{{@$packagesReceived}}</div>
        <div class="custom-card-title">{{__('fields.package_status_received')}}</div>
      </div>
    </div>
    <!-- Card 2 -->
    <div class="custom-card">
      <div class="custom-card-icon icon-lightblue">
        <i class="bi bi-airplane"></i>
      </div>
      <div class="custom-card-content">
        <div class="custom-card-number">{{@$packagesProgress}}</div>
        <div class="custom-card-title">{{__('fields.package_status_in_progress')}}</div>
      </div>
    </div>
    <!-- Card 3 -->
    <div class="custom-card">
      <div class="custom-card-icon icon-red">
        <i class="bi bi-truck"></i>
      </div>
      <div class="custom-card-content">
        <div class="custom-card-number">{{@$packagesArrived}}</div>
        <div class="custom-card-title">{{__('fields.package_status_arrived')}}</div>
      </div>
    </div>
  </div>
<div class="container my-5">
    <div class="row g-4">
        @foreach($origins as $org)
        <div class="col-sm-6 col-md-4">
            <div class="card card-accent-primary">
                <div class="card-header " style="padding-top: 10px; padding-bottom: 10px;"><img src="https://flagcdn.com/{{strtolower($org->iso)}}.svg" alt="" style="width: 30px; height: auto; display: block;"></div>
                <div class="card-body" style="line-height: 1;">
                    <h5>{{$org->origin_name}}</h5>
                    <p>{{GetActiveGuardDetail()->suite}} {{GetActiveGuardDetail()->first_name}}</p>
                    <p>{{$org->address}}</p>
                    <p></p>
                    <p>{{$org->city_name}}, {{$org->state_name}}, {{$org->zip_code}}</p>
                    <p>{{$org->phone}}</p>
                </div>
            </div>
        </div> 

        @endforeach
        <!-- Customers Registered Today Card -->
        <!-- <div class="col-md-4">
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
        </div> -->

        <!-- Packages Created Today Card -->
        <!-- <div class="col-md-4">
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
        </div> -->

        <!-- Packages Retired Today Card -->
        <!-- <div class="col-md-4">
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
        </div> -->
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">