<?= $this->extend('perawat/index_perawat') ?>
<?= $this->section('content') ?>
<?= $this->include('perawat/utiliti/modal_lihat_riwayat') ?>
<?= $this->include('perawat/utiliti/modal_lihat_catatan') ?>

<div class="wrapper">

    <div class="judul">
        DAFTAR PASIEN RAWAT INAP
    </div>

    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%;">
        <label for="filter-doctor">Filter Dokter :</label>
        <select id="filter-doctor">
            <option value="">Semua Dokter</option>
        </select>
    </div>

    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 5%;">Kelas</th>
                    <th style="width: 22%; ">NAMA PASIEN</th>
                    <th style="width: 25%; ">DPJP</th>
                    <th class="rowDiagnosaAkhir text-center">Dx.</th>
                    <th class="rowCatatan text-center">Catatan Terakhir</th>
                    <th style="width: 10%; text-align: center;">Jns. Bayar</th>
                    <th style="width: 9%; text-align: center;">Hari Ke-</th>
                    <th style="width: 7%;">ACTION</th>
                </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
        </table>
    </div>

    <div class="section-paginasi"
        style="text-align: center; font-size: 12px;" hidden>
        <div class="pagination" style="width: 100%; display: flex; justify-content: center;">
            <button class="btn-prev" id="prev-page" disabled>Previous</button>
            <span class="count_page" id="page-info"></span>
            <button class="btn-next" id="next-page">Next</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let currentPage = 1;
        const perPage = 20;
        let selectedDoctor = '';
        window.addEventListener('dataRefreshed', function() {
            fetchData(currentPage);
        });

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

        function fetchData(page) {
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
                url: '<?= base_url('pasien/getPasien') ?>',
                method: 'GET',
                data: {
                    page: page,
                    kd_dokter: selectedDoctor
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    const {
                        data,
                        totalPages,
                        currentPage
                    } = response;

                    if (data.length > 0) {
                        let rows = '';
                        data.forEach(function(item) {
                            rows += `
                            <tr onclick="klikTabel('${item.no_rkm_medis}', '${item.no_rawat}', '${item.nm_pasien}')">
                                <td>${item.kd_kamar}</td>
                                <td>${item.nm_pasien}</td>
                                <td>${item.dokter_dpjp ? item.dokter_dpjp : ''}</td>
                                <td class="rowDiagnosaAkhir text-center">${item.nama_penyakit}</td>
                                <td class="rowCatatan text-center">${item.catatan_terakhir ? item.catatan_terakhir : ''}</td>
                                <td class="text-center">${item.png_jawab}</td>
                                <td style="text-align: center;">${item.lama_inap}</td>
                                <td>
                                    <button class="btn-custom-blue btn-sm" onclick="ShowRiwayat('Riwayat', '${item.no_rkm_medis}', '${item.no_rawat}', '${item.nm_pasien}')">Riwayat</button>
                                    <button class="btn-custom-yellow btn-sm mt-1" onclick="showCatatan('Catatan', '${item.no_rkm_medis}', '${item.no_rawat}', '${item.nm_pasien}')" hidden>Catatan</button>
                                </td>
                            </tr>
                        `;
                        });

                        $('#table-body').html(rows);
                        $('#page-info').text(`Page ${currentPage} of ${totalPages}`);
                        $('#prev-page').prop('disabled', currentPage === 1);
                        $('#next-page').prop('disabled', currentPage === totalPages);
                    } else {
                        $('#table-body').html('<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengambil data pasien!'
                    });
                }
            });
        }

        fetchData(currentPage);

        $('#prev-page').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                fetchData(currentPage);
            }
        });

        $('#next-page').on('click', function() {
            currentPage++;
            fetchData(currentPage);
        });

        $('#filter-doctor').on('change', function() {
            selectedDoctor = $(this).val();
            currentPage = 1;
            fetchData(currentPage);
        });
    });



    function ShowRiwayat(title, no_rkm_medis, no_rawat, nama_pasien) {
        $('#catatan_noRm').val(no_rkm_medis);
        $('#catatan_noRawat').val(no_rawat);

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
            url: '<?= base_url('pasien/getAllRiwayat') ?>',
            method: 'GET',
            data: {
                norm: no_rkm_medis
            },
            dataType: 'json',
            success: function(data) {
                Swal.close();

                let rows = '';
                $('#staticBackdropLabel').text(title);

                if (data && data.pemeriksaan) {
                    const pemeriksaanKeys = Object.keys(data.pemeriksaan);

                    const kategoriMap = {
                        'source': 'Status',
                        'nama': 'Petugas',
                        'ttv': 'Ttv',
                        'keluhan': 'S',
                        'pemeriksaan': 'O',
                        'penilaian': 'A',
                        'rtl': 'P',
                        'instruksi': 'I',
                        'evaluasi': 'E'
                    };

                    $('#contentNorm').text(no_rkm_medis);
                    $('#contentNamaPasien').text(nama_pasien);



                    pemeriksaanKeys.forEach(key => {
                        const pemeriksaanData = data.pemeriksaan[key];
                        if (data.pemeriksaan[key][0].tgl_perawatan == "-" || data.pemeriksaan[key][0].jam_rawat == "-") {
                            return;
                        }
                        // console.log(data.pemeriksaan[key][0])

                        rows += `<div class="section-riwayat" style="margin-bottom: 2%;">`;
                        rows += `<div class="noRawat" style="border: 1px solid; text-align: center; background-color: #dedede; font-weight:700">${key}</div>`;

                        pemeriksaanData.forEach(item => {
                            if (!item.tgl_perawatan || !item.jam_rawat || item.tgl_perawatan == "-" || item.jam_rawat == '-') {
                                return;
                            }

                            rows += `<div class="section-content-riwayat" style="padding-left: 2%; padding-right: 2%; padding-bottom: 2%; border-left: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">`;

                            rows += `
                                    <div class="content">
                                        <div class="waktu" style="text-align: right;">
                                            ${item.tgl_perawatan} ${item.jam_rawat}
                                        </div>
                                    </div>`;

                            if (item.source === 'radiologi' && item.hasil !== '-') {
                                rows += `<div class="wrapper-modal" style="display: flex;">
                                            <div class="kaategori" style="width: 13%; text-align: center;">Radiologi</div>
                                            <div class="semicolon">:</div>
                                            <div class="content" style="width: 80%; margin-left: 1%;">${item.hasil || ''}</div>
                                        </div>`;
                            } else {
                                for (const [jsonKey, kategori] of Object.entries(kategoriMap)) {
                                    rows += `<div class="wrapper-modal" style="display: flex;">
                                                <div class="kaategori" style="width: 11%; text-align: center;">${kategori}</div>
                                                <div class="semicolon">:</div>
                                                <div class="content" style="width: 80%; margin-left: 1%;">${item[jsonKey] && item[jsonKey] !== '-' ? item[jsonKey] : '-'}</div>
                                            </div>`;
                                }
                            }

                            rows += `</div>`;
                        });

                        rows += `</div>`;
                    });
                }

                $('#section-modal-riwayat').html(rows);
                $('#staticBackdrop').modal('show');
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

    function klikTabel(noRm) {
        $('#setNorm').text(noRm)
    }

    function showCatatan(title, no_rkm_medis, no_rawat, nama_pasien) {
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

        document.getElementById('modalNamaPasien').innerText = nama_pasien;
        document.getElementById('modalNoRkmMedis').innerText = no_rkm_medis;
        document.getElementById('modalNoRawat').innerText = no_rawat;
        $('#catatanModal').modal('show');

        $.ajax({
            url: '<?= base_url('pasien/getCatatan') ?>',
            method: 'GET',
            data: {
                noRawat: no_rawat
            },
            dataType: 'json',
            success: function(data) {

                Swal.close();
                if (data.length > 0) {
                    let rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                            <div class="content">
                                <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                    ${item.tanggal +' '+item.jam}
                                </div>
                                <div class="isi" style="padding: 2%; border: 1px solid;" id="isCatatan">
                                    ${item.catatan}
                                </div>
                            </div>
                        `;
                    });
                    $('#wrapperCatatan').html(rows);
                }


            },

            error: function() {
                // Hapus swal loading
                Swal.close();

                // Tampilkan swal error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil data!'
                });
            }
        });

    }
</script>

<?= $this->endSection(); ?>