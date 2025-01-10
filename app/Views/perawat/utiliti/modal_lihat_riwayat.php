<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="section-nama" style="display: flex; padding-top: 3%; justify-content: center; font-weight: 700; margin-bottom: 3%">
                    <div class="norm" id="contentNorm" style=""></div>
                    <div class="" style="width: 3%; text-align: center;">-</div>
                    <div class="nama" id="contentNamaPasien"></div>
                </div>

                <div class="type">
                    <div class="section-menu">

                        <div class="menu-1" onclick="lab()" id="btn-lab">
                            Laboratorium
                        </div>
                        <div class="menu-1" onclick="radiologi()" id="btn-radiologi">
                            Radiologi
                        </div>
                        <div class="menu-1" onclick="riwayatObat()" id="btn-obat">
                            Data Pemberian Obat
                        </div>
                        <div class="menu-1" onclick="tambahCatatan()" id="btn-catatan">
                            Catatan
                        </div>
                        <div class="menu-1 active" onclick="riwayatPasien()" id="btn-riwayat">
                            Riwayat Pasien
                        </div>

                    </div>
                </div>

                <div class="riwayat" id="section-modal-riwayat">

                </div>

                <form id="insertCatatan" method="post">
                    <input type="text" id="catatan_noRm" name="noRm" hidden>
                    <input type="text" id="catatan_noRawat" name="noRawat" hidden>
                    <input type="text" id="catatan_tanggal" name="tanggal" hidden>
                    <input type="text" id="catatan_jam" name="jam" hidden>
                    <?= $this->include('perawat/utiliti/section_r_obat') ?>

                    <?= $this->include('perawat/utiliti/section-catatan') ?>
                </form>
                <?= $this->include('perawat/utiliti/section_radiologi') ?>
                <?= $this->include('perawat/utiliti/section-lab') ?>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('staticBackdrop').addEventListener('hidden.bs.modal', function() {
        $('#catatan_noRm').val("");
        $('#catatan_noRawat').val("");

        $('#section-modal-riwayat').html("");
        $('#list-catatan').html("");
        $('#insertCatatan')[0].reset();
        document.getElementById('section-modal-riwayat').removeAttribute('hidden');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true');
        document.getElementById('radiologi').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');
        document.getElementById('catatan_tanggal').value = "";
        document.getElementById('catatan_jam').value = ""

        document.getElementById('tombol-2').setAttribute('hidden', 'true')
        document.getElementById('section-change-tombol').removeAttribute('hidden');

        let rows = '';
        $('#gambar-radiologi-zoom-1').html(rows);

        var body = document.body;
        body.style.paddingRight = '';

        document.getElementById('btn-riwayat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
        document.getElementById('btn-lab').classList.remove('active');
    });


    function riwayatPasien() {
        document.getElementById('section-modal-riwayat').removeAttribute('hidden');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true')
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('radiologi').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');

        document.getElementById('btn-riwayat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
        document.getElementById('btn-lab').classList.remove('active');
    }

    function tambahCatatan() {
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true');
        document.getElementById('section-catatan').removeAttribute('hidden');
        document.getElementById('radiologi').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');

        document.getElementById('btn-catatan').classList.add('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
        document.getElementById('btn-lab').classList.remove('active');

        var no_rawat = document.getElementById("catatan_noRawat").value

        $.ajax({
            url: '<?= base_url('pasien/getCatatan') ?>',
            method: 'GET',
            data: {
                noRawat: no_rawat
            },
            dataType: 'json',
            success: function(data) {
                Swal.close();
                let rows = '';
                if (data.length > 0) {
                    data.forEach(function(item, index) {
                        rows += `
                                <div class="content">
                                    <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                        ${item.tanggal +' '+item.jam}
                                    </div>
                                    <div class="isi" style="padding: 2%; border: 1px solid; display: flex;" id="isCatatan">
                                        <div style="width: 80%;">
                                            ${item.catatan}
                                        </div>
                                        <div style="width: 15%; display: flex;">
                                            <button 
                                                class="btn-custom-edit"
                                                style="padding: 10%; border-radius: 2px; border: none; color: white; background-color: rgb(119, 128, 0); margin-right: 3%;"
                                                type="button"
                                                data-no-rawat="${no_rawat}"
                                                data-tanggal="${item.tanggal}"
                                                data-jam="${item.jam}"
                                                data-catatan="${item.catatan}"
                                                onclick="GantiCatatan(this)">
                                                Edit
                                            </button>
                                            <button 
                                                class="btn-custom-edit"
                                                style="padding: 10%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                                                type="button"
                                                data-no-rawat="${no_rawat}"
                                                data-tanggal="${item.tanggal}"
                                                data-jam="${item.jam}"
                                                data-catatan="${item.catatan}"
                                                onclick="hapus(this)">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                    });
                } else {
                    rows += `
                                <div class="content">
                                    <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                        BELUM ADA CATATAN PERAWATAN
                                    </div>
                                </div>
                            `;
                }
                $('#list-catatan').html(rows);


            },

            error: function() {
                // Hapus swal loading
                Swal.close();

                // Tampilkan swal error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil data!'
                });
            }
        });



    }

    function GantiCatatan(button) {
        const noRawat = button.getAttribute('data-no-rawat');
        const tanggal = button.getAttribute('data-tanggal');
        const jam = button.getAttribute('data-jam');
        const catatan = button.getAttribute('data-catatan');
        document.getElementById('floatingTextarea2').value = catatan;
        document.getElementById('catatan_tanggal').value = tanggal;
        document.getElementById('catatan_jam').value = jam;



        const allButtons = document.querySelectorAll('.btn-custom-edit');
        allButtons.forEach(function(btn) {
            btn.style.display = 'none';
        });
        button.style.display = 'block';

        button.innerText = 'Batalkan';
        button.style.backgroundColor = 'rgb(255, 0, 0)';

        button.onclick = function() {
            Batalkan(button, noRawat, tanggal, jam, catatan);
        };

        document.getElementById('section-change-tombol').setAttribute('hidden', 'true')
        document.getElementById('tombol-2').removeAttribute('hidden');
    }

    function updateCatatan(button) {

        var no_rawat = document.getElementById("catatan_noRawat").value
        var tes = $('#insertCatatan').serialize()

        $.ajax({
            url: '<?= base_url('pasien/updateCatatan') ?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                if (response.status_code === 200) {
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
                    });

                    setTimeout(function() {
                        $('#staticBackdrop').modal('hide');
                        const event = new CustomEvent("dataRefreshed");
                        window.dispatchEvent(event)
                    }, 1500);

                    $('#insertCatatan')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.'
                });
            }
        });
    }

    function hapus(button) {
        var noRawat = button.getAttribute('data-no-rawat');
        var tanggal = button.getAttribute('data-tanggal');
        var jam = button.getAttribute('data-jam');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dipulihkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pasien/deleteCatatan') ?>',
                    type: 'POST',
                    data: {
                        noRawat: noRawat,
                        tanggal: tanggal,
                        jam: jam
                    },
                    success: function(response) {
                        if (response.status_code === 200) {
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
                            });

                            setTimeout(function() {
                                $('#staticBackdrop').modal('hide');
                                const event = new CustomEvent("dataRefreshed");
                                window.dispatchEvent(event);
                            }, 1500);

                            $('#insertCatatan')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                });
            }
        });
    }

    function Batalkan(button, noRawat, tanggal, jam, catatan) {
        document.getElementById('floatingTextarea2').value = '';

        const allButtons = document.querySelectorAll('.btn-custom-edit');
        allButtons.forEach(function(btn) {
            btn.style.display = 'block';
        });

        button.innerText = 'Edit';
        button.style.backgroundColor = 'rgb(119, 128, 0)';

        button.onclick = function() {
            GantiCatatan(button);
        };

        document.getElementById('section-change-tombol').removeAttribute('hidden');
        document.getElementById('tombol-2').setAttribute('hidden', 'true')
        document.getElementById('floatingTextarea2').value = "";
        document.getElementById('catatan_tanggal').value = "";
        document.getElementById('catatan_jam').value = ""
    }

    function radiologi() {
        document.getElementById('radiologi').removeAttribute('hidden');
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true')
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true')
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');

        document.getElementById('btn-radiologi').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
        document.getElementById('btn-lab').classList.remove('active');

        Swal.fire({
            title: 'Sedang Mengambil data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            customClass: {
                title: 'swal-title-small',
                content: 'swal-text-small'
            }
        });

        var no_rawat = document.getElementById("catatan_noRawat").value
        // alert(noRawat)
        $.ajax({
            url: '<?= base_url('pasien/getGambarRadiologi') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                let rows = '';
                Swal.close();


                // console.log(response)
                if (response.length > 0) {
                    for (let i = 0; i < response.length; i++) {
                        rows += `
                            <div class="wrapper-jam" style="margin-bottom: 3%; ">
                                <div class"tgl-periksa" style="display: flex;">
                                    <div style="width: 10%">Tanggal</div>
                                    <div style="width: 2%;">:</div>
                                    <div>${response[i].tgl_periksa}</div>
                                </div>
                                <div class"tgl-periksa" style="display: flex;">
                                    <div style="width: 10%;">Jam</div>
                                    <div style="width: 2%;">:</div>
                                    <div>${response[i].jam}</div>
                                </div>
                                <div class"tgl-periksa" style="display: flex;">
                                    <div style="width: 10%;">Hasil</div>
                                    <div style="width: 2%;">:</div>
                                    <div>${response[i].hasil}</div>
                                </div>
                                <div style="display: flex;">
                                    <div style="width: 10%;"></div>
                                    <div style="width: 2%;"></div>
                                    <div class="tgl-btn" 
                                        onclick="zoomImage('http://192.168.2.91/webapps/radiologi/${response[i].lokasi_gambar}', 'modal-zoom-radiologi-custom-1')">
                                        Tampilkan Gambar
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    rows += 'Tidak Ada File Gambar Radiologi....'
                }
                $('#gambar-radiologi-zoom-1').html(rows);
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