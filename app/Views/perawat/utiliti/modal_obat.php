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

                <div class="modal-body-cpo">
                    
                    <div class="section-nama" style="display: flex; padding-top: 3%; justify-content: center; font-weight: 700; margin-bottom: 3%" hidden>
                        <div class="norm" id="contentNormSObat" style=""></div>
                        <div class="" style="width: 3%; text-align: center;">-</div>
                        <div class="nama" id="contentNamaPasienSObat"></div>
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
</div>

<script>
    document.getElementById('modalObat').addEventListener('hidden.bs.modal', function() {
        var currentDate = new Date();
        var currentDateString = currentDate.toISOString().split('T')[0];
        document.getElementById('tanggal-filter-new').value = currentDateString
        document.getElementById('stok-obat-pasien-v2').setAttribute('hidden', 'true');
        document.getElementById('cpo').removeAttribute('hidden');;
        $('#list-stok-obat').html("");
        $('#list-stok-obat-injeksi').html("");
        $('#totalTanggal').html("");
        $('#bodyCpo').html("");
        document.getElementById('searchInput-stok').value = '';
        // cpo()
        document.getElementById("limit").value = '3'
    })
</script>