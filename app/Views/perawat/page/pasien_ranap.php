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

</div>

<script>
    $(document).ready(function() {
        // Tampilkan swal loading sebelum memulai AJAX
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

        // Lakukan AJAX GET
        $.ajax({
            url: '<?= base_url('pasien/getPasien') ?>', // Ganti dengan endpoint API Anda
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Hapus swal loading
                Swal.close();

                // Cek apakah data tersedia
                if (data.length > 0) {
                    let rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${item.no_rkm_medis}</td>
                                <td>${item.nm_pasien}</td>
                                <td>${item.nm_dokter}</td>
                                <td>
                                    <button class="btn-custom-blue btn-sm" onclick="showModal('Riwayat', '${item.no_rkm_medis}', '${item.no_rawat}')">Riwayat</button>
                                    <button class="btn-custom-yellow btn-sm mt-1">Catatan</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#table-body').html(rows);
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
    });


    function showModal(title, no_rkm_medis, no_rawat) {
        $('#staticBackdropLabel').text(title);
        $('#noRm').val(no_rkm_medis)
        // $('#noRawat').val(no_rawat)
        // $('.modal-body').html(`Nomor Rekam Medis: ${no_rkm_medis}`);
        $('#staticBackdrop').modal('show');
    }
</script>

<?= $this->include('perawat/utiliti/filter-box') ?>
<?= $this->endSection(); ?>