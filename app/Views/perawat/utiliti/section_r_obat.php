<style>
    .small-input {
        font-size: 9px;
        padding: 3px;
    }

    .stock-out {
        background-color: black !important;
        color: white !important;
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
        <div class="submenu" style="display: flex; margin-bottom: 2%;">
            <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 1%;"
                type="button" onclick="tampilstok()">
                Stok Obat Pasien
            </button>
            <button
                class="btn-custom-edit"
                style="padding: 1%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 3%;"
                type="button" onclick="tampilRiwayat()">
                Daftar Obat Masuk
            </button>
        </div>

        <div class="table">
            <?= $this->include('perawat/utiliti/obat/daftar-obat-masuk') ?>
            <?= $this->include('perawat/utiliti/obat/stok-obat-pasien') ?>

        </div>
    </div>
</div>

<script>
    function tampilstok() {
        document.getElementById('daftar-obat-masuk').setAttribute('hidden', 'true');
        document.getElementById('stok-obat-pasien').removeAttribute('hidden');
        stokObat();

    }

    function tampilRiwayat() {
        document.getElementById('stok-obat-pasien').setAttribute('hidden', 'true');
        document.getElementById('daftar-obat-masuk').removeAttribute('hidden');
        riwayatObat();
    }

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

    let responseData = []

    function stokObat() {
        var no_rawat = document.getElementById("catatan_noRawat").value
        $.ajax({
            url: '<?= base_url('obat/getStokObatPasien') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response)
                Swal.close();
                if (response.length > 0) {
                    let rows = '';
                    responseData = response;
                    response.forEach(function(item, index) {
                        let kodeBrngNoRawatId = (item.kode_brng + '-' + item.no_rawat).replace(/\//g, '_').replace(/%/g, '_');
                        let rowStyle = '';
                        let isHidden = ''
                        if (item.sisa_stok == 0) {
                            rowStyle = 'class="stock-out"';
                            isHidden = 'hidden'
                        }
                        rows += `
                                <tr id="row-${kodeBrngNoRawatId}">
                                    <td ${rowStyle}>${item.nama_brng}</td>
                                    <td ${rowStyle}>${item.jumlah}</td>
                                    <td ${rowStyle}>${item.sisa_stok}</td>
                                    <td ${rowStyle}>${item.aturan_pakai}</td>
                                    <td ${rowStyle}>
                                        <button 
                                            ${isHidden}
                                            type="button"
                                            class="btn-custom-edit"
                                            style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(2, 61, 0); margin-right: 3%;"
                                            onclick="showJam('${item.kode_brng}', '${item.no_rawat}', '${kodeBrngNoRawatId}', this)"
                                        >
                                            Masukkan obat
                                        </button>
                                    </td>   
                                </tr>
                                <tr id="jam-row-${kodeBrngNoRawatId}" style="display:none">
                                    <td colspan="5">
                                        <div id="jam-display-${kodeBrngNoRawatId}"></div>
                                    </td>
                                </tr>
                            `;
                    });
                    $('#table-stok-obat').html(rows);
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

    function showJam(kodeBrng, noRawat, kodeBrngNoRawatId) {
        $('#table-stok-obat tr[id^="jam-row-"]').hide();
        $('#table-stok-obat div[id^="jam-display-"]').empty();

        let jamRow = $('#jam-row-' + kodeBrngNoRawatId);
        let jamDisplay = $('#jam-display-' + kodeBrngNoRawatId);

        if (jamRow.is(':hidden')) {
            let item = responseData.find(function(item) {
                return item.kode_brng === kodeBrng && item.no_rawat === noRawat;
            });

            let jamContent = '<table class="table">';
            for (let i = 0; i < 24; i++) {
                let jamKey = 'jam' + String(i).padStart(2, '0');
                if (item[jamKey] === "true") {
                    let timePeriod = getTimePeriod(i);
                    jamContent += `
                <tr>
                    <td>Jam ${i}:00</td>
                    <td>
                        <input type="number" min="0" class="form-control" id="input-${jamKey}-${kodeBrngNoRawatId}" placeholder="Jumlah">
                    </td>
                    <td>
                        <select class="form-control" id="select-${jamKey}-${kodeBrngNoRawatId}">
                            <option value="Pagi" ${timePeriod === 'Pagi' ? 'selected' : ''}>Pagi</option>
                            <option value="Siang" ${timePeriod === 'Siang' ? 'selected' : ''}>Siang</option>
                            <option value="Sore" ${timePeriod === 'Sore' ? 'selected' : ''}>Sore</option>
                            <option value="Malam" ${timePeriod === 'Malam' ? 'selected' : ''}>Malam</option>
                        </select>
                    </td>
                </tr>
                `;
                }
            }
            jamContent += `
        </table>
        <button class="btn btn-success" type="button" onclick="saveJam('${kodeBrng}', '${noRawat}', '${kodeBrngNoRawatId}')">Simpan</button>
        `;

            jamDisplay.html(jamContent);
            jamRow.show();
        } else {
            jamRow.hide();
        }
    }

    function getTimePeriod(hour) {
        if (hour >= 0 && hour <= 11) {
            return 'Pagi';
        } else if (hour >= 12 && hour <= 15) {
            return 'Siang';
        } else if (hour >= 16 && hour <= 19) {
            return 'Sore';
        } else {
            return 'Malam';
        }
    }


    function saveJam(kodeBrng, noRawat, kodeBrngNoRawatId) {
        let dataToSave = [];

        for (let i = 0; i < 24; i++) {
            let jamKey = 'jam' + String(i).padStart(2, '0');
            let jumlahInput = $(`#input-${jamKey}-${kodeBrngNoRawatId}`).val();
            let periodeSelect = $(`#select-${jamKey}-${kodeBrngNoRawatId}`).val();

            if (jumlahInput > 0) {
                let jamValue = '';
                switch (periodeSelect) {
                    case 'Pagi':
                        jamValue = '07:00:00';
                        break;
                    case 'Siang':
                        jamValue = '12:00:00';
                        break;
                    case 'Sore':
                        jamValue = '16:00:00';
                        break;
                    case 'Malam':
                        jamValue = '20:00:00';
                        break;
                }

                dataToSave.push({
                    kode_brng: kodeBrng,
                    no_rawat: noRawat,
                    jam: jamValue,
                    jumlah: jumlahInput,
                    periode: periodeSelect
                });
            }
        }

        if (dataToSave.length > 0) {
            // console.log(dataToSave)
            $.ajax({
                url: '<?= base_url('obat/simpanPemberianObat') ?>',
                type: 'post',
                data: JSON.stringify(dataToSave),
                contentType: 'application/json',
                success: function(response) {
                    stokObat();
                    alert('Data berhasil disimpan!');
                },
                error: function(error) {
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            });
        } else {
            alert('Tidak ada data yang disimpan karena semua nilai adalah 0.');
        }
    }


    function riwayatObat() {
        document.getElementById('section-modal-riwayat').setAttribute('hidden', 'true');
        document.getElementById('section-catatan').setAttribute('hidden', 'true');
        document.getElementById('riwayat_obat').removeAttribute('hidden');
        document.getElementById('radiologi').setAttribute('hidden', 'true');
        document.getElementById('section-lab').setAttribute('hidden', 'true');

        document.getElementById('btn-obat').classList.add('active');
        document.getElementById('btn-catatan').classList.remove('active');
        document.getElementById('btn-riwayat').classList.remove('active');
        document.getElementById('btn-radiologi').classList.remove('active');
        document.getElementById('btn-lab').classList.remove('active');

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
            url: '<?= base_url('obat/getRiwayatObat') ?>',
            method: 'GET',
            data: {
                norawat: no_rawat
            },
            dataType: 'json',
            success: function(response) {
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
                // if (response.length > 0) {
                //     let rows = '';
                //     let previousDate = ''; 

                //     response.forEach(function(item, index) {
                //         let currentDate = item.tgl_perawatan;

                //         if (currentDate !== previousDate) {
                //             rows += `
                //                 <tr>
                //                     <td>${currentDate}</td>
                //                     <td>${item.jam}</td>
                //                     <td>${item.nama_brng}</td>
                //                     <td style="text-align: center;">${item.jml}</td>
                //                 </tr>
                //             `;
                //             previousDate = currentDate;
                //         } else {
                //             rows += `
                //                 <tr>
                //                     <td></td>
                //                     <td>${item.jam}</td>
                //                     <td>${item.nama_brng}</td>
                //                     <td style="text-align: center;">${item.jml}</td>
                //                 </tr>
                //             `;
                //         }
                //     });

                //     $('#table-obat').html(rows);
                // }
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