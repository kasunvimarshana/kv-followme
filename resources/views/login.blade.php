<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ config('app.name', 'Title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('node_modules/admin-lte/plugins/iCheck/square/blue.css') }}"/>
    @includeIf('partials.main_stylesheet', array())
    <!-- custom fonts -->
    <link rel="stylesheet" href="{{ asset('css/custom_fonts.css') }}"/>
    <!-- custom stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/custom_style_login.css') }}"/>
    <!-- style></style -->
    <!-- Scripts -->
    <!-- script src="{{ asset('js/app.js') }}" defer></script -->
    <!-- REQUIRED JS SCRIPTS -->
    @includeIf('partials.main_script', array())
    <!-- REQUIRED JS SCRIPTS -->
</head>
<body class="hold-transition login-page">
    <div id="particles-js"></div>
<div class="login-box">
    <!-- --- -->
  <!-- div class="login-logo" -->
    <!-- a href="{{ url('/') }}"><b>Follow </b>Me</a -->
    <!-- a class="logo-link" href="{{ url('/') }}" -->
        <!-- img class="img img-responsive logo-img" src="{!! asset('img/logo_1.png') !!}"/ -->
        <!-- p style="color: white;"> Event Tracker </p -->
        <!-- p class="logo-text"> Event Tracker </p -->
    <!-- /a -->
  <!-- /div -->
    <!-- --- -->
    <!-- --- -->
    <div class="login-logo">
        <!-- a href="{{ url('/') }}"><b>Follow </b>Me</a -->
        <a class="logo-link" href="{{ url('/') }}">
            <p style="color: rgba(72, 61, 139, 1);"> Follo<span style="color: rgba(139, 61, 72, 1);">w</span>Me </p>
            <p class="logo-text"> Event Tracker </p>
        </a>
    </div>
    <!-- --- -->
  <!-- /.login-logo -->
  <div class="login-box-body">
    <!-- p class="login-box-msg">Sign in to start your session</p -->

    <form action="{{ url('login') }}" method="POST" autocomplete="off">
      <!-- {{ csrf_field() }} || {{ Session::token() }} -->
      @csrf
      <div class="form-group has-feedback">
        <input type="text" name="email" class="form-control" placeholder="User ID" value="{{ old('email') }}" required autocomplete="off"/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required autocomplete="off"/>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div -->
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- social-auth-links -->
    <!-- div class="social-auth-links text-center">
    </div -->
    <!-- /.social-auth-links -->

    <!-- a href="{{ url('/') }}">I forgot my password</a><br -->
    <!-- a href="{{ url('/') }}" class="text-center">Register a new membership</a -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- REQUIRED JS SCRIPTS -->
<!-- REQUIRED JS SCRIPTS -->
<!-- iCheck -->
<!-- script src="{{ asset('node_modules/admin-lte/plugins/iCheck/icheck.min.js') }}"></script -->
<!-- script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script -->
<!-- Particle-js -->
<script src="{{ asset('node_modules/particles.js/particles.js') }}"></script>
<script>
// ParticlesJS Config.
particlesJS("particles-js", {
  "particles": {
    "number": {
      "value": 80,
      "density": {
        "enable": true,
        "value_area": 700
      }
    },
    "color": {
      "value": "#ffffff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
    },
    "opacity": {
      "value": 0.5,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 3,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#ffffff",
      "opacity": 0.4,
      "width": 1
    },
    "move": {
      "enable": true,
      "speed": 6,
      "direction": "none",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": true,
        "mode": "grab"
      },
      "onclick": {
        "enable": true,
        "mode": "push"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 140,
        "line_linked": {
          "opacity": 1
        }
      },
      "bubble": {
        "distance": 400,
        "size": 40,
        "duration": 2,
        "opacity": 8,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
}); 
</script>
</body>
</html>