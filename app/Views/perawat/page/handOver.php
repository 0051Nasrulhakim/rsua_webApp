<?= $this->extend('perawat/index_perawat') ?>
<?= $this->section('content') ?>
<?= $this->include('perawat/utiliti/handOver/modal_handOver') ?>
<link rel="stylesheet" href="<?= base_url() ?>public/assets/css/handover.css">


<div class="wrapper">

    <div class="judul">
        Hand Over Ranap
    </div>
    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%; display: flex; width: 100%;" hidden>
        <div class="text-tanggal" style="display: flex; align-items: center; width: 11%;">
            <label for="filter-doctor">Tanggal</label>
        </div>
        <div class="input">
            <input type="date" class="form-control form-control" value="<?= date('Y-m-d') ?>" id="tanggalListCatatan" onchange="fetchChangeTanggal()">
        </div>
    </div>
    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%; display: flex">
        <label for="filter-doctor" style="width: 11%; display: flex; align-items: center;">Filter Dokter </label>
        <select class="form-select" id="filter-doctor" style="width: 30%;">
            <option value="">Semua Dokter</option>
        </select>

    </div>
    <div class="sectionhandOver" style="overflow-x: auto; -webkit-overflow-scrolling: touch; font-size: 10pt;">
        <div class="headHandover" style="display: flex;">
            <div class="kelas">Kelas</div>
            <div class="nama">Nama</div>
            <div class="dpjp">DPJP</div>
            <div class="shift">PAGI</div>
            <div class="shift">SIANG</div>
            <div class="shift">Malam</div>
            <div class="all">All</div>
        </div>
        <div class="listHandOver" id="bodyHandover" style="font-size: 12pt;">

        </div>

        <div class="wrapAllListCatatan" id="allListHandOver" style="font-size: 12pt !important;">


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

    function menuHandOver() {
        document.getElementById('ranap').classList.remove('active');
        document.getElementById('handover').classList.add('active');
    }
    
    function fecthDataHandOver(no_rawat, isHiddenOpen = 'false') {
        menuHandOver();
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
                kd_dokter: selectedDoctor,
                noRawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                console.log(response)
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
                                    <div class="kelas">
                                        ${item.kd_kamar}
                                    </div>
                                    <div class="nama" style="text-align: left">
                                        ${item.nm_pasien}
                                    </div>
                                    <div class="dpjp" style="text-align: left"> `
                                    if (item.dokter_dpjp && item.dokter_dpjp !== 'null') {
                                        bodyTabel += item.dokter_dpjp
                                    } else {
                                        console.log("Tidak ada dokter DPJP");
                                    }
                        bodyTabel +=  `<div class"section-addshift" style="display:flex; margin-bottom: 1%">
                                `
                        if (item.todayPagi == "") {
                            bodyTabel += `  
                                            <div class="tombol" style="padding: 1%; width: 30%; background-color:rgb(96, 136, 74);  color:white; text-align:center; margin-right:2%"
                                                onclick="addCatatan('${item.no_rawat}', 'pagi')"
                                            >
                                                Pagi
                                            </div>`
                        }
                        if (item.todaySiang == "") {
                            bodyTabel += `
                                            <div class="tombol" style="padding: 1%; width:30%; background-color:rgb(96, 136, 74);  color:white; text-align:center; margin-right:2%"
                                                onclick="addCatatan('${item.no_rawat}', 'siang')"
                                            >
                                                Siang
                                            </div>
                                    `
                        }
                        if (item.todayMalam == "") {
                            bodyTabel += `
                                            <div class="tombol" style="padding: 1%; width:30%; background-color:rgb(96, 136, 74);  color:white; text-align:center; margin-right:2%"
                                                onclick="addCatatan('${item.no_rawat}', 'malam')"
                                            >
                                                Malam
                                            </div>
                                    `
                        }
                        bodyTabel += `       
                                            
                                        </div>
                                    </div>

                                    <div class="shift" style="padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                `
                        if (item.catatan.pagi.length > 0) {
                            item.catatan.pagi.forEach(function(catatan) {
                                jam_pagi = catatan.jam
                                isi_pagi = catatan.catatan
                                tanggal_catatan_pagi = catatan.tanggal
                                if (catatan.catatan !== '') {
                                    bodyTabel += `
                                    <div clas="catatan">
                                        <pre style="text-wrap: wrap;">${catatan.catatan}</pre>
                                    </div>
                                    <div class"namaPetugas" style="font-weight: 700;">
                                        <span class="badge text-bg-warning">${catatan.nama_petugas}</span>
                                    </div>
                                    <div>
                                        <span class="badge text-bg-secondary"> ${catatan.tanggal} |  ${catatan.jam} </span>
                                    </div>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                `
                        if (item.catatan.pagi != '' && tanggal_catatan_pagi == tanggal) {

                            bodyTabel += `
                                                    <div class="flex" style="display: flex; justify-content: space-between;">
                                                        <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                            onclick="editCatatan('${item.no_rawat}', '${jam_pagi}', 'pagi', '${tanggal_catatan_pagi}')"
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
                        }

                        bodyTabel += `
                                        </div>
                                    </div>

                                    <div class="shift" style="padding: 3px; border: 1px solidrgb(0, 0, 0); display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                    `
                        if (item.catatan.siang.length > 0) {
                            item.catatan.siang.forEach(function(catatan) {
                                jam_siang = catatan.jam
                                isi_siang = catatan.catatan
                                tanggal_catatan_siang = catatan.tanggal
                                if (catatan.catatan !== '') {
                                    bodyTabel += `
                                    <div clas="catatan">
                                        <pre style="text-wrap: wrap;">${catatan.catatan}</pre>
                                    </div>
                                    <div class"namaPetugas" style="font-weight: 700;">
                                        <span class="badge text-bg-warning"> ${catatan.nama_petugas}</span>
                                    </div>
                                    <div>
                                        <span class="badge text-bg-secondary">${catatan.tanggal} | ${catatan.jam} </span>
                                    </div>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                    `
                        if (item.catatan.siang != '' && tanggal_catatan_siang == tanggal) {
                            bodyTabel += `
                                                                <div class="flex" style="display: flex; justify-content: space-between;">
                                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                                        onclick="editCatatan('${item.no_rawat}', '${jam_siang}', 'siang', '${tanggal_catatan_siang}')"
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
                        }
                        bodyTabel +=
                            `
                                        </div>

                                    </div>
                                    <div class="shift" style="padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">


                                    `
                        if (item.catatan.malam.length > 0) {
                            item.catatan.malam.forEach(function(catatan) {
                                jam_malam = catatan.jam
                                isi_malam = catatan.catatan
                                tanggal_catatan_malam = catatan.tanggal
                                if (catatan.catatan !== '') {
                                    bodyTabel += `
                                    <div clas="catatan">
                                        <pre style="text-wrap: wrap;">${catatan.catatan}</pre>
                                    </div>
                                    <div class"namaPetugas" style="font-weight: 700;">
                                        <span class="badge text-bg-warning">${catatan.nama_petugas}</span>
                                    </div>
                                    <div>
                                        <span class="badge text-bg-secondary">${catatan.tanggal} |  ${catatan.jam} </span>
                                    </div>`
                                }
                            })
                        }

                        bodyTabel += `
                                        </div>
                                        <div class="keterangan" style="margin-top: 1%;">
                                    `
                        if (item.catatan.malam != '' && tanggal_catatan_malam == tanggal) {
                            bodyTabel += `
                                                            <div class="flex" style="display: flex; justify-content: space-between;">
                                                                <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                                    onclick="editCatatan('${item.no_rawat}', '${jam_malam}', 'malam', '${tanggal_catatan_malam}')"
                                                                ><i class="fa-solid fa-pen-to-square"></i>    
                                                                </div>
                                                                <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                                    onclick="hapusCatatan('${item.no_rawat}', '${jam_malam}')"
                                                                >
                                                                    <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                                </div>
                                                            </div>
                                                        `
                        }
                        bodyTabel +=
                            `
                                        </div>
                                    </div>

                            `
                        if (isHiddenOpen != 'true') {
                            bodyTabel += `
                                    <div class="all" style=""
                                        onclick="actionBtnOpen('${item.no_rawat}')"
                                        id="btnOpenFetchlistCatatan"

                                    >
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    `
                        } else {
                            bodyTabel += `
                                    <div class="all" style=""
                                        onclick="fecthDataHandOver('', false)"
                                        id="btnCloseFectListCatatan"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </div>
                                `
                        }
                        bodyTabel += `
                                </div>
                                `
                    })
                }

                $('#bodyHandover').html(bodyTabel);
                fetchAllListCatatan(no_rawat)
            },
            error: function(error) {
                stokObatNew(tanggalfilter);
                alert('Terjadi kesalahan Silahkan Ulangi.');
                inputObject = []
            }
        });
    }


    $('#filter-doctor').on('change', function() {
        selectedDoctor = $(this).val();
        fecthDataHandOver()
    });

    function editCatatan(no_rawat, jam, shift, tanggal) {

        $('#handOverModalLabel').text('Update Hand Over');
        $('#inputNoRawat').val(no_rawat);
        $('#inputJam').val(jam);
        $('#inputTanggal').val(tanggal);
        $('#inputShift').val(shift);
        document.getElementById('addBtnSection').setAttribute('hidden', 'true')
        document.getElementById('updateBtnSection').removeAttribute('hidden')
        getCatatan(no_rawat, jam, shift, tanggal)

    }

    function getCatatan(no_rawat, jam, shift, tanggal, showAll = 'false') {
        console.log(no_rawat, jam, shift, tanggal, showAll)
        $.ajax({
            url: '<?= base_url('HandOver/detailCatatan') ?>',
            type: 'POST',
            data: {
                noRawat: no_rawat,
                tanggal: tanggal,
                jam: jam
            },
            success: function(response) {
                console.log(response)
                $('#floatingTextarea2').val(response.data.uraian);
                $('#showAll').val(showAll);
                $('#handOverModal').modal('show');
            },
            error: function(error) {
                stokObatNew(tanggalfilter);
                alert('Terjadi kesalahan Silahkan Ulangi.');
                inputObject = []
            }
        });
    }

    function fetchChangeTanggal() {
        fecthDataHandOver()
    }

    function addCatatan(no_rawat, shift) {
        const now = new Date();
        const jam = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

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

        let showAll = $('#showAll').val();
        let noRawat = $('#inputNoRawat').val();

        $.ajax({
            url: '<?= base_url('pasien/saveCatatan_perawatan') ?>',
            type: 'POST',
            data: $('#formHandover').serialize(),
            success: function(response) {
                if (showAll == 'true') {
                    fecthDataHandOver(noRawat, 'true');
                } else {
                    fecthDataHandOver();
                }
                $('#handOverModal').modal('hide');
            },
            error: function(error) {
                stokObatNew(tanggalfilter);
                alert('Terjadi kesalahan Silahkan Ulangi.');
                inputObject = []
            }
        });
    }

    function submitCatatan() {
        let showAll = $('#showAll').val();

        let noRawat = $('#inputNoRawat').val();
        $.ajax({
            url: '<?= base_url('pasien/updateCatatan') ?>',
            type: 'POST',
            data: $('#formHandover').serialize(),
            success: function(response) {
                if (showAll == 'true') {
                    fecthDataHandOver(noRawat, 'true');
                } else {
                    fecthDataHandOver();
                }
                $('#handOverModal').modal('hide');
            },
            error: function(error) {
                stokObatNew(tanggalfilter);
                alert('Terjadi kesalahan Silahkan Ulangi.');
                inputObject = []
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

    function actionBtnOpen(no_rawat) {
        let isHiddenOpen = 'true'
        fecthDataHandOver(no_rawat, isHiddenOpen)

    }


    function fetchAllListCatatan(no_rawat) {

        $.ajax({
            url: '<?= base_url('pasien/getCatatan') ?>',
            type: 'GET',
            data: {
                noRawat: no_rawat,
                newPage: '1'
            },
            success: function(response) {

                let allCatatan = ''
                let shifts = ['pagi', 'siang', 'malam'];

                if (response.status_code == '200') {

                    if (Object.keys(response.data).length > 0) {

                        for (let tanggal in response.data) {

                            let items = response.data[tanggal];
                            let shiftData = {};

                            items.forEach(function(item) {
                                shiftData[item.shift] = {
                                    catatan: item.catatan,
                                    jam: item.jam,
                                    nama: item.nama
                                };
                            });

                            allCatatan += `

                                <div class="allListHandOver" style="display: flex;" >
                                    <div class="Alltanggal" style="">${tanggal}</div>
                                    <div class="allShift">
                                        <div class="text-catatan" style="text-align:center;">
                                            <pre style="text-wrap: wrap;">${shiftData.pagi.catatan}
                                            </pre>
                                        `
                            if (shiftData.pagi.catatan != '') {
                                allCatatan += `
                                        <div class="namaPetugas" style="font-weight:700">
                                            ${shiftData.pagi.nama}
                                        </div>
                                        <div class="">
                                            <span class="badge text-bg-warning">${tanggal}</span> | <span class="badge text-bg-secondary">${shiftData.pagi.jam}</span>
                                        </div>
                            `
                            }
                            allCatatan += `
                                        </div>
                                    `

                            allCatatan += `
                                    </div>
                                    `
                            allCatatan += `
                                    <div class="allShift">
                                        <div class="text-catatan" style="text-align:center;">
                                            <pre style="text-wrap: wrap;">${shiftData.siang.catatan}
                                            </pre>
                                        `
                            if (shiftData.siang.catatan != '') {
                                allCatatan += `
                                        <div class="namaPetugas" style="font-weight:700">
                                            ${shiftData.siang.nama}
                                        </div>
                                        <div class="">
                                            <span class="badge text-bg-warning">${tanggal}</span> | <span class="badge text-bg-secondary">${shiftData.siang.jam}</span>
                                        </div>
                            `
                            }
                            allCatatan += `
                                        </div>
                            `

                            allCatatan += `
                                    </div>
                                    `

                            allCatatan += `
                                    <div class="allShift">
                                        <div class="text-catatan" style="text-align:center;">
                                            <pre style="text-wrap: wrap;">${shiftData.malam.catatan}
                                            </pre>
                                            `
                            if (shiftData.malam.catatan != '') {
                                allCatatan += `
                                        <div class="namaPetugas" style="font-weight:700">
                                            ${shiftData.malam.nama}
                                        </div>
                                        <div class="">
                                            <span class="badge text-bg-warning">${tanggal}</span> | <span class="badge text-bg-secondary">${shiftData.malam.jam}</span>
                                        </div>
                            `
                            }
                            allCatatan += `
                                        </div>
                                        `
                            allCatatan += `
                                    </div>
                                </div>
                            `

                        }

                    }


                }

                $('#allListHandOver').html(allCatatan);
            }
        });
    }

    function addOldList(tanggal, no_rawat, shift) {
        let showAll = 'true'
        $('#handOverModalLabel').text('Insert Hand Over');
        $('#inputNoRawat').val(no_rawat);
        $('#inputTanggal').val(tanggal);
        $('#inputShift').val(shift);
        $('#showAll').val(showAll);
        document.getElementById('addBtnSection').removeAttribute('hidden')
        document.getElementById('updateBtnSection').setAttribute('hidden', 'true')
        $('#handOverModal').modal('show');

    }

    function updateOldList(tanggal, jam, no_rawat, shift) {
        // console.log(tanggal,jam, no_rawat, shift)
        let showAll = 'true'
        $('#handOverModalLabel').text('Update Hand Over');
        $('#inputNoRawat').val(no_rawat);
        $('#inputJam').val(jam);
        $('#inputTanggal').val(tanggal);
        $('#inputShift').val(shift);
        document.getElementById('addBtnSection').setAttribute('hidden', 'true')
        document.getElementById('updateBtnSection').removeAttribute('hidden')
        getCatatan(no_rawat, jam, shift, tanggal, showAll)
    }

    function deleteOldList(tanggal, jam, no_rawat, shift) {
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
                            fecthDataHandOver(no_rawat, 'true')

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