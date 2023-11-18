<!DOCTYPE html>
<html lang="en">

<head>

	<title>Login</title>
	<!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 11]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />
	<!-- Favicon icon -->
	<link rel="icon" href="{{asset('template/dist/assets/images/favicon.ico')}}" type="image/x-icon">

	<!-- vendor css -->
	<link rel="stylesheet" href="{{asset('template/dist/assets/css/style.css')}}">
	
	


</head>

<!-- [ auth-signin ] start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Tambahkan file CSS Anda di sini (jika ada) -->
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="row align-items-center text-center">
                    <div class="col-md-12">
                        <div class="card-body">
                            <!-- Tambahkan bagian logo di sini -->
                            <img src="{{asset('template/dist/assets/images/a.png')}}" width="150px" height="150px" alt="" class="img-fluid mb-2">
                            <h4 class="mb-2 text-center">Selamat Datang!👋</h4>
                            <p class="mb-4 text-center">Login untuk dapat menggunakan aplikasi</p>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @elseif(session('statusErr'))
                                <div class="alert alert-danger">
                                    {{ session('statusErr') }}
                                </div>
                            @endif
                            <form id="formAuthentication" class="mb-3" action="{{ route('login-process') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="floating-label" for="username">Username</label>
                                    <input type="text" class="form-control" name="email" id="username" placeholder="">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="floating-label" for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="">
                                </div>
                                <button class="btn btn-block btn-primary mb-4" type="submit">Sign in</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Tambahkan file JavaScript Anda di sini (jika ada) -->
</body>
</html>

<!-- [ auth-signin ] end -->

<!-- Required Js -->
<script src="{{asset('template/dist/assets/js/vendor-all.min.js')}}"></script>
<script src="{{asset('template/dist/assets/js/plugins/bootstrap.min.js')}}"></script>
<script src="{{asset('template/dist/assets/js/ripple.js')}}"></script>
<script src="{{asset('template/dist/assets/js/pcoded.min.js')}}"></script>



</body>

</html>
