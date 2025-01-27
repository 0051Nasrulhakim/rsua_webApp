
<link rel="stylesheet" href="<?= base_url()?>public/assets/css/handover.css">
<div class="cpo-modal" id="cpo">
     <div class="filter-riwayat" style="display: flex; align-items: center;">
        <div class="text" style="width: 12%;">
            Cari Nama Obat
        </div>
        <div class="input" style="width: 30%; display: flex;">
            <input class="form-control" type="text" id="searchObatInput" placeholder="Cari Nama Obat" onkeyup="filterData()">
            <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearchRiwayat()">
                X
            </div>
        </div>
    </div>

    <div class="wrapscroll" style="overflow-x: auto; ">
        <div class="headList" style="display: flex; padding: 0;">
            <div class="namaobat" style="text-align: center; width: 320px !important; padding: 0; white-space: nowrap; flex-shrink: 0; border-bottom: 1px solid; border-left: 1px solid;border-top: 1px solid; display: flex; align-items: center; justify-content: center;">
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
    function clearSearchRiwayat() {
        document.getElementById('searchObatInput').value = '';
        cpo()
    }
</script>