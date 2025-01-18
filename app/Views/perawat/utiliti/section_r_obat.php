<style>
    .small-input {
        font-size: 9px;
        padding: 3px;
    }

    .stock-out {
        background-color: black !important;
        color: white !important;
    }

    .stock-out2 {
        display: flex;
        width: 100%;
        background-color: black !important;
        color: white !important;
    }

    .stock-in {
        display: flex !important;
        width: 100%;
        background-color: rgb(143, 233, 255) !important;
        color: black !important;
    }
</style>
<div class="section-riwayat-obat" id="riwayat_obat"
    style=" padding-left: 2%;
            padding-right: 2%;
            padding-bottom: 2%;
            border-left: 1px solid;
            border-right: 1px solid;
            border-bottom: 1px solid;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            ">
    <div class="content" style="padding-top: 3%;">
        <div class="submenu" style="display: flex; margin-bottom: 2%;">
            <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                type="button" onclick="tampilRiwayat()">
                Riwayat Pemberian Obat
            </button>
            <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                type="button" onclick="unitTesting()">
                Stok Obat Pasien
            </button>
            <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                type="button" onclick="cpo()">
                Cpo
            </button>
            
        </div>

        <div class="table">
            <?= $this->include('perawat/utiliti/obat/daftar-obat-masuk') ?>
            <?= $this->include('perawat/utiliti/obat/stok-obat-pasien-v2') ?>
            <?= $this->include('perawat/utiliti/obat/cpo') ?>

        </div>
    </div>
</div>

<script>

    function unitTesting() {
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien-v2').removeAttribute('hidden');

        var tanggalfilter = document.getElementById("tanggal-filter-new").value
        stokObatNew(tanggalfilter)

    }

    function tampilRiwayat() {
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').removeAttribute('hidden');
        riwayatObat();
    }

    function cpo()
    {
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('cpo').removeAttribute('hidden');
    }

    function clearSearch() {
        document.getElementById('search-bar').value = "";

        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }

    }

    function clearSearchStok() {
        document.getElementById('search-bar-stok-obat-pasien').value = "";

        var table = document.getElementById("table-stok-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }

    }
    document.getElementById('staticBackdrop').addEventListener('hidden.bs.modal', function() {
        document.getElementById('search-bar').value = "";

        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }
        let clear = '';

        $('#table-obat').html(clear);

    });

    let responseData = [];
    let arrayObatMasuk = [];



    function stokObatNew(tanggalFilter) {
        var no_rawat = document.getElementById("obat_noRawat").value;

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
            url: '<?= base_url('obat/getStokObatPasien') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat,
                tanggal: tanggalFilter
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                // console.log(response)

                let rows = '';
                let rowsInject = '';
                const stockIn = `
                                    border-bottom: 1px solid;
                                    display: flex !important;
                                    width: 100% !important;
                                    align-items:center;
                                    color: black !important;
                                `;
                const stockOut = `
                                    border-bottom: 1px solid;
                                    display: flex !important;
                                    align-items:center;
                                    width: 100% !important;
                                    background-color: rgb(0, 0, 0) !important;
                                    color: white !important;
                                `;

                if (response.length > 0) {
                    responseData = response;
                    let currentHour = new Date().getHours();

                    response.forEach(function(item) {
                        let rowStyle = stockIn

                        if (item.sisa_stok == 0) {
                            rowStyle = stockOut
                        }
                        // console.log(item.jadwal_pemberian)
                        if(item.jenis != "Injeksi"){
                            rows += `
                                <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                                    <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                                    <div class="jml" style="width: 5%; text-align: center;">${item.jumlah}</div>
                                    <div class="sisa" style="width: 5%; text-align: center;">${item.sisa_stok}</div>
                                    <div class="aturanPakai" style="width: 20%; text-align: center;">${item.last_insert_time}</div>
                                    <div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                                    `

                                if(item.jadwal_pemberian.length > 0){
                                    // alert('ok')
                                    var jadwalPemberian = item.jadwal_pemberian
                                    jadwalPemberian.forEach(function(indexPemberian) {
                                        if(indexPemberian.status != 'diberikan'){
                                            rows += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                                        onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian.jadwal}', 
                                                                '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga_obat}')"
                                                        >${indexPemberian.jadwal}
                                                    </div>`
                                        }
                                    })
                                }
                            rows += `
                                        </div>
                                    </div>
                                `;

                        }else{
                            rowsInject += `
                                <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                                    <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                                    <div class="jml" style="width: 5%; text-align: center;">${item.jumlah}</div>
                                    <div class="sisa" style="width: 5%; text-align: center;">${item.sisa_stok}</div>
                                    <div class="aturanPakai" style="width: 20%; text-align: center;">${item.last_insert_time}</div>
                                    <div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                                    `

                                if(item.jadwal_pemberian.length > 0){
                                    // alert('ok')
                                    var jadwalPemberian = item.jadwal_pemberian
                                    jadwalPemberian.forEach(function(indexPemberian) {
                                        if(indexPemberian.status != 'diberikan'){
                                            rowsInject += `
                                                        <div style="padding: 3%; color: white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                                            onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian.jadwal}', 
                                                                    '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga_obat}')">
                                                            ${indexPemberian.jadwal}
                                                        </div>
                                                        `;
                                        }
                                    })
                                }
                                rowsInject += `
                                        </div>
                                    </div>
                                `;

                        }
                        

                    })
                }else {
                    rows += `<div style="width: 100%; text-align: center;"> Tidak Ada Stok Obat Untuk Pasien </div>`;
                    rowsInject += `<div style="width: 100%; text-align: center;"> Tidak Ada Stok Obat Injeksi Untuk Pasien </div>`;
                }
                
                $('#list-stok-obat').html(rows);
                $('#list-stok-obat-injeksi').html(rowsInject);
            }
        })

    }
    
    function savePemberianObat(kode_brng,no_rawat, tanggal, label_jadwal, kd_bangsal, no_batch, no_faktur,h_beli, harga_obat) {
        var jml = 1;
        var currentDateTime = new Date();
        var jamDevice = currentDateTime.toTimeString().split(' ')[0];
        var tanggalfilter = document.getElementById("tanggal-filter-new").value
        var biayaObat = harga_obat
        var total = harga_obat * jml;
        let inputObject = [{
                            kode_brng: kode_brng,
                            no_rawat: no_rawat,
                            jam: jamDevice,
                            jumlah: jml,
                            tanggal: tanggal,
                            periode: label_jadwal,
                            kd_bangsal: kd_bangsal,
                            no_batch: no_batch,
                            no_faktur: no_faktur,
                            h_beli: h_beli,
                            biaya_obat: biayaObat,
                            total: total
                        }];
        if (inputObject.length > 0) {

            $.ajax({
                url: '<?= base_url('obat/simpanPemberianObat') ?>',
                type: 'post',
                data: JSON.stringify(inputObject),
                contentType: 'application/json',
                success: function(response) {
                    // console.log(response);
                    if (response.status_code != 200) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Penyimpanan gagal',
                            text: response.message,
                            timer: 1000,
                            showConfirmButton: true,
                            timerProgressBar: true,
                            willClose: () => {
                                stokObatNew(tanggalfilter);
                                inputObject=[]
                                jumlah = 1;
                            }
                        });
                    } else {
                        stokObatNew(tanggalfilter);
                        inputObject=[]
                    }
                },
                error: function(error) {
                    stokObatNew(tanggalfilter);
                    alert('Terjadi kesalahan saat menyimpan data.');
                    inputObject=[]
                }
            });
        }
    }


    function getTimePeriod(hour) {
        if (hour >= 7 && hour < 12) {
            return 'Pagi';
        } else if (hour >= 12 && hour < 16) {
            return 'Siang';
        } else if (hour >= 16 && hour < 20) {
            return 'Sore';
        } else {
            return 'Malam';
        }
    }

    function isTimeInRange(currentHour, checkHour) {
        if 
        (
            (currentHour >= 7 && currentHour < 14 && checkHour >= 7 && checkHour < 14) ||
            (currentHour >= 14 && currentHour < 21 && checkHour >= 14 && checkHour < 21) ||
            (currentHour >= 21 && currentHour < 7 && checkHour >= 21 && checkHour < 7)
            // (currentHour >= 20 || currentHour < 7) && (checkHour >= 20 || checkHour < 7)
        ) 
        
        {
            return true;
        }
        return false;
    }



    function riwayatObat() {
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').removeAttribute('hidden');
        document.getElementById('radiologi').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');

        document.getElementById('btn-obat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
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

        var no_rawat = document.getElementById("obat_noRawat").value

        $.ajax({
            url: '<?= base_url('obat/getRiwayatObat') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.length > 0) {
                    let rows = '';
                    response.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${item.tgl_perawatan}</td>
                                <td>${item.jam}</td>
                                <td>${item.nama_brng}</td>
                                <td style="text-align: center;">${item.jml}</td>
                            </tr>
                        `;
                    });
                    $('#table-obat').html(rows);
                }
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

    function filterObat() {
        var input = document.getElementById("search-bar").value.toLowerCase();
        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var td = rows[i].getElementsByTagName("td")[2]; 
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(input) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }

    function filterStok() {
        var input = document.getElementById("search-bar-stok-obat-pasien");
        var filter = input.value.toLowerCase();
        var table = document.getElementById("table-stok-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                var namaObat = cells[0].textContent || cells[0].innerText;
                if (namaObat.toLowerCase().indexOf(filter) > -1) { 
                    rows[i].style.display = ""; 
                } else {
                    rows[i].style.display = "none"; 
                }
            }
        }
    }
</script>