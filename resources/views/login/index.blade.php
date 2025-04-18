<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - WebAbsensiEskul</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .text-bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0098db 100%);
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-body {
            padding: 3rem !important;
        }

        .btn-primary {
            padding: 0.8rem;
            font-weight: 500;
        }

        .form-control-lg {
            padding: 1rem;
            font-size: 1rem;
        }

        .login-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- Login 5 - Bootstrap Brain Component -->
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6 text-bg-primary">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="col-10 col-xl-8 py-3">
                                <img class="img-fluid rounded mb-4" loading="lazy" src="assets/img/logo-igsr.png"
                                    width="80" height="80" alt="BootstrapBrain Logo">
                                <hr class="border-primary-subtle mb-4">
                                <h2 class="h1 mb-4">Selamat Datang di Website Absensi SMK Igasar Pindad</h2>
                                <p class="lead m-0">Jujur Disiplin Kompeten</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h3>Log in</h3>
                                    </div>
                                </div>
                            </div>
                            <form role="form" action="{{ route('login.auth') }}" method="POST">
                                @csrf
                                @error('email')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                                <div class="mb-3">
                                    <input type="text" name="email" value="{{ @old('email') }}"
                                        class="form-control form-control-lg" placeholder="Email" aria-label="Email">
                                </div>
                                @error('password')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                                <div class="mb-3">
                                    <input type="password" name="password" value="{{ @old('password') }}"
                                        class="form-control form-control-lg" placeholder="Password"
                                        aria-label="Password">
                                </div>
                                {{-- <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="rememberMe">
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div> --}}
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign
                                        in</button>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                        {{-- <a href="#!" class="link-secondary text-decoration-none">Create new
                                            account</a> --}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
