@extends('layouts/contentNavbarLayout')

@section('title', trns("Dashboard"))

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
<style>
  .layout-page{
  overflow-x: hidden !important;
}
.layout-wrapper{
  overflow-x: hidden !important;
}
</style>
<div class="row">
  <div class="col-lg-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
                        <div class="card-body  rounded-3 shadow-sm p-4">
                <h5 class="card-title text-primary fw-bold mb-4">
                    👋 {{ trns('Welcome') }} : {{ $userProvider->user_name }} ❤️
                </h5>

                <!-- Temperature -->
                <div class="mb-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="orange" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 4.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"/>
                        <path d="M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zM8 13.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5zM16 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 16 8zM2.5 8a.5.5 0 0 1-.5.5H0a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5z"/>
                    </svg>
                    <span class="fw-semibold text-warning">{{ trns('Temperature:') }}</span>
                    <span class="ms-2 text-dark">{{ $data['temp'] }} °C</span>
                </div>

                <!-- Pressure -->
                <div class="mb-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 3a5 5 0 1 0 0 10A5 5 0 0 0 8 3zm0 1a4 4 0 0 1 4 4c0 .82-.25 1.58-.68 2.21l-2.5-2.5a.5.5 0 0 0-.71.71l2.5 2.5A3.98 3.98 0 0 1 8 12a4 4 0 1 1 0-8z"/>
                    </svg>
                    <span class="fw-semibold text-danger">{{ trns('Pressure:') }}</span>
                    <span class="ms-2 text-dark">{{ $data['pressure'] }} hPa</span>
                </div>

                <!-- Humidity -->
                <div class="mb-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="blue" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 0C5 4 2 7 2 10a6 6 0 1 0 12 0c0-3-3-6-6-10zM4 10a4 4 0 0 1 8 0A4 4 0 0 1 4 10z"/>
                    </svg>
                    <span class="fw-semibold text-info">{{ trns('Humidity:') }}</span>
                    <span class="ms-2 text-dark">{{ $data['humidity'] }} %</span>
                </div>

                <!-- Wind Speed -->
                <div class="mb-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="teal" class="me-2" viewBox="0 0 16 16">
                        <path d="M8.5 2A1.5 1.5 0 1 1 10 3.5H1a.5.5 0 0 0 0 1h9A2.5 2.5 0 1 0 7.5 2h1zM13 6a2 2 0 1 1-2 2H1a.5.5 0 0 0 0 1h10a3 3 0 1 0 2-3zM6 10a2 2 0 1 1-2 2H1a.5.5 0 0 0 0 1h3a3 3 0 1 0 2-3z"/>
                    </svg>
                    <span class="fw-semibold text-success">{{ trns('Wind Speed:') }}</span>
                    <span class="ms-2 text-dark">{{ $data['wind_speed'] }} km/h</span>
                </div>
            </div>

          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="{{ trns("View Badge User") }}" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                 <i class="bx bx-group text-primary" style="font-size: 1.75rem;"></i>
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                  <a class="dropdown-item" href="{{route('users.index')}}">{{ trns("View More") }}</a>
                </div>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">{{ trns("users") }}</span>
            <h3 class="card-title mb-2">{{$usersCount}}</h3>
          </div>  
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <img class="rounded" src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="{{ trns("View Badge User") }}" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                  <a class="dropdown-item" href="{{route('admins.index')}}">{{ trns("View More") }}</a>
                </div>
              </div>
            </div>
            <span>{{ trns("admins") }}</span>
            <h3 class="card-title text-nowrap mb-1">{{$adminsCount}}</h3>
          </div>
        </div>
      </div>
      
    </div>
  </div>
{{-- 
  <!-- Total Revenue -->
  <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-md-8">
          <h5 class="card-header m-0 me-2 pb-3">{{ trns("Total Revenue") }}</h5>
          <div id="totalRevenueChart" class="px-2"></div>
        </div>
        <div class="col-md-4">
          <div class="card-body">
            <div class="text-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  2022
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                  <a class="dropdown-item" href="javascript:void(0);">2021</a>
                  <a class="dropdown-item" href="javascript:void(0);">2020</a>
                  <a class="dropdown-item" href="javascript:void(0);">2019</a>
                </div>
              </div>
            </div>
          </div>
          <div id="growthChart"></div>
          <div class="text-center fw-medium pt-3 mb-2">62% {{ trns("Company Growth") }}</div>

          <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
            <div class="d-flex">
              <div class="me-2">
                <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
              </div>
              <div class="d-flex flex-column">
                <small>2022</small>
                <h6 class="mb-0">$32.5k</h6>
              </div>
            </div>
            <div class="d-flex">
              <div class="me-2">
                <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
              </div>
              <div class="d-flex flex-column">
                <small>2021</small>
                <h6 class="mb-0">$41.2k</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  <!--/ Total Revenue -->
  <div class="col-12 w-100 order-3 order-md-2">
    <div class="row w-100">
  <!-- Teachers -->
  <div class="col-3 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class="bx bx-chalkboard" style="font-size: 1.75rem;"></i>
          </div>
          <div class="dropdown-menu-end" >
              {{-- <a class="dropdown-item" href="{{route('teachers.index')}}">{{ trns("View More") }}</a> --}}
            </div>
        </div>
        <span>{{ trns("teachers") }}</span>
        {{-- <h3 class="card-title text-nowrap mb-1">{{$teachersCount}}</h3> --}}
      </div>
    </div>
  </div>

  <!-- Families -->
  <div class="col-3 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class="bx bx-user" style="font-size: 1.75rem;"></i>
          </div>
          <div class=" dropdown-menu-end">
              {{-- <a class="dropdown-item" href="{{route('familys.index')}}">{{ trns("View More") }}</a> --}}
            </div>
        </div>
        <span>{{ trns("families") }}</span>
        {{-- <h3 class="card-title text-nowrap mb-1">{{$familiesCount}}</h3> --}}
      </div>
    </div>
  </div>

  <!-- Payments -->
  <div class="col-3 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="Payments" class="rounded">
          </div>
          <div class=" dropdown-menu-end">
              {{-- <a class="dropdown-item" href="{{route('orders.index')}}">{{ trns("View More") }}</a> --}}
            </div>
        </div>
        <span class="d-block mb-1">{{ trns("Payments") }}</span>
        {{-- <h3 class="card-title text-nowrap mb-2">{{ $paymentsCount }}</h3> --}}
      </div>
    </div>
  </div>

  <!-- Transactions -->
  <div class="col-3 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="Transactions" class="rounded">
          </div>
          <div class=" dropdown-menu-end">
              {{-- <a class="dropdown-item" href="{{route('transactions.index')}}">{{ trns("View More") }}</a> --}}
            </div>
        </div>
        <span class="fw-semibold d-block mb-1">{{ trns("Transactions") }}</span>
        {{-- <h3 class="card-title mb-2">{{ $transactionsCount }}</h3> --}}
      </div>
    </div>
  </div>
</div>


    </div>
  </div>
</div>
<div class="row" @if(app()->getLocale() == 'ar') style="margin-top: 25rem;" @endif>
  <!-- Order Statistics -->
<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">{{ trns("Order Statistics") }}</h5>
        {{-- <small class="text-muted">{{ $ordersCount }} {{ trns("Total Sales") }}</small> --}}
      </div>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column align-items-center gap-1">
          {{-- <h2 class="mb-2">{{ $ordersCompletedCount }}</h2> --}}
          <span>{{ trns("Total Orders completed") }}</span>
        </div>
        <canvas id="ordersChart" style="max-width: 150px; max-height: 150px;"></canvas>

      </div>

      <ul class="p-0 m-0">
        <!-- Pending -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary">
              <i class='bx bx-time-five'></i>

            </span>
          </div>
          <div class="d-flex w-100 align-items-center justify-content-between flex-nowrap gap-2">
            <div class="me-2 flex-grow-1 text-truncate">
              <h6 class="mb-0">{{ trns("pending") }}</h6>
              <small class="text-muted">{{ trns("orders waiting for payment") }}</small>
            </div>
            <div class="user-progress flex-shrink-0">
              {{-- <small class="fw-medium">{{ $ordersPendingCount }}</small> --}}
            </div>
          </div>
        </li>

        <!-- Refunded -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success">
              <i class='bx bx-undo'></i>

            </span>
          </div>
          <div class="d-flex w-100 align-items-center justify-content-between flex-nowrap gap-2">
            <div class="me-2 flex-grow-1 text-truncate">
              <h6 class="mb-0">{{ trns("refunded") }}</h6>
              <small class="text-muted">{{ trns("orders that have been refunded") }}</small>
            </div>
            <div class="user-progress flex-shrink-0">
              {{-- <small class="fw-medium">{{ $ordersRefundedCount }}</small> --}}
            </div>
          </div>
        </li>

        <!-- Cancelled -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-x-circle'></i>
</span>
          </div>
          <div class="d-flex w-100 align-items-center justify-content-between flex-nowrap gap-2">
            <div class="me-2 flex-grow-1 text-truncate">
              <h6 class="mb-0">{{ trns("cancelled") }}</h6>
              <small class="text-muted">{{ trns("orders that have been cancelled") }}</small>
            </div>
            <div class="user-progress flex-shrink-0">
              {{-- <small class="fw-medium">{{ $ordersCancelledCount }}</small> --}}
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!--/ Order Statistics -->

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('ordersChart').getContext('2d');

    const data = {
        labels: ['Pending', 'Completed', 'Refunded', 'Cancelled'],
        datasets: [{
            data: [
                {{ $ordersPendingCount }},
                {{ $ordersCompletedCount }},
                {{ $ordersRefundedCount }},
                {{ $ordersCancelledCount }}
            ],
            backgroundColor: [
                '#ffc107', // Pending - warning
                '#28a745', // Completed - success
                '#17a2b8', // Refunded - info
                '#6c757d'  // Cancelled - secondary
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 10 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' orders';
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
});
</script> --}}


<!-- Expense Overview -->

<!--/ Expense Overview -->



  <!-- Transactions -->
  <!-- Transactions -->
<div class="col-md-6 col-lg-4 order-2 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">{{ trns("orders") }}</h5>
      
    </div>

    <div class="card-body">
      <!-- Single icon at the top -->
      <div class="d-flex p-4 pt-3">
        <div class="avatar flex-shrink-0 me-3">
          <img src="{{ asset('assets/img/icons/unicons/cc-success.png') }}" alt="Transactions" class="rounded">
        </div>
        <div>
          <small class="text-muted d-block">{{ trns('Last 5 Orders') }}</small>
        </div>
      </div>

      <ul class="list-unstyled mb-0">
         {{-- @foreach($orders5 as $order)
              @php
                  $status = $order->status->value ?? 'N/A';
              @endphp
              <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                <div>
                  <h6 class="mb-0">{{ $order->student?->name ?? trns('Unknown') }}</h6>
                  <small class="text-muted">#{{ $order->transaction_id ?? trns('N/A') }}</small>
                </div>

                <div class="text-end">
                  <h6 class="mb-0">${{ number_format($order->amount, 2) }}</h6>
                  <small class="badge 
                      @if($status == 'pending') bg-warning 
                      @elseif($status == 'success') bg-success 
                      @elseif($status == 'cancelled') bg-danger 
                      @elseif($status == 'refunded') bg-info 
                      @else bg-secondary @endif">
                      {{ ucfirst($status) }}
                  </small>
                </div>
              </li>
            @endforeach --}}
      </ul>
    </div>
  </div>
</div>




  <!-- Transactions -->
  <!-- Transactions -->
<div class="col-md-6 col-lg-4 order-2 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">{{ trns("Transactions") }}</h5>
      
    </div>

    <div class="card-body">
      <!-- Single icon at the top -->
      <div class="d-flex p-4 pt-3">
        <div class="avatar flex-shrink-0 me-3">
          <img src="{{ asset('assets/img/icons/unicons/cc-success.png') }}" alt="Transactions" class="rounded">
        </div>
        <div>
          <small class="text-muted d-block">{{ trns('Last 5 Transactions') }}</small>
        </div>
      </div>

      <ul class="list-unstyled mb-0">
        {{-- @foreach($transaction as $tran)
          <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
            <div>
              <h6 class="mb-0">{{ $tran->teacher?->name ?? trns('Teacher') }}</h6>
              <small class="text-muted">{{ trns("Transaction ID:") }} {{ $tran->id }}</small>
            </div>
            <div class="text-end">
              <h6 class="mb-0">${{ number_format($tran->total, 2) }}</h6>
              <span class="text-muted">USD</span>
            </div>
          </li>
        @endforeach --}}
      </ul>
    </div>
  </div>
</div>


  <!--/ Transactions -->
</div>
@endsection
