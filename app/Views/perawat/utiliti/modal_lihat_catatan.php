<div class="modal fade" id="catatanModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="catatanModalLabel" >
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="catatanModalLabel">Catatan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="norm" style="display: flex;">
                    <div class="title" style="width: 14%;">No RM</div>
                    <div class="" style="width: 4%; text-align: center;">:</div>
                    <div class="isi" id="modalNoRkmMedis"></div>
                </div>
                <div class="noRawat" style="display: flex;">
                    <div class="title" style="width: 14%;">No Rawat</div>
                    <div class="" style="width: 4%; text-align: center;">:</div>
                    <div class="isi" id="modalNoRawat"></div>
                </div>
                <div class="nama" style="display: flex; margin-bottom: 2%;">
                    <div class="title" style="width: 14%;">Nama</div>
                    <div class="" style="width: 4%; text-align: center;">:</div>
                    <div class="isi" id="modalNamaPasien"></div>
                </div>

                <div class="wrapper-catatan" id="wrapperCatatan" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('catatanModal').addEventListener('hidden.bs.modal', function() {

        $('#modalNoRkmMedis').text("")
        $('#modalNoRawat').text("")
        $('#modalNamaPasien').text("")
        $('#wrapperCatatan').html("");

        var body = document.body;
        body.style.paddingRight = '';
    });
</script>