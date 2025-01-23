<?= $this->extend('webview/index_perawat') ?>
<?= $this->section('content') ?>

<div class="cpo" id="cpo" style="padding: 2%;">
    <!-- <div class="section-cpo" id="section-cpo" style="overflow-x: auto;"> -->
    <input type="text" id="noRawat" placeholder="Cari Obat" value="<?= $norawat?>" readonly hidden>
     <div class="filter-riwayat" style="display: flex; align-items: center;">
        <div class="text-custom" style="width: 12%;">
            Cari Nama Obat
        </div>
        <div class="input" style="width: 30%; display: flex;">
            <input class="form-control" type="text" id="searchObatInput" placeholder="Cari Nama Obat" onkeyup="filterData()">
            <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearchRiwayat()">
                X
            </div>
        </div>
    </div>

    <div class="wrapscroll" style="overflow-x: auto; margin-top: 2%;" >
        <div class="headList" style="display: flex; padding: 0;">
            <div class="namaobat" style="text-align: center; width: 200px !important; padding: 0; white-space: nowrap; flex-shrink: 0; border-bottom: 1px solid; border-left: 1px solid;border-top: 1px solid; display: flex; align-items: center; justify-content: center;">
                NAMA OBAT / ALKES / BHP
            </div>
            <div class="total-tanggal" id="totalTanggal" style="display: flex;">
                
            </div>

        </div>

        <div class="body" id="bodyCpo" style="border-left: 1px solid; padding: 0; white-space: nowrap; flex-shrink: 0;">

        </div>

    </div>
</div>


</div>

<script>
   $(document).ready(function() {
    // your code here
    cpo()
   });

    var cachedData = null;

    function cpo() {

        var no_rawat = document.getElementById("noRawat").value;
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
                    <div class="list" style="width: 280px !important; text-align: center; padding: 0; border: 1px solid;">
                        <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                            ${tanggal}
                        </div>
                        <div class="shift" style="display: flex; width: 100%; justify-content: center; padding: 0;">
                            <div class="pagi" style="width: 70px !important; padding: 0;">
                                <div class="text-shift text-center">Pagi</div> 
                            </div>
                            <div class="siang" style="width: 70px !important; padding: 0;">
                                <div class="text-shift text-center">Siang</div>
                            </div>
                            <div class="sore" style="width: 70px !important; padding: 0;">
                                <div class="text-shift text-center">Sore</div>
                            </div>
                            <div class="malam" style="width: 70px !important; padding: 0;">
                                <div class="text-shift text-center">Malam</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        if (data.daftar_nama_obat.length > 0) {
            data.daftar_nama_obat.forEach(function(item) {
                bodyCpo += `<div class="list-body-cpo" style="display: flex; padding: 0px !important; white-space: nowrap; flex-shrink: 0;">
                    <div class="namaobat" style="border-bottom: 1px solid; border-right: 1px solid; width: 200px !important; padding-left: 1%; white-space: nowrap; flex-shrink: 0;">
                        ${item.nama_brng}
                    </div>`;

                if (Object.keys(data.list_tanggal).length > 0) {
                    Object.keys(data.list_tanggal).forEach(function(tanggal) {
                        bodyCpo += `
                            <div class="shift" style="width: 280px !important; display: flex; justify-content: center; padding: 0; text-align: center; min-width: 200px;">
                        `;

                        let listPagi = '<div class="pagi" style="width: 70px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:rgb(61, 196, 122); color:white">-</div>';
                        let listSiang = '<div class="siang" style="width: 70px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:rgb(61, 196, 122); color:white">-</div>';
                        let listSore = '<div class="sore" style="width: 70px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:rgb(61, 196, 122); color:white">-</div>';
                        let listMalam = '<div class="malam" style="width: 70px !important; padding: 0; border-right: 1px solid white; border-bottom: 1px solid white; background-color:rgb(61, 196, 122); color:white">-</div>';

                        data.list_tanggal[tanggal].forEach(function(jamPemberian) {
                            if (jamPemberian.kd_obat === item.kode_brng) {
                                if (jamPemberian.label_jam_diberikan.pagi.length > 0) {
                                    listPagi = `<div class="pagi" style="width: 70px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.pagi.map(p => p.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.siang.length > 0) {
                                    listSiang = `<div class="siang" style="width: 70px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.siang.map(s => s.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.sore.length > 0) {
                                    listSore = `<div class="sore" style="width: 70px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.sore.map(so => so.waktu).join('<br>')}</div>`;
                                }
                                if (jamPemberian.label_jam_diberikan.malam.length > 0) {
                                    listMalam = `<div class="malam" style="width: 70px !important; padding: 0; border-right: 1px solid rgb(61, 196, 122); border-bottom: 1px solid rgb(61, 196, 122); background-color:rgb(255, 255, 255); color:black">${jamPemberian.label_jam_diberikan.malam.map(m => m.waktu).join('<br>')}</div>`;
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
                                <div class="list" style="width: 280px !important; text-align: center; padding: 0; border: 1px solid;">
                                    <div class="tanggal" style="padding: 0; border-bottom: 1px solid;">
                                        ${formattedDate}
                                    </div>
                                    <div class="shift" style="display: flex; width: 100%; justify-content: center; padding: 0;">
                                        <div class="pagi" style="width: 70px !important; padding: 0;">
                                            <div class="text-shift text-center">Pagi</div> 
                                        </div>
                                        <div class="siang" style="width: 70px !important; padding: 0;">
                                            <div class="text-shift text-center">Siang</div>
                                        </div>
                                        <div class="sore" style="width: 70px !important; padding: 0;">
                                            <div class="text-shift text-center">Sore</div>
                                        </div>
                                        <div class="malam" style="width: 70px !important; padding: 0;">
                                            <div class="text-shift text-center">Malam</div>
                                        </div>
                                    </div>
                                </div>
                            `;
        }
        bodyCpoNull += `
                            <div style="text-align: center; padding: 10px; font-size: 14px; color: black; width: 759px; border-right: 1px solid; border-bottom : 1px solid;">
                                Tidak ada riwayat pemberian obat
                            </div>
                        `;

        $('#totalTanggal').html(headtotalTanggal);
        $('#bodyCpo').html(bodyCpoNull);
    }

</script>

<?= $this->endSection(); ?>