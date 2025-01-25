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
    style="
            padding-bottom: 2%;
            padding-left: 1%;
            padding-right: 1%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            ">
    <div class="content" style="padding-top: 3%;">
        <div class="submenu" style="display: flex; margin-bottom: 2%;">
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
    function clearSearchStok() {
        document.getElementById('searchInput-stok').value = '';
        searchObat();
    }

    function unitTesting() {
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('cpo').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien-v2').removeAttribute('hidden');
        document.getElementById("searchObatInput").value = '';

        var tanggalfilter = document.getElementById("tanggal-filter-new").value
        stokObatNew(tanggalfilter)

    }

    function tampilRiwayat() {
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('cpo').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').removeAttribute('hidden');
        riwayatObat();
    }

    var cachedData = null;
    function cpo() {
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('cpo').removeAttribute('hidden');

        var no_rawat = document.getElementById("obat_noRawat").value;
        document.getElementById("searchObatInput").value = '';

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
            url: '<?= base_url('obat/getCpo') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat,
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.status_code == "200") {
                    cachedData = response;
                    console.log(response)
                    renderData(response);
                } else {
                    renderEmptyData(response.message);
                }
            }
        });
    }

    function filterData() {
        var filterNamaObat = document.getElementById("searchObatInput").value.toLowerCase();
        if (cachedData) {
            var filteredData = {
                ...cachedData,
                daftar_nama_obat: cachedData.daftar_nama_obat.filter(function(item) {
                    return item.nama_brng.toLowerCase().includes(filterNamaObat);
                })
            };
            renderData(filteredData);
        }
    }

    function renderData(data) {
        let headtotalTanggal = '';
        let bodyCpo = '';

        if (Object.keys(data.list_tanggal).length > 0) {
            Object.keys(data.list_tanggal).forEach(function(tanggal) {
                headtotalTanggal += `
                    <div class="list" style="width: 200px !important; text-align: center; padding: 0; border: 1px solid;">
                        <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                            ${tanggal}
                        </div>
                        <div class="shift" style="display: flex; width: 100%; justify-content: center; padding: 0;">
                            <div class="pagi" style="width: 50px !important; padding: 0;">
                                <div class="text-shift text-center">Pagi</div> 
                            </div>
                            <div class="siang" style="width: 50px !important; padding: 0;">
                                <div class="text-shift text-center">Siang</div>
                            </div>
                            <div class="sore" style="width: 50px !important; padding: 0;">
                                <div class="text-shift text-center">Sore</div>
                            </div>
                            <div class="malam" style="width: 50px !important; padding: 0;">
                                <div class="text-shift text-center">Malam</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        if (data.daftar_nama_obat.length > 0) {
            data.daftar_nama_obat.forEach(function(item, index) {
                const backgroundColor = index % 2 === 0 ? 'rgb(61, 196, 122)' : 'rgb(47, 160, 98)';
                bodyCpo += `<div class="list-body-cpo" style="display: flex; padding: 0px !important; white-space: nowrap; flex-shrink: 0;">
                    <div class="namaobat" style="border-bottom: 1px solid; border-right: 1px solid; width: 200px !important; padding-left: 1%; white-space: nowrap; flex-shrink: 0;">
                        ${item.nama_brng}
                    </div>`;

                if (Object.keys(data.list_tanggal).length > 0) {
                    Object.keys(data.list_tanggal).forEach(function(tanggal) {
                        bodyCpo += `
                            <div class="shift" style="width: 200px !important; display: flex; justify-content: center; padding: 0; text-align: center; min-width: 200px;">
                        `;

                        let listPagi = `<div class="pagi" style="width: 50px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:${backgroundColor}; color:white">-</div>`;
                        let listSiang = `<div class="siang" style="width: 50px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:${backgroundColor}; color:white">-</div>`;
                        let listSore = `<div class="sore" style="width: 50px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:${backgroundColor}; color:white">-</div>`;
                        let listMalam = `<div class="malam" style="width: 50px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:${backgroundColor}; color:white">-</div>`;

                        data.list_tanggal[tanggal].forEach(function(jamPemberian) {
                            if (jamPemberian.kd_obat === item.kode_brng) {
                                if (jamPemberian.label_jam_diberikan.pagi.length > 0) {
                                    listPagi = `<div class="pagi" style="width: 50px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.pagi.map(p => p.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.siang.length > 0) {
                                    listSiang = `<div class="siang" style="width: 50px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.siang.map(s => s.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.sore.length > 0) {
                                    listSore = `<div class="sore" style="width: 50px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.sore.map(so => so.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.malam.length > 0) {
                                    listMalam = `<div class="malam" style="width: 50px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.malam.map(m => m.waktu).join('<br>')}</div>`;
                                }
                            }
                        });

                        bodyCpo += listPagi + listSiang + listSore + listMalam + `</div>`;
                    });
                }
                bodyCpo += `</div>`;
            });
        }

        $('#totalTanggal').html(headtotalTanggal);
        $('#bodyCpo').html(bodyCpo);
    }

    function renderEmptyData(message) {
        let headtotalTanggal = '';
        let bodyCpoNull = '';

        function formatDate(date) {
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
            let year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        let today = new Date();

        for (let i = 0; i < 2; i++) {
            let currentDate = new Date();
            currentDate.setDate(today.getDate() - i);
            let formattedDate = formatDate(currentDate);

            headtotalTanggal += `
                                <div class="list" style="width: 200px !important; text-align: center; padding: 0; border: 1px solid;">
                                    <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                                        ${formattedDate}
                                    </div>
                                    <div class="shift" style="display: flex; width: 100%; justify-content: center; padding: 0;">
                                        <div class="pagi" style="width: 50px !important; padding: 0;">
                                            <div class="text-shift text-center">Pagi</div> 
                                        </div>
                                        <div class="siang" style="width: 50px !important; padding: 0;">
                                            <div class="text-shift text-center">Siang</div>
                                        </div>
                                        <div class="sore" style="width: 50px !important; padding: 0;">
                                            <div class="text-shift text-center">Sore</div>
                                        </div>
                                        <div class="malam" style="width: 50px !important; padding: 0;">
                                            <div class="text-shift text-center">Malam</div>
                                        </div>
                                    </div>
                                </div>
                            `;
        }
        bodyCpoNull += `
                            <div style="text-align: center; padding: 10px; font-size: 14px; color: black; width: 599px; border-right: 1px solid; border-bottom : 1px solid;">
                                Tidak ada riwayat pemberian obat
                            </div>
                        `;

        $('#totalTanggal').html(headtotalTanggal);
        $('#bodyCpo').html(bodyCpoNull);
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
    let stokObatData = [];

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
                stokObatData = response;

                displayStokObat(stokObatData);
            }
        });
    }

    function displayStokObat(data) {
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

        if (data.length > 0) {
            data.forEach(function(item) {
                let rowStyle = stockIn;

                if (item.sisa_stok == 0) {
                    rowStyle = stockOut;
                }
                if (item.jenis != "Injeksi") {
                    rows += `
                        <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                            <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                            <div class="jml" style="width: 5%; text-align: center;">${item.jumlah}</div>
                            <div class="sisa" style="width: 5%; text-align: center;">${item.sisa_stok}</div>
                            <div class="aturanPakai" style="width: 20%; text-align: center;">${item.last_insert_time}</div>
                            <div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                        `;

                    if (item.jadwal_pemberian.length > 0) {
                        var jadwalPemberian = item.jadwal_pemberian;
                        jadwalPemberian.forEach(function(indexPemberian) {
                            if (indexPemberian.status != 'diberikan') {
                                rows += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                            onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian.jadwal}', 
                                                    '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga_obat}')"
                                            >${indexPemberian.jadwal}
                                        </div>`;
                            }
                        });
                    }
                    rows += `</div></div>`;

                } else {
                    rowsInject += `
                        <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                            <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                            <div class="jml" style="width: 5%; text-align: center;">${item.jumlah}</div>
                            <div class="sisa" style="width: 5%; text-align: center;">${item.sisa_stok}</div>
                            <div class="aturanPakai" style="width: 20%; text-align: center;">${item.last_insert_time}</div>
                            <div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                    `;

                    if (item.jadwal_pemberian.length > 0) {
                        var jadwalPemberian = item.jadwal_pemberian;
                        jadwalPemberian.forEach(function(indexPemberian) {
                            if (indexPemberian.status != 'diberikan') {
                                rowsInject += `
                                                <div style="padding: 3%; color: white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                                    onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian.jadwal}', 
                                                            '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga_obat}')">
                                                    ${indexPemberian.jadwal}
                                                </div>
                                            `;
                            }
                        });
                    }
                    rowsInject += `</div></div>`;
                }
            });
        } else {
            rows += `<div style="width: 100%; text-align: center;"> Tidak Ada Stok Obat Untuk Pasien </div>`;
            rowsInject += `<div style="width: 100%; text-align: center;"> Tidak Ada Stok Obat Injeksi Untuk Pasien </div>`;
        }

        $('#list-stok-obat').html(rows);
        $('#list-stok-obat-injeksi').html(rowsInject);
    }

    function searchObat() {
        var input = document.getElementById("searchInput-stok").value.toLowerCase();
        var filteredData = stokObatData.filter(function(item) {
            return item.nama_brng.toLowerCase().includes(input);
        });

        if (filteredData.length === 0) {
            $('#list-stok-obat').html('<div style="width: 100%; text-align: center;">Tidak ada stok obat dengan nama tersebut</div>');
            $('#list-stok-obat-injeksi').html('<div style="width: 100%; text-align: center;">Tidak ada stok obat dengan nama tersebut</div>');
        } else {
            // Tampilkan hasil pencarian
            displayStokObat(filteredData);
        }
    }


    function savePemberianObat(kode_brng, no_rawat, tanggal, label_jadwal, kd_bangsal, no_batch, no_faktur, h_beli, harga_obat) {
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
                                inputObject = []
                                jumlah = 1;
                            }
                        });
                    } else {
                        stokObatNew(tanggalfilter);
                        inputObject = []
                    }
                },
                error: function(error) {
                    stokObatNew(tanggalfilter);
                    alert('Terjadi kesalahan saat menyimpan data.');
                    inputObject = []
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
        if (
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