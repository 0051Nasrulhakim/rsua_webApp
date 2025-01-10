<div class="section-catatan" id="section-catatan" hidden
    style=" padding-left: 2%;
            padding-right: 2%;
            padding-bottom: 2%;
            border-left: 1px solid;
            border-right: 1px solid;
            border-bottom: 1px solid;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            ">
    <div class="content" style="padding-top: 3%;">
        <div class="list-catatan" id="list-catatan">

        </div>
        <div class="form-floating" style="margin-top: 2%;">
            <textarea class="form-control" placeholder="Tambahkan Catatan" id="floatingTextarea2" style="height: 200px" name="catatan"></textarea>
            <label for="floatingTextarea2">Catatan Perawatan</label>
        </div>

        <div class="section-change-tombol" id="section-change-tombol">
            <div class="section-tombol" style="display: flex; width: 100%; text-align: center; justify-content: center;">

                <button
                    class="btn-custom"
                    onclick="batalkan()"
                    style="
                            margin-top: 3%;
                            padding: 2%;
                            border-radius: 2px;
                            border: none;
                            color: white;
                            background-color: #dd0000;
                            margin-right: 1%;"
                    type="button">Batalkan</button>

                <button
                    class="btn-custom"
                    style="
                            margin-top: 3%;
                            margin-left: 1%;
                            padding: 2%;
                            border-radius: 2px;
                            border: none;
                            color: white;
                            background-color: #008d57;"
                    type="submit">Simpan Catatan</button>
            </div>
        </div>

        <div class="tombol-2" id="tombol-2" style="display: flex; width: 100%; text-align: center; justify-content: center;" hidden>

            <button
                class="btn-custom"
                id="btn-update-catatan"
                style="
                        margin-top: 3%;
                        margin-left: 1%;
                        padding: 2%;
                        border-radius: 2px;
                        border: none;
                        color: white;
                        background-color:rgb(0, 66, 141);"
                type="button"
                onclick="updateCatatan(this)">Update Catatan</button>

        </div>
    </div>
</div>


<script>
    function batalkan() {
        document.getElementById('floatingTextarea2').value = '';
    }

    $('#insertCatatan').submit(function(e) {
        // var tes = $('#insertCatatan').serialize()
        // console.log(tes)
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('pasien/saveCatatan_perawatan') ?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                if (response.status_code === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                    });

                    setTimeout(function() {
                        $('#staticBackdrop').modal('hide');
                        const event = new CustomEvent("dataRefreshed");
                        window.dispatchEvent(event)
                    }, 1500);

                    $('#insertCatatan')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.'
                });
            }
        });
    })
</script>