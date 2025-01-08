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

                        <div class="menu-1" onclick="radiologi()" id="btn-radiologi">
                            Radiologi
                        </div>
                        <div class="menu-1" onclick="riwayatObat()" id="btn-obat">
                            Data Pemberian Obat
                        </div>
                        <div class="menu-1" onclick="tambahCatatan()" id="btn-catatan">
                            Tambahkan Catatan
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
                    <?= $this->include('perawat/utiliti/section_r_obat') ?>

                    <?= $this->include('perawat/utiliti/section-catatan') ?>
                </form>
                <?= $this->include('perawat/utiliti/section_radiologi') ?>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('staticBackdrop').addEventListener('hidden.bs.modal', function() {
        $('#catatan_noRm').val("");
        $('#catatan_noRawat').val("");

        $('#section-modal-riwayat').html("");
        $('#insertCatatan')[0].reset();
        document.getElementById('section-modal-riwayat').removeAttribute('hidden');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true');
        document.getElementById('radiologi').setAttribute('hidden', 'true');

        let rows = '';
        $('#gambar-radiologi-zoom-1').html(rows);

        var body = document.body;
        body.style.paddingRight = '';

        document.getElementById('btn-riwayat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
    });

    function tambahCatatan() {
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true');
        document.getElementById('section-catatan').removeAttribute('hidden');
        document.getElementById('radiologi').setAttribute('hidden', 'true');

        document.getElementById('btn-catatan').classList.add('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
    }

    function riwayatPasien() {
        document.getElementById('section-modal-riwayat').removeAttribute('hidden');
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true')
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('radiologi').setAttribute('hidden', 'true');

        document.getElementById('btn-riwayat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');
    }

    function radiologi() {
        document.getElementById('radiologi').removeAttribute('hidden');
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true')
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true')
        document.getElementById('section-catatan').setAttribute('hidden', 'true');

        document.getElementById('btn-radiologi').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-obat').classList.remove('active');

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