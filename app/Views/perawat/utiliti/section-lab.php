<div class="section-lab" id="section-lab"
    style="padding-left: 2%; padding-right: 2%; padding-bottom: 2%; border-left: 1px solid; border-right: 1px solid; border-bottom: 1px solid; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);" hidden>
    <div class="content" style="padding-top: 3%;">
        <div class="content-lab" id="content-lab">

        </div>
    </div>
</div>

<script>
    function lab() {
        var no_rawat = document.getElementById("catatan_noRawat").value
        document.getElementById('search-bar').value = "";

        document.getElementById('section-lab').removeAttribute('hidden');
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true')
        document.getElementById('riwayat_obat').setAttribute('hidden', 'true')
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('radiologi').setAttribute('hidden', 'true');

        document.getElementById('btn-lab').classList.add('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');

        Swal.fire({
            title: 'Sedang Mengambil data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            customClass: {
                title: 'swal-title-small',
                content: 'swal-text-small'
            }
        });

        $.ajax({
            url: '<?= base_url('pasien/getLab') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                let rows = '';
                if (response.status_code == 200) {
                    // Mengakses hasil laboratorium yang dikelompokkan
                    let identitasLab = response.identitasLab;
                    let hasilLab = response.hasilLab;
                    // console.log(hasilLab)

                    rows += identitasLab.map(item => `
                        <div class="" style="width: 100%; border: 1px solid; text-align: center; background-color:rgb(243, 236, 198); padding: 0.5%;">
                            ${item.tgl_periksa + ' - ' + item.jam}
                        </div>

                        <div class="" style="width: 100%; border: 1px solid; text-align: center; margin-bottom: 1%;">
                            
                            <div class="keterangan" style="display: flex; font-size: 12pt; margin-top: 0.5%;">
                                <div class="judul-lab" style=" width: 20%;">
                                    Kode
                                </div>
                                <div style="width: 2%;">
                                    :
                                </div>
                                <div class="isi">
                                    ${item.kd_jenis_prw}
                                </div>
                            </div>
                            <div class="keterangan" style="display: flex; font-size: 12pt">
                                <div class="judul-lab" style="width: 20%;">
                                    Nama Pemeriksaan
                                </div>
                                <div style="width: 2%;">
                                    :
                                </div>
                                <div class="isi">
                                    ${item.nm_perawatan}
                                </div>
                            </div>
                            <div class="keterangan" style="display: flex; font-size: 12pt">
                                <div class="judul-lab" style="width: 20%;">
                                    Dokter PJ
                                </div>
                                <div style="width: 2%;">
                                    :
                                </div>
                                <div class="isi">
                                    ${item.nm_dokter_pj}
                                </div>
                            </div>
                            <div class="keterangan" style="display: flex; font-size: 12pt">
                                <div class="judul-lab" style="width: 20%;">
                                    Petugas
                                </div>
                                <div style="width: 2%;">
                                    :
                                </div>
                                <div class="isi">
                                    ${item.nama_petugas}
                                </div>
                            </div>

                            <table class="table table-striped table-bordered" style="width: 100%; margin-top: 2%;">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">No</th>
                                        <th style="width: 30%;">Detail Pemeriksaan</th>
                                        <th style="width: 20%;">Hasil</th>
                                        <th style="width: 20%;">Nilai Rujukan</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    ${Object.entries(hasilLab).map(([key, hasil], index) => {
                                        // Mengecek apakah item.kd_jenis_prw cocok dengan key
                                        if (item.kd_jenis_prw == key) {
                                            return hasil.map((itemHasil, indexHasil) => `
                                                <tr>
                                                    <td>${indexHasil + 1}</td>
                                                    <td>${itemHasil.pemeriksaan}</td>
                                                    <td>${itemHasil.nilai}</td>
                                                    <td>${itemHasil.nilai_rujukan}</td>
                                                </tr>
                                            `).join('');
                                        }
                                    }).join('') || '<tr><td colspan="3">Data tidak tersedia</td></tr>'}
                                </tbody>

                            </table>


                        </div>
                        
                    `).join('');
                } else {
                    rows += response.message;
                }
                $('#content-lab').html(rows);
            },
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil data!'
                });
            }
        });

    }
</script>