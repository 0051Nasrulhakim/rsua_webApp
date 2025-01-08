<style>
    .small-input {
        font-size: 9px;
        padding: 3px;
    }
</style>
<div class="section-riwayat-obat" id="riwayat_obat"
    style=" padding-left: 2%;
            padding-right: 2%;
            padding-bottom: 2%;
            border-left: 1px solid;
            border-right: 1px solid;
            border-bottom: 1px solid;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            " hidden>
    <div class="content" style="padding-top: 3%;">
        <div class="table">
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
                        <td class="jml">Jumlah</td>
                    </tr>
                </thead>
                <tbody id="table-obat">

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function clearSearch() {
        document.getElementById('search-bar').value = "";

        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }

    }
    document.getElementById('staticBackdrop').addEventListener('hidden.bs.modal', function() {
        document.getElementById('search-bar').value = "";

        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            rows[i].style.display = "";
        }
        let clear = '';

        $('#table-obat').html(clear);

    });

    function riwayatObat() {
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').removeAttribute('hidden');
        document.getElementById('radiologi').setAttribute('hidden', 'true');

        document.getElementById('btn-obat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-riwayat').classList.remove('active');
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

        var no_rawat = document.getElementById("catatan_noRawat").value
        // alert(noRawat)
        $.ajax({
            url: '<?= base_url('pasien/getRiwayatObat') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                // your code here
                Swal.close();
                if (response.length > 0) {
                    let rows = '';
                    response.forEach(function(item, index) {
                        rows += `
                            <tr>
                                <td>${item.tgl_perawatan}</td>
                                <td>${item.jam}</td>
                                <td>${item.nama_brng}</td>
                                <td style="text-align: center;">${item.jml}</td>
                            </tr>
                        `;
                    });
                    $('#table-obat').html(rows);
                }
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

    function filterObat() {
        var input = document.getElementById("search-bar").value.toLowerCase();
        var table = document.getElementById("table-obat");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var td = rows[i].getElementsByTagName("td")[2]; // Kolom nama_brng
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(input) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>