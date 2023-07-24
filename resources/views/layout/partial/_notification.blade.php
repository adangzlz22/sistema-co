{{-- toast --}}
<div id="toast" class="toast"  data-bs-autohide="true" style="position: absolute; top: 10px; right: 10px;z-index:2000;">
    <div class="toast-header">
        <strong class="me-auto"><i class="bi-gift-fill"></i><label id="toast-name-notification"></label></strong>
        <small><label id="toast-time"></label></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
        <label id="toast-message"></label> <a href="#" id="link-more-info"></a>
    </div>
</div>
<div id="minimal-toast" class="toast align-items-center position-fixed border-0 end-0 me-2 bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000" data-bs-autohide="true" style="top: 30px;z-index: 2011">
    <div class="d-flex">
        <div class="toast-body" id="tm-body">
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
