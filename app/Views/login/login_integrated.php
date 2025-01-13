<!DOCTYPE html>
<html lang="en">

<head>
    <title>SIMRS-ARO</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?= base_url() ?>public/login_template/images/icons/faviconaro.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/login_template/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/login_template/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="<?= base_url() ?>public/login_template/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/login_template/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/login_template/css/main.css">
    <!--===============================================================================================-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('public/login_template/images/bg-01.jpg');">
            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    LOGIN SIMRS-ARO
                </span>
                <form class="login100-form validate-form p-b-33 p-t-5" onkeydown="handleEnter(event)">

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="nameuser" placeholder="Username">
                        <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="keyuser" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                    </div>

                    <div class="container-login100-form-btn m-t-32">
                        <a class="btn text-white login100-form-btn" onclick="cekLogin()">
                            Login
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script>
        function handleEnter(event) {
            if (event.key === "Enter") {
                cekLogin();
            }
        }

        function cekLogin() {
            Swal.fire({
                title: 'Mengecek Login...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    title: 'swal-title-small',
                    content: 'swal-text-small'
                }
            });

            var username = $('input[name="nameuser"]').val();
            var password = $('input[name="keyuser"]').val();
            console.log(username);
            console.log(password);

            $.ajax({
                url: '<?= base_url('') ?>AuthController/checklogin',
                method: 'POST',
                data: {
                    username: username,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    setTimeout(function() {
                        Swal.close();
                        // console.log(response.status_code);
                        if (response.status_code == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                            }).then(() => {
                                window.location.href = '<?= base_url()?>pasien';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    }, 800);
                },

                error: function() {
                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengambil data!'
                    });
                }
            });
        }
    </script>


    <!--===============================================================================================-->
    <!-- <script src="<?= base_url() ?>public/login_template/vendor/jquery/jquery-3.2.1.min.js"></script> -->
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/vendor/bootstrap/js/popper.js"></script>
    <script src="<?= base_url() ?>public/login_template/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url() ?>public/login_template/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/login_template/js/main.js"></script>



</body>

</html>