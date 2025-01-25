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
    <div class="sectionhandOver" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="headHandover" style="display: flex;">
            <div class="kelas" style="width: 45px; text-align: center; border: 1px solid #d6d6d6;">Kelas</div>
            <div class="nama" style="width: 130px; text-align: center; border: 1px solid #d6d6d6;">Nama</div>
            <div class="kelas" style="width: 130px; text-align: center; border: 1px solid #d6d6d6;">DPJP</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">PAGI</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">SIANG</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid #d6d6d6;">Malam</div>
            <div class="kelas" style="width: 26px; text-align: center; border: 1px solid #d6d6d6; padding: 2px">All</div>
        </div>
        <div class="listHandOver" id="bodyHandover">

        </div>

        <div class="wrapAllListCatatan" id="allListHandOver">

            
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
    

    function fecthDataHandOver(no_rawat, isHiddenOpen = 'false') {
        
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
                                    <div class="kelas" style="width: 45px; text-align: center; border: 1px solid #d6d6d6; padding: 2px">
                                        ${item.kd_kamar}
                                    </div>
                                    <div class="nama" style="width: 130px; border: 1px solid #d6d6d6; padding: 2px">
                                        ${item.nm_pasien}
                                    </div>
                                    <div class="kelas" style="width: 130px; border: 1px solid #d6d6d6; padding: 2px">
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
                                                        onclick="addCatatan('${item.no_rawat}', 'pagi')"
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
                                                    onclick="addCatatan('${item.no_rawat}',  'siang')"
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
                                            onclick="addCatatan('${item.no_rawat}', 'malam')"
                                            >
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        `
                        }
                        bodyTabel +=
                            `
                                        </div>
                                    </div>

                            `
                            if(isHiddenOpen != 'true'){
                                bodyTabel +=`
                                    <div class="kelas" style="width: 26px; text-align: center; border: 1px solid #d6d6d6; padding: 2px; display:flex; align-items:center; justify-content:center"
                                        onclick="actionBtnOpen('${item.no_rawat}')"
                                        id="btnOpenFetchlistCatatan"

                                    >
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    `
                            }else{
                                bodyTabel +=`
                                    <div class="kelas" style="width: 26px; text-align: center; border: 1px solid #d6d6d6; padding: 2px; display:flex; align-items:center; justify-content:center"
                                        onclick="fecthDataHandOver('', false)"
                                        id="btnCloseFectListCatatan"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </div>
                                `
                            }
                        bodyTabel+=`
                                </div>
                                `
                    })
                }

                $('#bodyHandover').html(bodyTabel);
                fetchAllListCatatan(no_rawat)
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

    function getCatatan(no_rawat, jam, shift, tanggal, showAll = 'false') {

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
                $('#showAll').val(showAll);
                $('#handOverModal').modal('show');
            }
        });
    }

    function fetchChangeTanggal() {
        fecthDataHandOver()
    }

    function addCatatan(no_rawat, shift) {
        const now = new Date();
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

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
                if(showAll == 'true'){
                    fecthDataHandOver(noRawat, 'true');
                }else{
                    fecthDataHandOver();
                }
                $('#handOverModal').modal('hide');
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
                if(showAll == 'true'){
                    fecthDataHandOver(noRawat, 'true');
                }else{
                    fecthDataHandOver();
                }
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

    function actionBtnOpen(no_rawat)
    {
        let isHiddenOpen = 'true'
        fecthDataHandOver(no_rawat, isHiddenOpen)
        
    }


    function fetchAllListCatatan(no_rawat)
    {
        
        $.ajax({
            url: '<?= base_url('pasien/getCatatan')?>',
            type: 'GET',
            data: {
                noRawat: no_rawat,
                newPage: '1'
            },
            success: function(response) {
                // console.log(response)

                let allCatatan = ''
                let shifts = ['pagi', 'siang', 'malam'];

                if(response.status_code == '200'){

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
                                    <div class="kelas" style="width: 305px; text-align: center; border: 1px solid #d6d6d6;">${tanggal}</div>
                                    <div class="kelas" style="width: auto; min-width: 12px; max-width:130px; border: 1px solid #d6d6d6; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                            <pre style="text-wrap: wrap">${shiftData.pagi.catatan}`
                            if(shiftData.pagi.catatan != ''){
                            allCatatan +=`
                                                --------------------------
                                                ${shiftData.pagi.nama}<br>${tanggal} | ${shiftData.pagi.jam}
                            `}
                            allCatatan +=`
                                            </pre>
                                        </div>
                                    `
                                    if(shiftData.pagi.catatan != ''){
                                            allCatatan += `            
                                            <div class="keterangan" style="margin-top: 1%;">
                                                <div class="flex" style="display: flex; justify-content: space-between;">
                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                        onclick="updateOldList('${tanggal}', '${shiftData.pagi.jam}', '${no_rawat}', 'pagi')"
                                                    >
                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                    </div>
                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                        onclick="deleteOldList('${tanggal}', '${shiftData.pagi.jam}' , '${no_rawat}', 'pagi')"
                                                    >
                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                    </div>
                                                </div>
                                            </div>
                                            `
                                    }else{
                                    allCatatan += 
                                        `
                                            <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                            onclick="addOldList('${tanggal}', '${no_rawat}', 'pagi')"
                                            >
                                                <i class="fa-solid fa-plus"></i>
                                        </div>
                                    `
                                    }

                            allCatatan +=`
                                    </div>
                                    `
                            allCatatan +=`
                                    <div class="kelas" style="width: auto; min-width: 130px; max-width:130px; border: 1px solid #d6d6d6; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                            <pre style="text-wrap: wrap">${shiftData.siang.catatan}
                                            `
                            if(shiftData.siang.catatan != ''){
                            allCatatan +=`
                                                --------------------------
                                                ${shiftData.siang.nama}<br>${tanggal} | ${shiftData.siang.jam}
                            `}
                            allCatatan +=`
                                            </pre>
                                        </div>
                            `
                                        if(shiftData.siang.catatan != ''){
                                            allCatatan += `            
                                            <div class="keterangan" style="margin-top: 1%;">
                                                <div class="flex" style="display: flex; justify-content: space-between;">
                                                    <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                        onclick="updateOldList('${tanggal}', '${shiftData.siang.jam}', '${no_rawat}', 'siang')"
                                                    >
                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                    </div>
                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                        onclick="deleteOldList('${tanggal}', '${shiftData.siang.jam}' , '${no_rawat}', 'siang')"
                                                    >
                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                    </div>
                                                </div>
                                            </div>
                                            `
                                        }else{
                                        allCatatan += 
                                            `
                                                <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                                onclick="addOldList('${tanggal}', '${no_rawat}', 'siang')"
                                                >
                                                    <i class="fa-solid fa-plus"></i>
                                            </div>
                                        `
                                        }
                        
                            allCatatan +=`
                                    </div>
                                    `
                        
                        allCatatan += `
                                    <div class="kelas" style="width: auto; min-width: 130px; max-width:130px; center; border: 1px solid #d6d6d6; padding: 3px; border: 1px solid #d6d6d6; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="text-catatan">
                                            <pre style="text-wrap: wrap">${shiftData.malam.catatan}
                                            `
                            if(shiftData.malam.catatan != ''){
                            allCatatan +=`
                                                --------------------------
                                                ${shiftData.malam.nama}<br>${tanggal} | ${shiftData.malam.jam}
                            `}
                            allCatatan +=`
                                            </pre>
                                        </div>
                                        `
                                    if(shiftData.malam.catatan != ''){
                                        allCatatan += `            
                                        <div class="keterangan" style="margin-top: 1%;">
                                            <div class="flex" style="display: flex; justify-content: space-between;">
                                                <div class="tombol" style="padding: 5%; width: 48%; background-color:rgb(58, 78, 190); color:white; text-align:center"
                                                        onclick="updateOldList('${tanggal}', '${shiftData.malam.jam}', '${no_rawat}', 'malam')"
                                                    >
                                                        <i class="fa-solid fa-pen-to-square"></i>    
                                                    </div>
                                                    <div class="tombol" style="padding: 5%; width:48%; background-color:rgb(185, 70, 70); color:white; text-align:center"
                                                        onclick="deleteOldList('${tanggal}', '${shiftData.malam.jam}' , '${no_rawat}', 'malam')"
                                                    >
                                                        <i class="fa-solid fa-trash-can fa-lg"></i>    
                                                    </div>
                                            </div>
                                        </div>
                                        `
                                    }else{
                                    allCatatan += 
                                        `
                                            <div class="tombol" style="width: 30%; padding: 5%; background-color:rgb(96, 136, 74); color:white; text-align:center; margin-left:auto; margin-right:auto;"
                                            onclick="addOldList('${tanggal}', '${no_rawat}', 'malam')"
                                            >
                                                <i class="fa-solid fa-plus"></i>
                                        </div>
                                    `
                                    }
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

    function addOldList(tanggal, no_rawat, shift)
    {
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
    function updateOldList(tanggal, jam, no_rawat, shift)
    {
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

    function deleteOldList(tanggal, jam, no_rawat, shift)
    {
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