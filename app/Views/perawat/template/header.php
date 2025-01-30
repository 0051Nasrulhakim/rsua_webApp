<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HAND OVER - ARO RANAP</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->

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
                HAND OVER ARO RANAP
            </div>
        </div>
        <div class="right">
            <!-- <i class="fa-solid fa-bars fa-lg"></i> -->
        </div>
    </div>

    <div class="section-breadvum" style="display: flex; justify-content: space-between; margin-left: 2%; margin-right: 5%; margin-top: 0.5%;">

        <div class="menu" style="display: flex; align-items: flex-end; width: 50%;">
            <div class="ranap active" style="padding-left: 1%; padding-right: 1%; border-top-right-radius: 8px; background-color: rgb(245, 242, 89); border: 1px solid black;" onclick="menuRanap()" id="ranap">
                <a href="<?= base_url('pasien')?>" style="text-decoration: none; color: black; font-weight: 600;">
                    Pasien Ranap
                </a> 
            </div>
            <div class="handOver" style="padding-left: 1%; padding-right: 1%; border-top-right-radius: 8px; background-color: rgb(245, 242, 89); border: 1px solid black;" onclick="menuHandOver()" id="handover">
                <a href="<?= base_url('HandOver')?>" style="text-decoration: none; color: black; font-weight: 600;">
                    Hand Over
                </a>
            </div>
        </div>

        <div class="userLogin" style="font-weight: 700; width: 40%; display: flex; justify-content: flex-end; text-align: right; align-items: center; font-size: 12px; margin-bottom: 0.5%;">
            <?= session()->get('nama') ?>
            <div onclick="logout()" class="logo" style="margin-left: 5%; padding-left: 3%; padding-right: 3%; background-color:rgb(255, 0, 0); border-radius: 5px;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
        </div>
    </div>


    <script>
        

        function menuRanap()
        {
            document.getElementById('handover').classList.remove('active');
            document.getElementById('ranap').classList.add('active');
        }

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