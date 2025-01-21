<?= $this->extend('perawat/index_perawat') ?>
<?= $this->section('content') ?>
<?= $this->include('perawat/utiliti/modal_lihat_riwayat') ?>
<?= $this->include('perawat/utiliti/modal_lihat_catatan') ?>
<?= $this->include('perawat/utiliti/modal_obat') ?>

<div class="wrapper">

    <div class="judul">
        Hand Over Ranap
    </div>

    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%; display: flex; width: 100%;">
        <div class="text-tanggal" style="display: flex; align-items: center; width: 9%;">
            <label for="filter-doctor">Tanggal</label>
        </div>
        <div class="input">
            <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
    <div class="filter-section-pasien-ranap" style="margin-bottom: 2%; margin-top: 2%;">
        <label for="filter-doctor">Filter Dokter </label>
        <select id="filter-doctor">
            <option value="">Semua Dokter</option>
        </select>
    </div>

    <div class="sectionhandOver" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="headHandover" style="display: flex;">
            <div class="kelas" style="width: 6%; text-align: center; border: 1px solid;">Kelas</div>
            <div class="nama" style="width: 18%; text-align: center; border: 1px solid;">Nama</div>
            <div class="kelas" style="width: 20%; text-align: center; border: 1px solid;">DPJP</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid;">PAGI</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid;">SIANG</div>
            <div class="kelas" style="width: auto; min-width: 130px; text-align: center; border: 1px solid;">Malam</div>
        </div>
        <div class="bodyHandover" style="display: flex;">
            <div class="kelas" style="width: 6%; text-align: center; border: 1px solid;">2.B.1</div>
            <div class="nama" style="width: 18%; text-align: center; border: 1px solid;">KASMUDI, TN</div>
            <div class="kelas" style="width: 20%; text-align: center; border: 1px solid;">dr. Aryo Budiyogo, Sp.OT</div>
            <div class="kelas" style="width: 130px; text-align: center; border: 1px solid;">
                <div class="text-catatan">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, repellendus. Excepturi aut, hic atque fugit </div>
                <div class="keterangan">
                    <div class="insertby">
                        Tes user insert
                    </div>
                    <div class="lastUpdate">
                        <?= date('Y-m-d h:i:s')?>
                    </div>
                </div>
            </div>
            <div class="kelas" style="width: 130px; text-align: center; border: 1px solid;">SIANG</div>
            <div class="kelas" style="width: 130px; text-align: center; border: 1px solid;">Malam</div>
        </div>

    </div>

</div>

<?= $this->endSection(); ?>