<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMRS - ARO RANAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="<?= base_url('assets/css/perawat.css') ?>"> -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/perawat.css') ?>">
    <!-- <link rel="stylesheet" href="https://7725wjls-8080.asse.devtunnels.ms/assets/css/perawat.css"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" crossorigin="anonymous"></script>

    <!-- Tambahkan CDN untuk jQuery dan SweetAlert -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-o+xAO1Bk74GlQ9BtBa4KxfJGRzQ3HQct+cxKFiBbxK7AcOxOpmgqb4TiOr9zEbYG" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="navbar">
        <div class="left">
            <div class="logo">
                SIMRS ARO RANAP
            </div>
        </div>
        <div class="right">
            <i class="fa-solid fa-bars fa-lg"></i>
        </div>
    </div>

    <div class="section-breadvum" style="display: flex; justify-content: space-between; margin-left: 2%; margin-right: 5%;">
        <nav aria-label="breadcrumb" style="width: 50%; align-items: center; padding: 0.5%;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pasien</a></li>
                <!-- <li class="breadcrumb-item"><a href="#">Library</a></li> -->
                <li class="breadcrumb-item active" aria-current="page">Daftar Pasien Ranap</li>
            </ol>
        </nav>
        <div class="userLogin" style="font-weight: 700; width: 40%; display: flex; justify-content: flex-end; text-align: right; align-items: center; font-size: 12px;">
            <?= session()->get('nama') ?>
            <!-- <a > -->
            <div onclick="logout()" class="logo" style="margin-left: 5%; padding-left: 3%; padding-right: 3%; background-color:rgb(255, 0, 0); border-radius: 5px;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <!-- </a> -->
        </div>
    </div>


    <script>
        function logout() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan keluar dari aplikasi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('login/logout') ?>';
                }
            })
        }
    </script>