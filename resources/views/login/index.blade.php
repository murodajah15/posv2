<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS Login</title>
    <!-- Font Awesome Icons -->
    {{-- <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/fontawesome/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/') }}assets/dist/css/adminlte.min.css">

    <link href="{{ asset('/') }}assets/dist/css/sweet-alert.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/toastr/toastr.min.css">

    {{-- <script href="assets/dist/js/sweetalert2.all.min.js"></script>
    <link href="assets/dist/css/sweetalert2.min.css" rel="stylesheet"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet"> --}}


    {{-- <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"> --}}
    {{-- <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css"> --}}

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/bootstrap462/bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/datatables/jquery.dataTables.css">

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" /> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" /> --}}

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

<style>
    /* body {
        background-image: url('assets/image/shattered_@2X.png');
    } */
</style>

<body onload="document.login.email.focus()" style="background-image: url('assets/image/shattered_@2X.png')">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                {{-- <div class="container"> --}}
                <div class="row h-100 justify-content-center align-items-center">
                    {{-- <div class='card card-outline card-primary mt-10 style="max-width: 18rem;">'> --}}
                    {{-- <div class="row row-cols-1 row-cols-md-12 g-1"> --}}
                    <div class="card" style="width: 25rem; margin:35px">
                        {{-- <div class="card border-default mb-3 mt-5" style="max-width: 100%;"> --}}
                        <div class='card-body'>
                            {{-- <div class="row justify-content-center"> --}}
                            <div class="col-lg-12">
                                {{-- <h2 class="text-center"><b>POS</b><br>Aplikasi Point Of Sales</h3> --}}
                                <h5 class='text-center'><img src="{{ url('assets/image/logo.png') }}" width='110'
                                        height='100' style='margin:-50px' class='img-circle'>
                                </h5>
                                <br><br>
                                {{-- <h3 class="text-center">
                                        Login <b>{{ config('app.name') }}</h3> --}}
                                <h3 class="text-center">
                                    Point Of Sales</h3>
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        <b>Opps!</b> {{ session('error') }}
                                    </div>
                                @endif
                                <form action="{{ route('actionlogin') }}" name="login" method="post">
                                    @csrf
                                    <label class="form-check-label" for="username"><b>Username</b></label>
                                    <div class="input-group mb-2">
                                        <input type="username" name="username" id="username" class="form-control"
                                            placeholder="username" autocomplete="on" autofocus required>
                                        <div class="input-group-text">
                                            <i class='fas fa-user'></i>
                                        </div>
                                    </div>
                                    <label class="form-check-label" for="password"><b>Password</b></label>
                                    <div class="input-group mb-2">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Password" autocomplete="on" required>
                                        <div class="input-group-text">
                                            <i class='fas fa-key'></i>
                                        </div>
                                    </div>
                                    <p id="capslockon">Caps lock is ON.</p>
                                    <button type="submit" class="btn btn-primary mt-2 col-12">Log
                                        In</button>
                                    <hr>
                                    <p class="text-center">Belum punya akun? <a href="register">Register</a>
                                        sekarang!</p>
                                    {{-- <a href="reset_password.php">Reset Password</a> --}}
                                </form>
                                <p class="text-center mt-2">
                                    <span>© 2023-{{ date('Y') }} Copyright <a class="ml-25"></a>
                                        <br />All rights
                                        reserved.</span>
                                </p>
                                <h5 class='text-right'><img src="{{ url('assets/image/login1.jpg') }}" width='60'
                                        height='60' style='margin:-70px 0px' class='img-curve'>
                                </h5>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                    {{-- </div> --}}
                </div>
        </div>

        <script>
            document.getElementById("capslockon").style.display = "none";
            var inputusername = document.getElementById("username");
            var text = document.getElementById("capslockon");
            inputusername.addEventListener("keyup", function(event) {
                if (event.getModifierState("CapsLock")) {
                    text.style.display = "block";
                } else {
                    text.style.display = "none"
                }
            });

            var inputpassword = document.getElementById("password");
            var text = document.getElementById("capslockon");
            inputpassword.addEventListener("keyup", function(event) {
                if (event.getModifierState("CapsLock")) {
                    text.style.display = "block";
                } else {
                    text.style.display = "none"
                }
            });
        </script>

</body>

{{-- <body onload="document.login.email.focus()" style="background-image: url('assets/image/shattered_@2X.png')">
    <div class="container"><br><br>
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center"><b>POS</b><br>Aplikasi Point Of Sales</h3>
                <hr>
                @if (session('error'))
                    <div class="alert alert-danger">
                        <b>Opps!</b> {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('actionlogin') }}" name="login" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="on"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required
                            autocomplete="on">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                    <hr>
                    <p class="text-center">Belum punya akun? <a href="register">Register</a> sekarang!</p>
                </form>
        </div>
    </div>
</body> --}}

</html>
