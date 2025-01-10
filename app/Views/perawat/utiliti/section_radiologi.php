<link rel="stylesheet" href="<?= base_url('public/assets/css/radiologi.css')?>">

<div class="section-riwayat-obat" id="radiologi"
    style="padding-left: 2%; padding-right: 2%; padding-bottom: 2%; border-left: 1px solid; border-right: 1px solid; border-bottom: 1px solid; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);" hidden>
    <div class="content" style="padding-top: 3%;">
        <div class="gambar-radiologi-custom" id="gambar-radiologi-zoom-1" style="width: 100%;">
            
        </div>
    </div>
</div>

<!-- Modal for zoomed image 1 -->
<div id="modal-zoom-radiologi-custom-1" class="modal-custom">
    <span class="close-custom" onclick="closeModal('modal-zoom-radiologi-custom-1')">&times;</span>
    <img class="modal-content-custom" id="modalImg-custom-1">
</div>


<script>
    function zoomImage(img, modalId) {
        console.log(img);
        var modal = document.getElementById(modalId);
        var modalImg = document.getElementById("modalImg-custom-" + modalId.split("-").pop());
        modal.style.display = "block";
        modalImg.src = img;
    }

    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = "none";
    }
</script>