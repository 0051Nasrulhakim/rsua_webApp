<div class="daftar-stok-obat-pasien" id="stok-obat-pasien" hidden>
    <div class="tanggalSection" style="width: 100%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Tanggal
        </div>
        <div class="text">
            <input type="date" class="form-control" id="tanggal-filter" name="tanggal-filter" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="sectionBtn">
            <div class="tombol" style="text-align: center; border: 1px solid; border-radius: 4px; background-color:rgb(55, 52, 235); padding: 7px; color: white;" onclick="tampilstok()">
                Tampilkan
            </div>
        </div>
    </div>
    <div class="search" style="width: 100%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Nama Stok Obat
        </div>
        <div class="input" style="width: 35%; margin-right: 1%;">
            <input class="form-control small-input" type="text" id="search-bar-stok-obat-pasien" placeholder="Cari Obat..." onkeyup="filterStok()">
        </div>
        <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearchStok()">
            X
        </div>
    </div>

    <table class="table table-striped sro-tabel">
        <thead>
            <tr>
                <td>Nama Barang</td>
                <td>Jml</td>
                <td>Sisa</td>
                <td>Aturan Pakai</td>

            </tr>
        </thead>
        <tbody id="table-stok-obat">

        </tbody>
    </table>
    <button id="save-button" class="btn btn-success" style="display:none;" onclick="saveAllJam()">Simpan</button>
</div>