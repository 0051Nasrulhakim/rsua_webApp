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

        <input type="text" id="noRawat" hidden>
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
        let limit = document.getElementById("limit").value

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
                limit: limit
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.status_code == "200") {
                    cachedData = response;
                    renderData(response);
                } else {
                    renderEmptyData(response.message);
                }
            },
            error: function(error) {
                alert('Terjadi kesalahan Silahkan Ulangi.');
                inputObject = []
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
        // console.log(data)
        let headtotalTanggal = '';
        let bodyCpo = '';

        if (Object.keys(data.list_tanggal).length > 0) {
            Object.keys(data.list_tanggal).forEach(function(tanggal) {
                headtotalTanggal += `
                    <div class="list" style="">
                        <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                            ${tanggal}
                        </div>
                        <div class="shift" style="">
                            <div class="listShift">
                                <div class="text-shift text-center">Pagi</div> 
                            </div>
                            <div class="listShift">
                                <div class="text-shift text-center">Siang</div>
                            </div>
                            <div class="listShift">
                                <div class="text-shift text-center">Sore</div>
                            </div>
                            <div class="listShift">
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

                bodyCpo += `<div class="list-body-cpo" style="display: flex; padding: 0px !important; white-space: nowrap; flex-shrink: 0;">`

                let stopTombolDitambahkan = false;

                data.cekButton.forEach(function(looptombolStop) {
                    // console.log("loop :",looptombolStop)
                    if (item.kode_brng == looptombolStop.kode_brng) {

                        if (looptombolStop.endStop == null) {
                            bodyCpo += `<div class="stopObat" 
                                                style="padding: 0.5%;border: 1px solid black; width: 50px !important; text-align: center; white-space: nowrap; flex-shrink: 0; background-color: rgb(46, 105, 161); color: white;"
                                                onClick="setLanjutkan('${item.kode_brng}', '${looptombolStop.tanggal}', '${looptombolStop.jam}', '${looptombolStop.shift}')">
                                                Lanjut
                                            </div>`;
                        } else {
                            bodyCpo += `<div class="stopObat" 
                                                style="padding: 0.5%;border: 1px solid black; width: 50px !important; text-align: center; white-space: nowrap; flex-shrink: 0; background-color: rgb(161, 46, 46); color: white;"
                                                onClick="setStop('${item.kode_brng}', '')">
                                                Stop
                                            </div>`;
                        }
                        stopTombolDitambahkan = true;
                    }
                });

                if (!stopTombolDitambahkan) {
                    bodyCpo += `<div class="stopObat" 
                                        style="padding: 0.5%;border: 1px solid black; width: 50px !important; text-align: center; white-space: nowrap; flex-shrink: 0; background-color: rgb(161, 46, 46); color: white;"
                                        onClick="setStop('${item.kode_brng}', '')">
                                        Stop
                                    </div>`;
                }
                bodyCpo += `<div class="namaobat" 
                                    style="border-bottom: 1px solid; border-right: 1px solid; width: 320px !important; padding-left: 1%; white-space: nowrap; flex-shrink: 0; display:flex; align-items: center;"
                                                                
                                >
                                    ${item.nama_brng}
                                </div>`;

                if (Object.keys(data.list_tanggal).length > 0) {
                    Object.keys(data.list_tanggal).forEach(function(tanggal) {
                        bodyCpo += `
                            <div class="shift listTr" style="">
                        `;
                        let listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                        let listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                        let listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                        let listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;

                        let parsedEndStop = {};
                        let endStop = {};

                        if (data.stopObat.length > 0) {
                            let tanggal = '';
                            data.stopObat.forEach(function(stopObat) {

                                if (!endStop[stopObat.kode_brng]) {
                                    endStop[stopObat.kode_brng] = [];
                                }
                                tanggal = stopObat.tanggal
                                endStop[stopObat.kode_brng].push({
                                    startStopShift: stopObat.shift,
                                    startStopTanggal: tanggal,
                                    endStop: stopObat.endStop,
                                    keyBarang: stopObat.kode_brng
                                });

                            });

                            Object.keys(endStop).forEach(function(kodeBrng) {
                                parsedEndStop[kodeBrng] = endStop[kodeBrng].map(item => ({
                                    endStop: item.endStop ? JSON.parse(item.endStop) : null,
                                    startStopShift: item.startStopShift || null,
                                    startStopTanggal: item.startStopTanggal || null,
                                    keyBarang: item.keyBarang
                                }));
                            });
                        }



                        if (Object.keys(parsedEndStop).length === 0) {
                            listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                            listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                            listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                            listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                        } else {
                            Object.keys(parsedEndStop).forEach(function(keyBarang) {
                                parsedEndStop[keyBarang].forEach(function(loop) {
                                    let bgColor = "black";
                                    let textColor = "white";
                                    if (tanggal >= loop.startStopTanggal && (loop.endStop == null || loop.endStop.end >= tanggal)) {
                                        if (loop.keyBarang == item.kode_brng) {
                                            if (tanggal == loop.startStopTanggal) {
                                                if(loop.endStop != null && loop.endStop.end == loop.startStopTanggal){ 
                                                    console.log(loop)
                                                    if(loop.startStopShift == 'pagi' && loop.endStop.shift == 'pagi'){
                                                        listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                    }
                                                    if(loop.startStopShift == 'pagi' && loop.endStop.shift == 'siang'){
                                                        listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                    }
                                                    if(loop.startStopShift == 'pagi' && loop.endStop.shift == 'sore'){
                                                        listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                    }
                                                    if(loop.startStopShift == 'pagi' && loop.endStop.shift == 'malam'){
                                                        listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    }
                                                    // siang
                                                    if(loop.startStopShift == 'siang' && loop.endStop.shift == 'siang'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                    }
                                                    if(loop.startStopShift == 'siang' && loop.endStop.shift == 'sore'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                    }
                                                    if(loop.startStopShift == 'siang' && loop.endStop.shift == 'malam'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    }
                                                    //sore
                                                    if(loop.startStopShift == 'sore' && loop.endStop.shift == 'sore'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                    }
                                                    if(loop.startStopShift == 'sore' && loop.endStop.shift == 'malam'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    }
                                                    // malam
                                                    if(loop.startStopShift == 'malam' && loop.endStop.shift == 'malam'){
                                                        listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    }

                                                }else{
                                                    switch (loop.startStopShift) {
                                                        case 'pagi':
                                                            listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            break;
                                                        case 'siang':
                                                            listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            break;
                                                        case 'sore':
                                                            listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            break;
                                                        case 'malam':
                                                            listPagi = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                            listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                            break;
                                                    }
                                                }
                                                
                                            } else if (tanggal > loop.startStopTanggal) {
                                                if (loop.endStop != null) {
                                                    if (tanggal == loop.endStop.end) {
                                                        switch (loop.endStop.shift) {
                                                            case 'pagi':
                                                                listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSiang = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                break;
                                                            case 'siang':
                                                                listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSore = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                break;
                                                            case 'sore':
                                                                listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listMalam = `<div class="shiftListTr" style="border: 1px solid black; background-color:${backgroundColor}; color:white">-</div>`;
                                                                break;
                                                            case 'malam':
                                                                listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                                break;
                                                        }
                                                    } else {
                                                        listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                        listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    }

                                                } else {
                                                    listPagi = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    listSiang = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    listSore = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                    listMalam = `<div class="shiftListTr" style="border:1px solid black; background-color:${bgColor}; color:${textColor}"></div>`;
                                                }
                                            }
                                        }
                                    }
                                })
                            })
                        }

                        data.list_tanggal[tanggal].forEach(function(jamPemberian) {
                            if (jamPemberian.kd_obat === item.kode_brng) {
                                if (jamPemberian.label_jam_diberikan.pagi.length > 0) {
                                    listPagi = `<div class="shiftListTr" style="border:1px solid; background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.pagi.map(p => p.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.siang.length > 0) {
                                    listSiang = `<div class="shiftListTr" style="border:1px solid; background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.siang.map(s => s.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.sore.length > 0) {
                                    listSore = `<div class="shiftListTr" style="border:1px solid; background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.sore.map(so => so.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.malam.length > 0) {
                                    listMalam = `<div class="shiftListTr" style="border:1px solid; background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.malam.map(m => m.waktu).join('<br>')}</div>`;
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

    function setStop(kd_barang) {
        let noRawat = document.getElementById('noRawat').value
        let now = new Date();
        let tanggal = now.toISOString().split('T')[0];
        let jam = now.toTimeString().split(' ')[0];
        let shift = ''
        if (jam >= "07:00:00" && jam < "12:00:00") {
            shift = "pagi";
        } else if (jam >= "12:00:00" && jam < "16:00:00") {
            shift = "siang";
        } else if (jam >= "16:00:00" && jam < "20:00:00") {
            shift = "sore";
        } else if ((jam >= "20:00:00" && jam < "24:00:00") || (jam >= "00:00:00" && jam < "07:00:00")) {
            shift = "malam";
        }

        $.ajax({
            url: '<?= base_url('obat/setStopObat') ?>',
            type: 'POST',
            data: {
                no_rawat: noRawat,
                kode_brng: kd_barang,
                tanggal: tanggal,
                jam: jam,
                shift: shift
            },
            success: function(response) {
                console.log('ok cuy')
                cpo()
            },
            error: function(error) {
                alert('Terjadi kesalahan saat menyimpan data.');
                inputObject = []
            }
        });
    }

    function setLanjutkan(kd_barang, old_tanggal, old_jam, old_shift){
        let noRawat = document.getElementById('noRawat').value
        let now = new Date();
        let tanggal = now.toISOString().split('T')[0];
        let jam = now.toTimeString().split(' ')[0];
        let shift = ''
        if (jam >= "07:00:00" && jam < "12:00:00") {
            shift = "pagi";
        } else if (jam >= "12:00:00" && jam < "16:00:00") {
            shift = "siang";
        } else if (jam >= "16:00:00" && jam < "20:00:00") {
            shift = "sore";
        } else if ((jam >= "20:00:00" && jam < "24:00:00") || (jam >= "00:00:00" && jam < "07:00:00")) {
            shift = "malam";
        }

        $.ajax({
            url: '<?= base_url('obat/lanjutkanObat') ?>',
            type: 'POST',
            data: {
                no_rawat: noRawat,
                kode_brng: kd_barang,
                tanggal: tanggal,
                jam: jam,
                shift: shift,
                old_tanggal: old_tanggal,
                old_jam: old_jam,
                old_shift: old_shift
            },
            success: function(response) {
                console.log('ok cuy')
                cpo()
            },
            error: function(error) {
                alert('Terjadi kesalahan saat menyimpan data.');
                inputObject = []
            }
        });
    }

    function renderEmptyData(message) {
        let headtotalTanggal = '';
        let bodyCpoNull = '';

        function formatDate(date) {
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        let today = new Date();

        for (let i = 0; i < 2; i++) {
            let currentDate = new Date();
            currentDate.setDate(today.getDate() - i);
            let formattedDate = formatDate(currentDate);

            headtotalTanggal += `
                                <div class="list">
                                    <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                                        ${formattedDate}
                                    </div>
                                    <div class="shift" style="display: flex; width: 100%; justify-content: center; padding: 0;">
                                        <div class="listShift">
                                            <div class="text-shift text-center">Pagi</div> 
                                        </div>
                                        <div class="listShift">
                                            <div class="text-shift text-center">Siang</div>
                                        </div>
                                        <div class="listShift">
                                            <div class="text-shift text-center">Sore</div>
                                        </div>
                                        <div class="listShift">
                                            <div class="text-shift text-center">Malam</div>
                                        </div>
                                    </div>
                                </div>
                            `;
        }
        bodyCpoNull += `
                        <div >
                            <div style="text-align: center; padding: 10px; font-size: 14px; color: black; width: 370px; border-right: 1px solid; border-bottom: 1px solid;">
                                Tidak ada riwayat pemberian obat
                            </div>
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
            },
            error: function(error) {
                alert('Terjadi kesalahan Coba Ulangi lagi.');
                inputObject = []
            }
        });
    }

    function displayStokObat(data) {
        console.log('list obat', data);
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
        const stocStop = `
                            border-bottom: 1px solid;
                            display: flex !important;
                            align-items:center;
                            width: 100% !important;
                            background-color: rgb(168, 0, 0) !important;
                            color: white !important;
                        `;

        if (data.listStokObat.length > 0) {
            data.listStokObat.forEach(function(item) {
                let rowStyle = stockIn;
                let sisaStok = ''

                if (data.sisa_obat && Array.isArray(data.sisa_obat) && data.sisa_obat.length > 0) {
                    let sisa = data.sisa_obat.find(so => so.kode_brng === item.kode_brng);

                    if (sisa) {
                        let sisa_stok = Math.max(item.jumlah_stok - sisa.jumlah, 0); 
                        if (sisa_stok === 0) {
                            rowStyle = stockOut; 
                            sisaStok = sisa_stok;
                        }
                        sisaStok = sisa_stok
                    } else {
                        sisaStok = item.jumlah_stok
                    }
                } else {
                    sisaStok = item.jumlah_stok
                }

                if (item.jenis != "Injeksi") {
                    rows += `
                        <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                            <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                            <div class="jml" style="width: 5%; text-align: center;">${item.jumlah_stok}</div>`

                    rows += `<div class="sisa" style="width: 5%; text-align: center;">${sisaStok}</div>`;
                    


                    let pemberianTerakhirDitambahkan = false;

                    if (data.pemberian_terakhir != undefined) {
                        if (data.pemberian_terakhir.length > 0) {
                            data.pemberian_terakhir.forEach(function(lastPemberian) {
                                if (lastPemberian.kode_barang == item.kode_brng) {
                                    rows += `<div class="aturanPakai" 
                                                style="width: 20%; text-align: center;">
                                                ${lastPemberian.tanggal} ${lastPemberian.jam}
                                            </div>`;
                                    pemberianTerakhirDitambahkan = true;
                                }
                            });
                        }
                    }

                    if (!pemberianTerakhirDitambahkan) {
                        rows += `<div class="aturanPakai" 
                                    style="width: 20%; text-align: center;">
                                    -
                                </div>`;
                    }


                    rows += `<div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                        `;

                    if (item.jadwal_pemberian != undefined) {
                        if (item.jadwal_pemberian.length > 0) {
                            var jadwalPemberian = item.jadwal_pemberian;
                            jadwalPemberian.forEach(function(indexPemberian) {
                                if (sisaStok !== 0) {
                                    if (data.pemberianHariIni && Array.isArray(data.pemberianHariIni)) {
                                        let sudahDiberikan = false;

                                        data.pemberianHariIni.forEach(function (pemberianHariIni) {
                                            if (item.kode_brng === pemberianHariIni.kode_barang && pemberianHariIni.label_jam_pemberian === indexPemberian) {
                                                sudahDiberikan = true; // Tandai bahwa obat sudah diberikan pada jam ini
                                            }
                                        });

                                        // Hanya tambahkan tombol jika obat belum diberikan pada jam ini
                                        if (!sudahDiberikan) {
                                            rows += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                                onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian}', 
                                                '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga}')">
                                                ${indexPemberian}
                                            </div>`;
                                        }
                                    }
                                }
                            });
                        }
                    }

                    rows += `</div></div>`;

                } else {

                    if (data.sisa_obat && Array.isArray(data.sisa_obat) && data.sisa_obat.length > 0) {
                        let sisa = data.sisa_obat.find(so => so.kode_brng === item.kode_brng);

                        // Jika ditemukan sisa stok
                        if (sisa) {
                            let sisa_stok = Math.max(item.jumlah_stok - sisa.jumlah, 0); // Menghindari nilai negatif
                            if (sisa_stok === 0) {
                                rowStyle = stockOut; 
                                sisaStok = sisa_stok;
                            }
                            sisaStok = sisa_stok
                        } else {
                            sisaStok = item.jumlah_stok
                        }
                    } else {
                        sisaStok = item.jumlah_stok
                    }
                    rowsInject += `
                        <div class="head-list-stok-obat" id="head-list-stok-obat" style="${rowStyle}">
                            <div class="namaBarang" style="width: 25%;">${item.nama_brng}</div>
                            <div class="jml" style="width: 5%; text-align: center;">${item.jumlah_stok}</div>
                            <div class="sisa" style="width: 5%; text-align: center;">${sisaStok}</div>`

                    let pemberianTerakhirDitambahkan = false;

                    if (data.pemberian_terakhir != undefined) {

                        if (data.pemberian_terakhir.length > 0) {
                            data.pemberian_terakhir.forEach(function(lastPemberian) {
                                if (lastPemberian.kode_barang == item.kode_brng) {
                                    rowsInject += `<div class="aturanPakai" style="width: 20%; text-align: center;">
                                                        ${lastPemberian.tanggal} ${lastPemberian.jam}
                                                    </div>`;
                                    pemberianTerakhirDitambahkan = true;
                                }
                            });
                        }

                    }

                    if (!pemberianTerakhirDitambahkan) {
                        rowsInject += `<div class="aturanPakai" 
                                            style="width: 20%; text-align: center;">
                                            -
                                        </div>`;
                    }


                    rowsInject += `<div class="aturanPakai" style="width: 50%; text-align: center; display:flex;">
                        `;

                    if (Array.isArray(item.jadwal_pemberian) && item.jadwal_pemberian.length > 0) {

                        // Cek apakah obat masuk dalam listObatDistop
                        let obatDistop = Array.isArray(data.listObatDistop) &&
                            data.listObatDistop.some(prosesStop => prosesStop.kode_brng === item.kode_brng);

                        if (obatDistop) {
                            rowsInject += `<div style="padding: 3%; width:100%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(105, 31, 31);">
                                                OBAT DI STOP
                                            </div>`;
                            return; // Hentikan proses agar "OBAT DI STOP" hanya muncul sekali
                        }

                        item.jadwal_pemberian.forEach(function (indexPemberian) {
                            if (sisaStok !== 0) {
                                // Cek apakah obat sudah diberikan hari ini
                                let sudahDiberikan = Array.isArray(data.pemberianHariIni) &&
                                    data.pemberianHariIni.some(pemberianHariIni =>
                                        item.kode_brng === pemberianHariIni.kode_barang &&
                                        pemberianHariIni.label_jam_pemberian === indexPemberian
                                    );

                                // Jika obat belum diberikan, tampilkan tombol
                                if (!sudahDiberikan) {
                                    rowsInject += `<div style="padding: 3%; color:white; margin-right: 3%; border-radius: 3px; background-color: rgb(13, 119, 4);"
                                                        onclick="savePemberianObat('${item.kode_brng}', '${item.no_rawat}', '${item.tanggal}', '${indexPemberian}', 
                                                        '${item.kd_bangsal}', '${item.no_batch}', '${item.no_faktur}', '${item.h_beli}', '${item.harga}')">
                                                        ${indexPemberian}
                                                    </div>`;
                                }
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
        var input = document.getElementById("searchInput-stok").value.trim().toLowerCase();

        // Cek apakah stokObatData tersedia
        if (!stokObatData || !stokObatData.listStokObat || stokObatData.listStokObat.length === 0) {
            $('#list-stok-obat').html('<div style="width: 100%; text-align: center;">Data stok obat tidak tersedia</div>');
            return;
        }

        // Filter data berdasarkan nama obat
        var filteredData = stokObatData.listStokObat.filter(item =>
            item.nama_brng.toLowerCase().includes(input)
        );

        console.log('Filtered Data:', filteredData);

        // Jika tidak ada hasil pencarian
        if (filteredData.length === 0) {
            $('#list-stok-obat').html('<div style="width: 100%; text-align: center;">Tidak ada stok obat dengan nama tersebut</div>');
        } else {
            // Pastikan semua data yang dibutuhkan oleh displayStokObat() tetap dikirim
            displayStokObat({
                listStokObat: filteredData,
                sisa_obat: stokObatData.sisa_obat || [],
                pemberian_terakhir: stokObatData.pemberian_terakhir || [],
                pemberianHariIni: stokObatData.pemberianHariIni || []
            });
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
            // tanggal: tanggal,
            tanggal: tanggalfilter,
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
                                // jumlah = 1;
                            }
                        });
                    } else {
                        stokObatNew(tanggalfilter);
                        inputObject = []
                    }
                },
                error: function(error) {
                    stokObatNew(tanggalfilter);
                    alert('Terjadi kesalahan saat menyimpan data. silahkan ulangi lagi');
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