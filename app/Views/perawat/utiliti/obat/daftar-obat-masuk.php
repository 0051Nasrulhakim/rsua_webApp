<div class="daftar-obat-masuk" id="daftar-obat-masuk">
    <div class="search" style="width: 100%; margin-bottom: 2%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Nama Obat
        </div>
        <div class="input" style="width: 35%; margin-right: 1%;">
            <input class="form-control small-input" type="text" id="search-bar" placeholder="Cari Obat..." onkeyup="filterObat()">
        </div>
        <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearch()">
            X
        </div>
    </div>
    <table class="table table-striped sro-tabel">
        <thead>
            <tr>
                <td class="tanggal">tanggal</td>
                <td class="jam">Jam</td>
                <td class="nama_brng">N. Obat / Alkes</td>
                <td class="jml text-center">Jumlah</td>
            </tr>
        </thead>
        <tbody id="table-obat">

        </tbody>
    </table>
</div>