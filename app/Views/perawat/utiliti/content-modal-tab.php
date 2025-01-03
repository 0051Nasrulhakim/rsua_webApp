<div class="type">
    <div class="section-menu">

        <div class="menu-1" onclick="tambahCatatan()" id="btn-catatan">
            Tambahkan Catatan
        </div>
    </div>
</div>
<div class="section" id="section-riwayat">
    <div class="section-nama" style="display: flex; padding-top: 3%; justify-content: center; font-weight: 700;">
        <div class="norm" id="contentNorm" style="width: 10%;"></div>
        <div class="" style="width: 2%; text-align: center;">-</div>
        <div class="nama" id="contentNamaPasien"></div>
    </div>
    <div class="wrapper-modal" style="display: flex; padding-top: 2%;">
        <div class="kaategori" style="width: 7%; text-align: center; ">
            TTV
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentTtv" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            S
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentS" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            O
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentO" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            A
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentA" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            P
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentP" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            I
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentI" style="width: 80%; margin-left: 1%;">

        </div>
    </div>
    <div class="wrapper-modal" style="display: flex;">
        <div class="kaategori" style="width: 7%; text-align: center;">
            E
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentE" style="width: 80%; margin-left: 1%;">

        </div>
    </div>

    <div class="wrapper-modal mt-2" style="display: flex;">
        <div class="kaategori" style="width: 14%; text-align: left;">
            Radiologi
        </div>
        <div class="semicolon">
            :
        </div>
        <div class="content" id="contentRad" style="width: 83%; margin-left: 1%;">

        </div>
    </div>
</div>

<div class="section-catatan" id="section-catatan" hidden
    style=" padding-left: 2%;
            padding-right: 2%;
            padding-bottom: 2%;
            border-left: 1px solid;
            border-right: 1px solid;
            border-bottom: 1px solid;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            ">
    <div class="content" style="padding-top: 3%;">
        <div class="section-nama" style="display: flex; padding-top: %; margin-top: 1%; justify-content: center; font-weight: 700;">
            <div class="norm" id="contentNormCatatan" style="width: 10%;"></div>
            <div class="" style="width: 2%; text-align: center;">-</div>
            <div class="nama" id="contentNamaPasienCatatan"></div>
        </div>
        <div class="form-floating" style="margin-top: 2%;">
            <textarea class="form-control" placeholder="Tambahkan Catatan" id="floatingTextarea2" style="height: 200px" name="catatan"></textarea>
            <label for="floatingTextarea2">Catatan Perawatan</label>
        </div>

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
                        margin-right: 1%;
                ">Batalkan</button>

            <button
                class="btn-custom"
                style="
                        margin-top: 3%;
                        margin-left: 1%;
                        padding: 2%;
                        border-radius: 2px;
                        border: none;
                        color: white;
                        background-color: #008d57;     
                " type="submit">Simpan Catatan</button>
        </div>
    </div>
</div>

<script>
    function tambahCatatan() {
        document.getElementById('section-riwayat').setAttribute('hidden', 'true');
        document.getElementById('btn-catatan').setAttribute('hidden', 'true');

        document.getElementById('section-catatan').removeAttribute('hidden');
    }

    function batalkan() {
        document.getElementById('floatingTextarea2').value = '';
        document.getElementById('section-riwayat').removeAttribute('hidden');
        document.getElementById('btn-catatan').removeAttribute('hidden');

        document.getElementById('section-catatan').setAttribute('hidden', 'true');
    }
</script>