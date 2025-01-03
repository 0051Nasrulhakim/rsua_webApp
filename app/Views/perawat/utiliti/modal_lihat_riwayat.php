<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="insertCatatan" method="post">
                    <input type="text" id="noRm" name="noRm" hidden>
                    <input type="text" id="noRawat" name="noRawat" hidden>
                    <?= $this->include('perawat/utiliti/content-modal-tab') ?>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn-custom btn-secondary red" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-custom btn-primary blue">Tambah Catatan</button>
            </div> -->
        </div>
    </div>
</div>

<script>
    $('#insertCatatan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('pasien/saveCatatan_perawatan')?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                // your code here
            }
        });
    })
    // Tambahkan event listener untuk reset modal saat ditutup
    document.getElementById('staticBackdrop').addEventListener('hidden.bs.modal', function() {
        // Reset semua input di dalam modal
        document.getElementById('noRm').value = '';
        document.getElementById('noRawat').value = '';

        // document.getElementById('btn-catatan').removeAttribute('hidden');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('section-riwayat').removeAttribute('hidden');
        document.getElementById('btn-catatan').removeAttribute('hidden');
        document.getElementById('floatingTextarea2').value = '';

        $('#contentTtv').text("")
        $('#contentS').text("")
        $('#contentO').text("")
        $('#contentA').text("")
        $('#contentP').text("")
        $('#contentI').text("")
        $('#contentE').text("")
        $('#contentRad').text("")
        

    });
</script>