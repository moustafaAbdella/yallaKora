<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Tajawal' rel='stylesheet'>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png')  }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')  }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'يلا جول') }}</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css')  }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css')  }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css')  }}" rel="stylesheet" />
    <!-- CSS Files -->
        @vite('resources/sass/app.scss')

    <link id="pagestyle" href="{{ asset('assets/css/alertify.rtl.css')  }}" rel="stylesheet" />

    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.7')  }}" rel="stylesheet" />
    <style>

    </style>
    <script src="{{ asset('assets/js/core/alertify.min.js')}}"></script>
        <!-- Scripts -->
        <script type="text/javascript">
            window.url = <?php echo json_encode(url('/')); ?>;
            window.csrf = <?php echo json_encode(csrf_token()); ?>;
        </script>
</head>
<body class="g-sidenav-show rtl  bg-gray-100">
  <script type="text/javascript">
    @if ($errors->any())
     @foreach ($errors->all() as $error)
     alertify.error('{{ $error }}');
     @endforeach
    @endif


	    @foreach (session('flash_notification', collect())->toArray() as $message)
          var level =  "{{ $message['level'] }}";
          var msg =  "{{ $message['message'] }}";
        if(level ==="success"){
          alertify.success(msg);
        }else if (level ==="danger"){
          alertify.error(msg);
        }else{
          alertify.success(msg);
        }
      console.log(level);
      console.log(msg);
	    @endforeach
      </script>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
  </form>

<div class="min-height-300 bg-primary position-absolute w-100"></div>
@include('layouts.sidebar')
<main class="main-content position-relative border-radius-lg overflow-hidden">
    @include('layouts.header')
    <div id="app" class="container-fluid py-4">
        @yield('content')
        @include('layouts.footer')
    </div>
</main>
<!-- Scripts -->
@vite('resources/js/app.js')
<!--<script src="{{ asset('js/app.js') }}"></script> -->
<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js')}}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js')}}"></script>
<!--  <script>
      var ctx1 = document.getElementById("chart-line").getContext("2d");
  
      var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
  
      gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
      gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
      gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
      new Chart(ctx1, {
        type: "line",
        data: {
          labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#5e72e4",
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6
  
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                padding: 10,
                color: '#fbfbfb',
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                color: '#ccc',
                padding: 20,
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
          },
        },
      });
    </script>
    <script>
      var win = navigator.platform.indexOf('Win') > -1;
      if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
          damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
      }
    </script> -->
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4')}}"></script>
</body>
</html>