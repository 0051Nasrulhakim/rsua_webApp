<div class="section-catatan" id="section-catatan" hidden
    style=" padding-left: 2%;
            padding-right: 2%;
            padding-bottom: 2%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            ">
    <div class="content" style="padding-top: 3%;">
        <div class="row g-3 align-items-center" style="margin-bottom: 2%; width: 50%;">
            <div class="col-auto">
                <label for="shift" class="col-form-label">Filter</label>
            </div>
            <div class="col-3" style="width: 70%;">
                <select id="filter" class="form-control form-select form-select-sm" onchange="filterChange()">
                    <option value="1" selected>Catatan Terakhir</option>
                    <option value="">Semua</option>
                </select>
            </div>
        </div>


        <div class="list-catatan" id="list-catatan-terakhir">

        </div>

        <div class="list-catatan" id="list-catatan">

        </div>

        <div class="section-waktu" style="display: flex; width: 100%;">

            <div class="row g-3 align-items-center" style="margin-top: 2%; width: 50%;">
                <div class="col-auto">
                    <label for="shift" class="col-form-label">Pilih Shift</label>
                </div>
                <div class="col-3" style="width: 70%;">
                    <select name="shift" id="shift_select" class="form-control form-select form-select-sm">
                        <option value="pagi">Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 align-items-center" style="margin-top: 2%; width: 50%;" id="input-tanggal">
                <div class="col-auto">
                    <label for="shift" class="col-form-label">Tanggal</label>
                </div>
                <div class="col-3" style="width: 70%;">
                    <input type="date" class="form-control form-control-sm" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" readonly>
                </div>
            </div>

        </div>

        <div class="form-floating" style="margin-top: 1%;">
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
                    type="button">Clear</button>

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
    function GantiCatatan(button) {
        const noRawat = button.getAttribute('data-no-rawat');
        const tanggal = button.getAttribute('data-tanggal');
        const jam = button.getAttribute('data-jam');
        const catatan = button.getAttribute('data-catatan');
        const shift = button.getAttribute('data-shift');

        document.getElementById('floatingTextarea2').value = catatan;
        document.getElementById('catatan_tanggal').value = tanggal;
        document.getElementById('tanggal').value = tanggal;
        document.getElementById('catatan_jam').value = jam;
        document.getElementById('shift_select').value = shift;

        const allButtons = document.querySelectorAll('.btn-custom-edit');
        allButtons.forEach(function(btn) {
            btn.style.display = 'none';
        });

        button.style.display = 'block';
        button.innerText = 'Batalkan';
        button.style.backgroundColor = 'rgb(255, 0, 0)';

        button.onclick = function() {
            Batalkan(button, noRawat, tanggal, jam, catatan, shift, true);
            document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
        };

        document.getElementById('section-change-tombol').setAttribute('hidden', 'true');
        document.getElementById('tombol-2').removeAttribute('hidden');
    }

    function updateCatatan(button) {
        var filterValue = document.getElementById('filter').value;
        var no_rawat = document.getElementById("catatan_noRawat").value

        $.ajax({
            url: '<?= base_url('pasien/updateCatatan') ?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                if (response.status_code == 200) {

                    const event = new CustomEvent("dataRefreshed");
                    document.getElementById('tombol-2').setAttribute('hidden', 'true');
                    document.getElementById('section-change-tombol').removeAttribute('hidden');
                    document.getElementById('floatingTextarea2').value = '';
                    document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
                    window.dispatchEvent(event);

                    if (filterValue == 1) {
                        lastCatatan();
                    } else {
                        allCatatan();
                    }

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

    }

    function hapus(button) {
        var filterValue = document.getElementById('filter').value;
        // console.log(filterValue)
        var noRawat = button.getAttribute('data-no-rawat');
        var tanggal = button.getAttribute('data-tanggal');
        var jam = button.getAttribute('data-jam');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dipulihkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pasien/deleteCatatan') ?>',
                    type: 'POST',
                    data: {
                        noRawat: noRawat,
                        tanggal: tanggal,
                        jam: jam
                    },
                    success: function(response) {
                        if (response.status_code === 200) {
                            const event = new CustomEvent("dataRefreshed");
                            document.getElementById('tombol-2').setAttribute('hidden', 'true');
                            document.getElementById('section-change-tombol').removeAttribute('hidden');
                            document.getElementById('floatingTextarea2').value = '';
                            document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
                            window.dispatchEvent(event);
                            // lastCatatan();
                            if (filterValue == 1) {
                                lastCatatan();
                            } else {
                                allCatatan();
                            }

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
            }
        });
    }

    function batalkan() {
        document.getElementById('floatingTextarea2').value = '';
        document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
    }

    function TambahCatatan(button) {
        const tanggal = button.getAttribute('data-tanggal');
        const shift = button.getAttribute('data-shift');
        // alert(tanggal)

        document.getElementById('shift_select').value = shift;
        document.getElementById('catatan_tanggal').value = tanggal;
        document.getElementById('tanggal').value = tanggal;
    }

    $('#insertCatatan').submit(function(e) {
        // var tes = $('#insertCatatan').serialize()
        // console.log(tes)
        var filterValue = document.getElementById('filter').value;
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('pasien/saveCatatan_perawatan') ?>',
            type: 'POST',
            data: $('#insertCatatan').serialize(),
            success: function(response) {
                if (response.status_code === 200) {

                    const event = new CustomEvent("dataRefreshed");
                    document.getElementById('tombol-2').setAttribute('hidden', 'true');
                    document.getElementById('section-change-tombol').removeAttribute('hidden');
                    document.getElementById('floatingTextarea2').value = '';
                    document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
                    window.dispatchEvent(event);
                    if (filterValue == 1) {
                        lastCatatan();
                    } else {
                        allCatatan();
                    }
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

    function copyCatatan(button) {
        const noRawat = button.getAttribute('data-no-rawat');
        const tanggal = button.getAttribute('data-tanggal');
        const shift = button.getAttribute('data-shift');

        $.ajax({
            url: '<?= base_url('pasien/findCatatan') ?>',
            type: 'POST',
            data: {
                no_rawat: noRawat,
                tgl: tanggal
            },
            success: function(response) {
                // console.log(response)
                if (response.status_code === 200) {
                    document.getElementById('shift_select').value = shift;
                    document.getElementById('floatingTextarea2').value = response.data.uraian;
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
    }

    function allCatatan() {
        var no_rawat = document.getElementById("catatan_noRawat").value
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
            url: '<?= base_url('pasien/getCatatan') ?>',
            method: 'GET',
            data: {
                noRawat: no_rawat
            },
            dataType: 'json',
            success: function(data) {
                Swal.close();
                let rows = '';
                let shifts = ['pagi', 'siang', 'malam'];

                if (Object.keys(data.data).length > 0) {
                    for (let tanggal in data.data) {
                        let items = data.data[tanggal];
                        let shiftData = {};

                        // Mapping data ke shiftData
                        items.forEach(function(item) {
                            shiftData[item.shift] = {
                                catatan: item.catatan,
                                jam: item.jam
                            };
                        });

                        rows += `
                            <div class="content">
                                <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                    ${tanggal}
                                </div>
                                <div class="isi" style="display: flex; width: 100%;" id="isCatatan">
                        `;

                        shifts.forEach(function(shift) {
                            let dataShift = shiftData[shift] || {
                                catatan: '',
                                jam: ''
                            };
                            let catatan = dataShift.catatan || 'tidak ada catatan';
                            let jam = dataShift.jam || '';

                            rows += `
                                    <div class="${shift}" style="width: 34%; border: 1px solid; display: flex; flex-direction: column; height: auto; min-height: 130px;">
                                        <div style="width: 100%; padding: 1%; border-bottom: 1px solid; text-align: center; background-color: rgb(194, 194, 194); font-weight: 700;">
                                            ${shift.toUpperCase()}
                                        </div>

                                        <div style="width: 100%; padding: 2%; flex-grow: 1; overflow-wrap: break-word;">
                                            ${catatan}
                                        </div>

                                        <div style="width: 100%; display: flex; justify-content: center; margin-top: 3%; margin-bottom: 2%;">
                                            ${
                                                catatan == "tidak ada catatan" ?
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(0, 70, 128); margin-right: 5%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="TambahCatatan(this)">
                                                    Tambahkan
                                                </button>
                                                <button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(0, 70, 128);"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="copyCatatan(this)">
                                                    Copy Sebelumnya
                                                </button>
                                                ` :
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(119, 128, 0); margin-right: 5%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="GantiCatatan(this)">
                                                    Edit
                                                </button>`
                                            }
                                            ${
                                                catatan == "tidak ada catatan" ? "" : 
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 3%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="hapus(this)">
                                                    Hapus
                                                </button>`
                                            }
                                        </div>
                                    </div>

                                `;
                        });

                        rows += `
                                </div>
                            </div>
                        `;
                    }
                } else {
                    rows += `
                        <div class="content">
                            <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                BELUM ADA CATATAN PERAWATAN
                            </div>
                        </div>
                    `;
                }


                $('#list-catatan').html(rows);
            },

            error: function() {
                // Hapus swal loading
                Swal.close();

                // Tampilkan swal error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil data!'
                });
            }
        });
    }

    function lastCatatan() {
        // 
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
        $.ajax({
            url: '<?= base_url('pasien/getCatatan') ?>',
            method: 'GET',
            data: {
                noRawat: no_rawat,
                filter: "1"
            },
            dataType: 'json',
            success: function(data) {
                Swal.close();
                let rows = '';
                let shifts = ['pagi', 'siang', 'malam'];

                if (Object.keys(data.data).length > 0) {
                    for (let tanggal in data.data) {
                        let items = data.data[tanggal];
                        let shiftData = {};

                        // Mapping data ke shiftData
                        items.forEach(function(item) {
                            shiftData[item.shift] = {
                                catatan: item.catatan,
                                jam: item.jam
                            };
                        });

                        rows += `
                            <div class="content">
                                <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                    ${tanggal}
                                </div>
                                <div class="isi" style="display: flex; width: 100%;" id="isCatatan">
                        `;

                        shifts.forEach(function(shift) {
                            let dataShift = shiftData[shift] || {
                                catatan: '',
                                jam: ''
                            };
                            let catatan = dataShift.catatan || 'tidak ada catatan';
                            let jam = dataShift.jam || '';

                            rows += `
                                    <div class="${shift}" style="width: 34%; border: 1px solid; display: flex; flex-direction: column; height: auto; min-height: 130px;">
                                        <div style="width: 100%; padding: 1%; border-bottom: 1px solid; text-align: center; background-color: rgb(194, 194, 194); font-weight: 700;">
                                            ${shift.toUpperCase()}
                                        </div>

                                        <div style="width: 100%; padding: 2%; flex-grow: 1; overflow-wrap: break-word;">
                                            ${catatan}
                                        </div>

                                        <div style="width: 100%; display: flex; justify-content: center; margin-top: 3%; margin-bottom: 2%;">
                                            ${
                                                catatan == "tidak ada catatan" ?
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(0, 70, 128); margin-right: 5%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="TambahCatatan(this)">
                                                    Tambahkan
                                                </button>
                                                <button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(0, 70, 128);"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="copyCatatan(this)">
                                                    Copy Sebelumnya
                                                </button>
                                                ` :
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(119, 128, 0); margin-right: 5%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="GantiCatatan(this)">
                                                    Edit
                                                </button>`
                                            }
                                            ${
                                                catatan == "tidak ada catatan" ? "" : 
                                                `<button 
                                                    class="btn-custom-edit"
                                                    style="padding: 3%; border-radius: 2px; border: none; color: white; background-color: rgb(184, 9, 9); margin-right: 3%;"
                                                    type="button"
                                                    data-no-rawat="${no_rawat}"
                                                    data-tanggal="${tanggal}"
                                                    data-catatan="${catatan}"
                                                    data-jam="${jam}"
                                                    data-shift="${shift}"
                                                    onclick="hapus(this)">
                                                    Hapus
                                                </button>`
                                            }
                                        </div>
                                    </div>

                                `;
                        });

                        rows += `
                                </div>
                            </div>
                        `;
                    }
                } else {
                    rows += `
                        <div class="content">
                            <div class="header" style="text-align: center; border: 1px solid; background-color: #fff7e0;">
                                BELUM ADA CATATAN PERAWATAN
                            </div>
                        </div>
                    `;
                }


                $('#list-catatan').html(rows);
            },

            error: function() {
                // Hapus swal loading
                Swal.close();

                // Tampilkan swal error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengambil data!'
                });
            }
        });
    }

    function filterChange() {
        var filterValue = document.getElementById('filter').value;

        if (filterValue == "1") {
            lastCatatan();
        } else {
            allCatatan();
        }
    }
</script>