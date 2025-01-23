<style>
    .modal-dialog.modal-xl {
        max-width: 90%;
    }
</style>

<div class="modal fade" id="handOverModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="handOverModalLabel">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="handOverModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formHandover" method="post">
                    <input type="text" id="inputNoRawat" name="noRawat" hidden>
                    <input type="text" id="inputJam" name="jam" hidden>
                    <input type="text" id="inputTanggal" name="tanggal" hidden>
                    <input type="text" id="inputShift" name="shift" hidden>
                    <div class="form-floating" style="margin-top: 1%;">
                        <textarea class="form-control" placeholder="Tambahkan Catatan" id="floatingTextarea2" style="height: 200px" name="catatan"></textarea>
                        <label for="floatingTextarea2">Catatan Perawatan</label>
                    </div>
                </form>
                <div class="btn-action" style="margin-top: 3%; display: flex; justify-content: center;">
                    <div class="copy" style="margin-right: 2%" id="addBtnSection">
                        <button class="btn btn-sm btn-success" onclick="tambahkanCatatan()">Tambahkan</button>
                    </div>
                    <div class="submit" id="updateBtnSection">
                        <button type="button" class="btn btn-sm btn-success" onclick="submitCatatan()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('handOverModal').addEventListener('hidden.bs.modal', function() {
        // let tanggalSekarang = new Date().toISOString().split('T')[0]; 
        // document.getElementById('tanggalListCatatan').value = tanggalSekarang;
        
        $('#inputNoRawat').val("");
        $('#inputJam').val("");
        $('#inputTanggal').val("");
        $('#inputShift').val("");
        $('#floatingTextarea2').val("");
    });
</script>