<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
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

                <div class="diagnosa" style="margin-bottom: 3%;">
                    <div class="diagnosaAwal" style="display: flex;">
                        <div class="title" style="width: 15%;">
                            Diagnosa Awal
                        </div>
                        <div class="semicolon" style="width:  2%; text-align: center;">
                            :
                        </div>
                        <div class="contentDiagnosaAkhir" id="contentDiagnosaAwal">
                        </div>
                    </div>
                    <div class="diagnosaAkhir" style="display: flex;">
                        <div class="title" style="width: 15%;">
                            Diagnosa Akhir
                        </div>
                        <div class="semicolon" style="width:  2%; text-align: center;">
                            :
                        </div>
                        <div class="contentDiagnosaAkhir" id="contentDiagnosaAkhir">

                        </div>
                    </div>
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
                            Catatan Perawatan
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
        document.getElementById('search-bar').value = "";
        $('#catatan_noRm').val("");
        $('#catatan_noRawat').val("");
        $('#diagnosa_awal').text("");
        $('#diagnosa_akhir').text("");

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
        document.getElementById('search-bar').value = "";
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
        document.getElementById('search-bar').value = "";
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

        // allCatatan();
        lastCatatan();

    }



    function BatalkanEditTambah(button, noRawat, tanggal, jam, catatan, shift, isEdit) {


        const actionButton = document.querySelector(`[data-no-rawat="${noRawat}"][data-tanggal="${tanggal}"][data-jam="${jam}"][data-shift="${shift}"]`);
        if (actionButton) {
            actionButton.style.display = 'block';
        }

        if (isEdit) {
            actionButton.innerText = 'Edit';
            actionButton.style.backgroundColor = 'rgb(119, 128, 0)';
            actionButton.onclick = function() {
                GantiCatatan(actionButton);
            };
        } else {
            actionButton.innerText = 'Tambahkan';
            actionButton.style.backgroundColor = 'rgb(0, 70, 128)';
            actionButton.onclick = function() {
                TambahCatatan(actionButton);
            };
        }

        document.getElementById('section-change-tombol').removeAttribute('hidden');
        document.getElementById('tombol-2').setAttribute('hidden', 'true');
    }


    function updateCatatan(button) {

        var no_rawat = document.getElementById("catatan_noRawat").value
        var tes = $('#insertCatatan').serialize()

        $.ajax({
            url: '<?= base_url('pasien/updateCatatan') ?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                console.log(response)
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
                    });

                    setTimeout(function() {
                        // $('#staticBackdrop').modal('hide');
                        const event = new CustomEvent("dataRefreshed");
                        document.getElementById('tombol-2').setAttribute('hidden', 'true');
                        document.getElementById('section-change-tombol').removeAttribute('hidden');
                        document.getElementById('floatingTextarea2').value = '';
                        document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
                        window.dispatchEvent(event)
                        lastCatatan();
                    }, 1500);

                    // $('#insertCatatan')[0].reset();

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
                                const event = new CustomEvent("dataRefreshed");
                                document.getElementById('tombol-2').setAttribute('hidden', 'true');
                                document.getElementById('section-change-tombol').removeAttribute('hidden');
                                document.getElementById('floatingTextarea2').value = '';
                                document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
                                window.dispatchEvent(event)
                                lastCatatan();
                            }, 1500);

                            // $('#insertCatatan')[0].reset();
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
        document.getElementById('tanggal').value = ""
    }

    function radiologi() {
        document.getElementById('search-bar').value = "";
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