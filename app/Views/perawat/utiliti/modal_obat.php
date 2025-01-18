<style>
    .modal-dialog.modal-xl {
        max-width: 90%;
        /* Mengubah lebar maksimal modal */
    }
</style>

<div class="modal fade" id="modalObat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalObatLabel">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalObatLabel">OBAT</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="section-nama" style="display: flex; padding-top: 3%; justify-content: center; font-weight: 700; margin-bottom: 3%">
                    <div class="norm" id="contentNormSObat" style=""></div>
                    <div class="" style="width: 3%; text-align: center;">-</div>
                    <div class="nama" id="contentNamaPasienSObat"></div>
                </div>

                <div class="diagnosa" style="margin-bottom: 3%;">
                    <div class="diagnosaAwal" style="display: flex;">
                        <div class="title">
                            Diagnosa Awal
                        </div>
                        <div class="semicolon" style="width:  2%; text-align: center;">
                            :
                        </div>
                        <div class="contentDiagnosaAkhir" id="contentDiagnosaAwalSObat">
                        </div>
                    </div>
                    <div class="diagnosaAkhir" style="display: flex;">
                        <div class="title">
                            Diagnosa Akhir
                        </div>
                        <div class="semicolon" style="width:  2%; text-align: center;">
                            :
                        </div>
                        <div class="contentDiagnosaAkhir" id="contentDiagnosaAkhirSObat">

                        </div>
                    </div>
                </div>

                <div class="type">
                    <div class="section-menu">

                        <div class="menu-1" onclick="riwayatObat()" id="btn-obat">
                            Data Obat Pasien
                        </div>

                    </div>
                </div>

                <div class="riwayat" id="section-modal-riwayat">

                </div>

                <form id="insertCatatan" method="post">
                    <input type="text" id="obat_noRm" name="noRm" hidden>
                    <input type="text" id="obat_noRawat" name="noRawat" hidden>
                    <input type="text" id="obat_tanggal" name="tanggal" hidden>
                    <input type="text" id="obat_jam" name="jam" hidden>
                    <?= $this->include('perawat/utiliti/section_r_obat') ?>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('modalObat').addEventListener('hidden.bs.modal', function() {
        var currentDate = new Date();
        var currentDateString = currentDate.toISOString().split('T')[0];
        document.getElementById('tanggal-filter-new').value = currentDateString
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').removeAttribute('hidden');;
        $('#list-stok-obat').html("");
        $('#list-stok-obat-injeksi').html("");
        $('#list-stok-obat-injeksi').html("");

    })
</script>