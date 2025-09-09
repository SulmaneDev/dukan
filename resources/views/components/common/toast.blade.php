<div class="position-fixed bottom-0 end-0 top-0 p-3" style="z-index: 9999">
    <div id="toast"
        class="toast align-items-center border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true">
        <div class="toast-header">
            <strong id="toast-title" class="me-auto"></strong>
            <small id="toast-time" class="text-muted"></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="toast-body" class="toast-body">
        </div>
    </div>
</div>
@if(session('alert') || $errors->any())
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toastEl = document.getElementById('toast');
    const toastTitle = document.getElementById('toast-title');
    const toastBody = document.getElementById('toast-body');
    const toastTime = document.getElementById('toast-time');

    let alertData;

    @if(session('alert'))
        alertData = @json(session('alert'));
    @elseif($errors->any())
        alertData = {
            type: 'danger',
            title: 'Error',
            message: @json($errors->all())
        };
    @endif

    
    toastTitle.textContent = alertData.title || "Notification";
    toastBody.innerHTML = Array.isArray(alertData.message)
        ? alertData.message.join('<br>')
        : (alertData.message || "");
    toastTime.textContent = "Just now";

    
    const type = alertData.type || "primary";
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;

    
    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();
});
</script>
@endif
