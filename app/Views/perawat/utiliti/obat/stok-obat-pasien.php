<div class="daftar-stok-obat-pasien" id="stok-obat-pasien" hidden>
    <div class="search" style="width: 100%; margin-bottom: 2%; display:flex; align-items:center">
        <div class="sro-nama-obat">
            Nama Stok Obat
        </div>
        <div class="input" style="width: 35%; margin-right: 1%;">
            <input class="form-control small-input" type="text" id="search-bar-stok-obat-pasien" placeholder="Cari Obat..." onkeyup="alert('filter stok obat pasien')">
        </div>
        <div class="tombol" style="text-align: center; width: 30px;border: 1px solid; border-radius: 4px; background-color: #eb4034; padding: 7px; color: white;" onclick="clearSearch()">
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
</div>