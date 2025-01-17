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
            " hidden>
    <div class="content" style="padding-top: 3%;">
        <div class="submenu" style="display: flex; margin-bottom: 2%;">
            <!-- <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                type="button" onclick="tampilstok()">
                Stok Obat Pasien
            </button> -->
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
            
        </div>

        <div class="table">
            <?= $this->include('perawat/utiliti/obat/daftar-obat-masuk') ?>
            <?= $this->include('perawat/utiliti/obat/stok-obat-pasien-v2') ?>

        </div>
    </div>
</div>

<script>
    function tampilstok() {
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien').removeAttribute('hidden');
        var tanggalfilter = document.getElementById("tanggal-filter").value
        stokObat(tanggalfilter);

    }

    function unitTesting() {
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien-v2').removeAttribute('hidden');

        var tanggalfilter = document.getElementById("tanggal-filter-new").value
        console.log(tanggalfilter);
        stokObatNew(tanggalfilter)

    }

    function tampilRiwayat() {
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').removeAttribute('hidden');
        riwayatObat();
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
        var no_rawat = document.getElementById("catatan_noRawat").value;

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
                console.log(response)

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
                                            rows += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);">${indexPemberian.jadwal}</div>`
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
                                            rowsInject += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);">${indexPemberian.jadwal}</div>`
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
                }
                
                $('#list-stok-obat').html(rows);
                $('#list-stok-obat-injeksi').html(rowsInject);
            }
        })

    }
    // function stokObatNew(tanggalFilter) {
    //     var no_rawat = document.getElementById("catatan_noRawat").value;

    //     Swal.fire({
    //         title: 'Sedang Mengambil data...',
    //         allowOutsideClick: false,
    //         didOpen: () => {
    //             Swal.showLoading();
    //         },
    //         customClass: {
    //             title: 'swal-title-small',
    //             content: 'swal-text-small'
    //         }
    //     });

    //     $.ajax({
    //         url: '<?= base_url('obat/getStokObatPasien') ?>',
    //         method: 'GET',
    //         data: {
    //             norawat: no_rawat,
    //             tanggal: tanggalFilter
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             // console.log(response)
    //             Swal.close();
    //             let rows = '';

    //             const stockIn = `
    //                                 display: flex !important;
    //                                 width: 100% !important;
    //                                 background-color: rgb(143, 233, 255) !important;
    //                                 color: black !important;
    //                             `;
    //             const stockOut = `
    //                                 display: flex !important;
    //                                 width: 100% !important;
    //                                 background-color: rgb(0, 0, 0) !important;
    //                                 color: white !important;
    //                             `;

    //             if (response.length > 0) {
    //                 responseData = response;
    //                 let currentHour = new Date().getHours();

    //                 response.forEach(function(item) {
    //                     let rowStyle = stockIn

    //                     if (item.sisa_stok == 0) {
    //                         rowStyle = stockOut
    //                     }

    //                     rows += `
    //                         <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
    //                             <div class="namaBarang" style="width: 40%;">${item.nama_brng}</div>
    //                             <div class="jml" style="width: 10%; text-align: center;">${item.jumlah}</div>
    //                             <div class="sisa" style="width: 10%; text-align: center;">${item.sisa_stok}</div>
    //                             <div class="aturanPakai" style="width: 20%; text-align: center;">${item.aturan_pakai}</div>
    //                             <div class="aturanPakai" style="width: 20%; text-align: center;">${item.last_insert_time}</div>
    //                         </div>
    //                     `;

    //                     for (let i = 0; i < 24; i++) {
    //                         let jamKey = 'jam' + String(i).padStart(2, '0');
    //                         let timePeriod = getTimePeriod(i);
                            
    //                         if (item[jamKey] === "true") {
    //                             let isHidden = !isTimeInRange(currentHour, i) ? 'hidden' : '';
    //                             // console.log(i);
    //                             let textContent = isHidden ? 'Shift saat ini tidak tersedia untuk memasukkan obat' : '';

    //                             rows += `
    //                                 <div style="padding-left: 3%; display: flex; align-items:center; ">
    //                                     <div style="width: 15%">${i}:00 (${timePeriod})</div>
    //                                     <div style="width: 60%">
    //                                         <input type="number" min="0" 
    //                                             no-rawat="${item.no_rawat}" 
    //                                             class="form-control" 
    //                                             placeholder="Jumlah" 
    //                                             ${isHidden} 
    //                                             id="input-${item.no_rawat}-${i}"
    //                                             data-item='${JSON.stringify(item)}' 
    //                                             data-kdBrg='${item.kode_brng}',
    //                                             data-periode='${i}'
    //                                         >
    //                                         <span class="text-danger" ${isHidden ? '' : 'hidden'}>${textContent}</span>
    //                                     </div>
    //                                 </div>
    //                             `;
    //                         }
    //                     }
    //                 });
    //             } else {
    //                 rows += `<div style="width: 100%; text-align: center;"> Tidak Ada Stok Obat Untuk Pasien </div>`;
    //             }

    //             $('#list-stok-obat').html(rows);


    //             $('input[type="number"]').on('input', function() {
    //                 // let inputValue = $(this).val();
    //                 let inputValue = parseInt($(this).val(), 10);;
    //                 let itemData = $(this).data('item');
    //                 let jamKey = $(this).attr('id').split('-')[2];
    //                 var currentDateTime = new Date();
    //                 var jamDevice = currentDateTime.toTimeString().split(' ')[0];
    //                 var tanggalStok = itemData.tanggal
    //                 var periode = $(this).data('periode');
    //                 var formattedPeriode = String(periode).padStart(2, '0') + ':00';


    //                 // console.log(itemData)

    //                 if (inputValue > itemData.sisa_stok) {

    //                     Swal.fire({
    //                         icon: 'warning',
    //                         title: 'Input Melebihi Sisa Stok',
    //                         text: `Jumlah yang dimasukkan melebihi sisa stok obat. Sisa stok: ${itemData.sisa_stok}.`,
    //                     });

    //                     $(this).val(itemData.sisa_stok);
    //                     inputValue = itemData.sisa_stok
    //                     // console.log(inputValue)
    //                     // return;
    //                 }

    //                 if (inputValue > 0) {
    //                     let inputObject = {
    //                         kode_brng: itemData.kode_brng,
    //                         no_rawat: itemData.no_rawat,
    //                         jam: jamDevice,
    //                         jumlah: inputValue,
    //                         tanggal: tanggalStok,
    //                         periode: formattedPeriode
    //                     };


    //                     let existingIndex = arrayObatMasuk.findIndex(input => input.no_rawat === inputObject.no_rawat && input.kode_brng === inputObject.kode_brng);
    //                     if (existingIndex > -1) {

    //                         arrayObatMasuk[existingIndex].jumlah = inputObject.jumlah;
    //                     } else {

    //                         arrayObatMasuk.push(inputObject);
    //                     }
    //                 } else {

    //                     arrayObatMasuk = arrayObatMasuk.filter(input => input.no_rawat !== itemData.no_rawat || input.kode_brng !== itemData.kode_brng);
    //                 }

    //                 console.log(arrayObatMasuk);


    //                 if (arrayObatMasuk.length > 0) {
    //                     document.getElementById('tombol-tombol-simpan').removeAttribute('hidden')
    //                 } else {
    //                     document.getElementById('tombol-tombol-simpan').setAttribute('hidden', 'true')
    //                 }
    //             });
    //         },
    //         error: function() {
    //             Swal.close();
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Oops...',
    //                 text: 'Terjadi kesalahan saat mengambil data!'
    //             });
    //         }
    //     });
    // }

    function savePemberianObat() {
        var tanggalfilter = document.getElementById("tanggal-filter-new").value
        if (arrayObatMasuk.length > 0) {

            $.ajax({
                url: '<?= base_url('obat/simpanPemberianObat') ?>',
                type: 'post',
                data: JSON.stringify(arrayObatMasuk),
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
                                arrayObatMasuk = []
                            }
                        });
                    } else {
                        stokObatNew(tanggalfilter);
                        arrayObatMasuk = []
                    }
                },
                error: function(error) {
                    stokObatNew(tanggalfilter);
                    alert('Terjadi kesalahan saat menyimpan data.');
                    arrayObatMasuk = []
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                text: 'Tidak ada data yang disimpan karena semua nilai adalah 0.',
                timer: 1000,
                showConfirmButton: true,
                timerProgressBar: true,
            });
            stokObatNew(tanggalfilter);
            arrayObatMasuk = []
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

        var no_rawat = document.getElementById("catatan_noRawat").value
        // alert(noRawat)
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
                // if (response.length > 0) {
                //     let rows = '';
                //     let previousDate = ''; 

                //     response.forEach(function(item, index) {
                //         let currentDate = item.tgl_perawatan;

                //         if (currentDate !== previousDate) {
                //             rows += `
                //                 <tr>
                //                     <td>${currentDate}</td>
                //                     <td>${item.jam}</td>
                //                     <td>${item.nama_brng}</td>
                //                     <td style="text-align: center;">${item.jml}</td>
                //                 </tr>
                //             `;
                //             previousDate = currentDate;
                //         } else {
                //             rows += `
                //                 <tr>
                //                     <td></td>
                //                     <td>${item.jam}</td>
                //                     <td>${item.nama_brng}</td>
                //                     <td style="text-align: center;">${item.jml}</td>
                //                 </tr>
                //             `;
                //         }
                //     });

                //     $('#table-obat').html(rows);
                // }
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
            var td = rows[i].getElementsByTagName("td")[2]; // Kolom nama_brng
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
        var filter = input.value.toLowerCase(); // Dapatkan nilai pencarian dan ubah ke huruf kecil
        var table = document.getElementById("table-stok-obat");
        var rows = table.getElementsByTagName("tr"); // Ambil semua baris dalam tabel

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                var namaObat = cells[0].textContent || cells[0].innerText; // Ambil teks dari kolom pertama (nama obat)
                if (namaObat.toLowerCase().indexOf(filter) > -1) { // Cek apakah nama obat mengandung teks pencarian
                    rows[i].style.display = ""; // Tampilkan baris yang cocok
                } else {
                    rows[i].style.display = "none"; // Sembunyikan baris yang tidak cocok
                }
            }
        }
    }
</script>