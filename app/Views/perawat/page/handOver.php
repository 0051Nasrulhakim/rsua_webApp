<?= $this->extend('perawat/index_perawat') ?>
<?= $this->section('content') ?>
<?= $this->include('perawat/utiliti/handOver/modal_handOver') ?>

<div class="wrapper">

    <div class="judul">
        Hand Over Ranap
    </div>

    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%; display: flex; width: 100%;">
        <div class="text-tanggal" style="display: flex; align-items: center; width: 9%;">
            <label for="filter-doctor">Tanggal</label>
        </div>
        <div class="input">
            <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" id="tanggalListCatatan" onchange="fetchChangeTanggal()">
        </div>
    </div>
    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%;">
        <label for="filter-doctor">Filter Dokter </label>
        <select id="filter-doctor">
            <option value="">Semua Dokter</option>
        </select>
    </div>
    <!-- <input type="text" id="noRawat"> -->
    <div class="sectionhandOver" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="headHandover" style="display: flex;">
            <div class="kelas" style="width: 6%; text-align: center; border: 1px solid #d6d6d6;">Kelas</div>
            <div class="nama" style="width: 18%; text-align: center; border: 1px solid #d6d6d6;">Nama</div>
            <div class="kelas" style="width: 20%; text-align: center; border: 1px solid #d6d6d6;">DPJP</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">PAGI</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">SIANG</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">Malam</div>
        </div>
        <div class="listHandOver" id="bodyHandover">

        </div>


    </div>

</div>


<script>
    $(document).ready(function() {
        
        fecthDataHandOver()

        $.ajax({
            url: '<?= base_url('pasien/dokterList') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.length > 0) {
                    let doctorOptions = '<option value="">Semua Dokter</option>';
                    response.forEach(function(item) {
                        doctorOptions += `<option value="${item.kd_dokter}">${item.nm_dokter}</option>`;
                    });
                    $('#filter-doctor').html(doctorOptions);
                }
            },
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil daftar dokter!'
                });
            }
        });
    });
    let selectedDoctor = '';
    

    function fecthDataHandOver() {
        let tanggal = document.getElementById('tanggalListCatatan').value;
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

        $.ajax({
            url: '<?= base_url('HandOver/handOverPerTanggal') ?>',
            method: 'GET',
            data: {
                tanggal: tanggal,
                kd_dokter: selectedDoctor
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                let bodyTabel = '';
                if (response.data.length > 0) {
                    response.data.forEach(function(item) {
                        var jam_pagi = ''
                        var jam_siang = ''
                        var jam_malam = ''
                        var isi_pagi = ''
                        var isi_siang = ''
                        var isi_malam = ''
                        bodyTabel += `
                                <div class="bodyHandover" style="display: flex; min-height: 30px" >
                                    <div class="kelas" style="width: 6%; text-align: center; border: 1px solid #d6d6d6; padding: 2px">
                                        ${item.kd_kamar}
                                    </div>
                                    <div class="nama" style="width: 18%; border: 1px solid #d6d6d6; padding: 2px">
                                        ${item.nm_pasien}
                                    </div>
                                    <div class="kelas" style="width: 20%; border: 1px solid #d6d6d6; padding: 2px">
                                        ${item.dokter_dpjp}
                                    </div>

                                    <div class="kelas" style="width: 130px; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                `
                        if (item.catatan.pagi.length > 0) {
                            item.catatan.pagi.forEach(function(catatan) {
                                jam_pagi = catatan.jam
                                isi_pagi = catatan.catatan
                                if (catatan.catatan !== '') {
                                    bodyTabel += `<pre style="text-wrap: wrap">${catatan.catatan +' | '+jam_pagi}</pre>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                `
                        if (item.catatan.pagi != '') {

                            bodyTabel += `
                                                                <div class="flex" style="display: flex; justify-content: space-between;">
                                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                                        onclick="editCatatan('${item.no_rawat}', '${jam_pagi}', 'pagi')"
                                                                    >
                                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                                    </div>
                                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                                        onclick="hapusCatatan('${item.no_rawat}', '${jam_pagi}')"
                                                                    >
                                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                                    </div>
                                                                </div>
                                                            `
                        } else {
                            bodyTabel += `
                                                    <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                                        onclick="addCatatan('${item.no_rawat}', '${jam_pagi}', 'pagi')"
                                                        >
                                                        <i class="fa-solid fa-plus"></i>
                                                    </div>
                                                `
                        }

                        bodyTabel += `
                                        </div>
                                    </div>

                                    <div class="kelas" style="width: 130px; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                    `
                        if (item.catatan.siang.length > 0) {
                            item.catatan.siang.forEach(function(catatan) {
                                jam_siang = catatan.jam
                                isi_siang = catatan.catatan
                                console.log(catatan)
                                if (catatan.catatan !== '') {
                                    bodyTabel += `<pre style="text-wrap: wrap">${catatan.catatan +' | '+jam_siang}</pre>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                    `
                        if (item.catatan.siang != '') {
                            bodyTabel += `
                                                                <div class="flex" style="display: flex; justify-content: space-between;">
                                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                                        onclick="editCatatan('${item.no_rawat}', '${jam_siang}', 'siang')"
                                                                    >
                                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                                    </div>
                                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                                        onclick="hapusCatatan('${item.no_rawat}', '${jam_siang}')"
                                                                    >
                                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                                    </div>
                                                                </div>
                                                            `
                        } else {
                            bodyTabel += `
                                                    <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                                    onclick="addCatatan('${item.no_rawat}', '${jam_pagi}', 'siang')"
                                                    >
                                                        <i class="fa-solid fa-plus"></i>
                                                    </div>
                                                `
                        }
                        bodyTabel +=
                            `
                                        </div>

                                    </div>
                                    <div class="kelas" style="width: 130px; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">


                                    `
                        if (item.catatan.malam.length > 0) {
                            item.catatan.malam.forEach(function(catatan) {
                                jam_malam = catatan.jam
                                isi_malam = catatan.catatan
                                if (catatan.catatan !== '') {
                                    bodyTabel += `<pre style="text-wrap: wrap">${catatan.catatan +' | '+jam_malam}</pre>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                    `
                        if (item.catatan.malam != '') {
                            bodyTabel += `
                                                            <div class="flex" style="display: flex; justify-content: space-between;">
                                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                                        onclick="editCatatan('${item.no_rawat}', '${jam_malam}', 'malam')"
                                                                    >
                                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                                    </div>
                                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                                        onclick="hapusCatatan('${item.no_rawat}', '${jam_malam}')"
                                                                    >
                                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                                    </div>
                                                                </div>
                                                        `
                        } else {
                            bodyTabel += `
                                            <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                            onclick="addCatatan('${item.no_rawat}', '${jam_pagi}', 'malam')"
                                            >
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        `
                        }
                        bodyTabel +=
                            `
                                        </div>
                                    </div>
                                </div>
                                `
                    })
                }

                $('#bodyHandover').html(bodyTabel);
            }
        });
    }

    $('#filter-doctor').on('change', function() {
        selectedDoctor = $(this).val();
        fecthDataHandOver()
    });

    function editCatatan(no_rawat, jam, shift) {

        let tanggal = document.getElementById('tanggalListCatatan').value;
        $('#handOverModalLabel').text('Update Hand Over');
        $('#inputNoRawat').val(no_rawat);
        $('#inputJam').val(jam);
        $('#inputTanggal').val(tanggal);
        $('#inputShift').val(shift);
        document.getElementById('addBtnSection').setAttribute('hidden', 'true')
        document.getElementById('updateBtnSection').removeAttribute('hidden')
        getCatatan(no_rawat, jam, shift, tanggal)

    }

    function getCatatan(no_rawat, jam, shift, tanggal) {

        $.ajax({
            url: '<?= base_url('HandOver/detailCatatan') ?>',
            type: 'POST',
            data: {
                noRawat: no_rawat,
                tanggal: tanggal,
                jam: jam
            },
            success: function(response) {
                $('#floatingTextarea2').val(response.data.uraian);
                $('#handOverModal').modal('show');
            }
        });
    }

    function fetchChangeTanggal() {
        fecthDataHandOver()
    }

    function addCatatan(no_rawat, jam, shift) {
        let tanggal = document.getElementById('tanggalListCatatan').value;
        $('#handOverModalLabel').text('Insert Hand Over');
        $('#inputNoRawat').val(no_rawat);
        $('#inputJam').val(jam);
        $('#inputTanggal').val(tanggal);
        $('#inputShift').val(shift);
        document.getElementById('addBtnSection').removeAttribute('hidden')
        document.getElementById('updateBtnSection').setAttribute('hidden', 'true')
        $('#handOverModal').modal('show');
    }

    function tambahkanCatatan() {
        $.ajax({
            url: '<?= base_url('pasien/saveCatatan_perawatan') ?>',
            type: 'POST',
            data: $('#formHandover').serialize(),
            success: function(response) {
                fecthDataHandOver();
                $('#handOverModal').modal('hide');
            }
        });
    }

    function submitCatatan() {
        $.ajax({
            url: '<?= base_url('pasien/updateCatatan') ?>',
            type: 'POST',
            data: $('#formHandover').serialize(),
            success: function(response) {
                fecthDataHandOver();
                $('#handOverModal').modal('hide');
            }
        });
    }

    function hapusCatatan(no_rawat, jam) {
        let tanggal = document.getElementById('tanggalListCatatan').value

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
                        noRawat: no_rawat,
                        tanggal: tanggal,
                        jam: jam
                    },
                    success: function(response) {
                        if (response.status_code === 200) {
                            fecthDataHandOver()

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
    
</script>

<?= $this->endSection(); ?>