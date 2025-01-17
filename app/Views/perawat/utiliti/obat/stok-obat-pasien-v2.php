<div class="stok-obat-pasien-v2" id="stok-obat-pasien-v2" hidden>
    <div class="tanggalSection" style="width: 100%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Tanggal
        </div>
        <div class="text">
            <input type="date" class="form-control" id="tanggal-filter-new" name="tanggal-filter-new" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="sectionBtn">
            <div class="tombol" style="text-align: center; border: 1px solid; border-radius: 4px; background-color:rgb(55, 52, 235); padding: 7px; color: white;" onclick="unitTesting()">
                Tampilkan
            </div>
        </div>
    </div>
    <!-- <div class="search" style="width: 100%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Nama Stok Obat
        </div>
        <div class="input" style="width: 35%; margin-right: 1%;">
            <input class="form-control small-input" type="text" id="search-bar-stok-obat-pasien" placeholder="Cari Obat..." onkeyup="filterStok()">
        </div>
        <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearchStok()">
            X
        </div>
    </div> -->
    <!-- <div class="wrap-list-obat" style="border: 1px solid;"> -->
    

        <div class="head-list-stok-obat" id="head-list-stok-obat" style="display: flex; width: 100%; ">
            <div class="namaBarang" style="width: 25%;">Nama Barang</div>
            <div class="jml" style="width: 5%; text-align: center;">Jml</div>
            <div class="sisa" style="width: 5%; text-align: center;">Sisa</div>
            <div class="aturanPakai" style="width: 20%; text-align: center;">Pemberian Teakhir</div>
            <div class="aturanPakai" style="width: 50%; text-align: center;">Masukan Obat</div>
        </div>
        <div class="list-stok-obat" id="list-stok-obat">
            
        </div>

        <div class="title-head-injeksi" style="width: 100%; text-align: center;  margin-top:3%; margin-bottom: 1%; font-size: 12pt; font-weight: 600;">
            Stok Obat Injeksi
        </div>

        <div class="head-list-stok-obat-injeksi" id="head-list-stok-obat-injeksi" style="display: flex; width: 100%;">
            <div class="namaBarang" style="width: 25%;">Nama Barang</div>
            <div class="jml" style="width: 5%; text-align: center;">Jml</div>
            <div class="sisa" style="width: 5%; text-align: center;">Sisa</div>
            <div class="aturanPakai" style="width: 20%; text-align: center;">Pemberian Teakhir</div>
            <div class="aturanPakai" style="width: 50%; text-align: center;">Masukan Obat</div>
        </div>
        <div class="list-stok-obat-injeksi" id="list-stok-obat-injeksi">
            
        </div>
    <!-- <div class="sectionBtn" style="width: 50%; text-align:center; margin-left: auto; margin-right: auto; margin-top: 2%;" id="tombol-tombol-simpan" hidden>
        <div class="tombol" style="text-align: center; border: 1px solid; border-radius: 4px; background-color:rgb(2, 95, 33); padding: 7px; color: white;" onclick="savePemberianObat()">
            Simpan
        </div>
    </div> -->


</div>