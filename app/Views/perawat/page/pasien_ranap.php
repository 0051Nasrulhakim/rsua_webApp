<?= $this->extend('perawat/index_perawat') ?>
<?= $this->section('content') ?>
<?= $this->include('perawat/utiliti/modal_lihat_riwayat') ?>

<div class="wrapper">

    <div class="judul">
        DAFTAR PASIEN
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 21%;">NO RM</th>
                <th style="width: 32%;">NAMA PASIEN</th>
                <th style="width: 32%;">DPJP</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody id="table-body">

        </tbody>
    </table>

    <div class="section-paginasi" 
         style="text-align: center; font-size: 12px;"
    >
        <div class="pagination" style="width: 100%; display: flex; justify-content: center;">
            <button id="prev-page" disabled style="margin-right: 3%; padding-left: 2%; padding-right: 2%; border: none; border-radius: 2px; background-color: #03e3fc;">Previous</button>
            <span id="page-info" ></span>
            <button id="next-page" style="margin-left: 3%; padding-left: 2%; padding-right: 2%; border: none; border-radius: 2px; background-color: #03e3fc;">Next</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let currentPage = 1;
        const perPage = 20;

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
                    page: page
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
                        data.forEach(function(item, index) {
                            rows += `
                            <tr onclick="klikTabel('${item.no_rkm_medis}', '${item.no_rawat}', '${item.nm_pasien}')">
                                <td>${item.no_rkm_medis}</td>
                                <td>${item.nm_pasien}</td>
                                <td>${item.nm_dokter}</td>
                                <td>
                                    <button class="btn-custom-blue btn-sm" onclick="showModal('Riwayat', '${item.no_rkm_medis}', '${item.no_rawat}', '${item.nm_pasien}')">Riwayat</button>
                                    <button class="btn-custom-yellow btn-sm mt-1">Catatan</button>
                                </td>
                            </tr>
                        `;
                        });
                        $('#table-body').html(rows);

                        $('#page-info').text(`Page ${currentPage} of ${totalPages}`);
                        $('#prev-page').prop('disabled', currentPage === 1);
                        $('#next-page').prop('disabled', currentPage === totalPages);
                    } else {
                        // Jika tidak ada data
                        $('#table-body').html('<tr><td colspan="4" class="text-center">Data tidak ditemukan</td></tr>');
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

    });


    function showModal(title, no_rkm_medis, no_rawat, nama_pasien) {
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
            url: '<?= base_url('pasien/riwayatSOAPIE') ?>',
            method: 'GET',
            data: {
                norm: no_rkm_medis
            }, // Ganti dengan endpoint API Anda
            dataType: 'json',
            success: function(data) {
                // console.log(data)
                // Hapus swal loading
                Swal.close();

                // Cek apakah data tersedia
                if (data.status_code == 200) {
                    $('#staticBackdropLabel').text(title);
                    $('#noRm').val(no_rkm_medis)
                    $('#noRawat').val(no_rawat)
                    $('#contentNorm').text(no_rkm_medis)
                    $('#contentNamaPasien').text(nama_pasien)
                    $('#contentNormCatatan').text(no_rkm_medis)
                    $('#contentNamaPasienCatatan').text(nama_pasien)
                    if (data.ttv != null) {
                        $('#contentTtv').text(data.ttv.data_pemeriksaan)
                        $('#contentS').text(data.ttv.keluhan)
                        $('#contentO').text(data.ttv.pemeriksaan)
                        $('#contentA').text(data.ttv.penilaian)
                        $('#contentP').text(data.ttv.rtl)
                        $('#contentI').text(data.ttv.instruksi)
                        $('#contentE').text(data.ttv.evaluasi)
                        $('#contentRad').text(data.radiologi.hasil)
                    } else {
                        $('#contentTtv').text('-');
                    }
                    $('#staticBackdrop').modal('show');
                } else {
                    // Jika tidak ada data
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'DATA TIDAK DITEMUKAN!'
                    });
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

    function klikTabel(noRm) {
        $('#setNorm').text(noRm)
    }
</script>

<?= $this->include('perawat/utiliti/filter-box') ?>
<?= $this->endSection(); ?>