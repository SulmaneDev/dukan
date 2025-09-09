<div class="modal fade" id="delete-modal" tabindex="-1" aria-modal="true" role="dialog">
    <form id="delete-form" method="POST" action="{{ url()->current() }}/delete">
        @csrf
        @method('DELETE')
    </form>

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-5">
            <div class="modal-body text-center p-0">
                <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                    <i class="ti ti-trash fs-24 text-danger"></i>
                </span>
                <h4 class="fs-20 text-gray-9 fw-bold mb-2 mt-1">Delete Resource</h4>
                <p class="text-gray-6 mb-0 fs-16">
                    Are you sure you want to delete this resource? This action cannot be undone.
                </p>
                <div class="d-flex justify-content-center mt-3">
                    <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary fs-13 fw-medium p-2 px-3 submit-delete">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleDelete(el) {
    let deletableIds = [];

    const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    if (selectedCheckboxes.length > 0) {
        selectedCheckboxes.forEach(cb => {
            const rowId = cb.closest('tr').querySelector('.action-table-data a[data-id]').getAttribute('data-id');
            deletableIds.push(rowId);
        });
    } else if (el) {
        const rowId = el.getAttribute('data-id');
        if (rowId) deletableIds.push(rowId);
    }

    const form = document.getElementById('delete-form');
    form.querySelectorAll('input[name="deletable_ids[]"]').forEach(i => i.remove());

    deletableIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deletable_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    const deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
    deleteModal.show();
}

document.querySelector('.submit-delete').addEventListener('click', function() {
    const form = document.getElementById('delete-form');
    if (form) form.submit();
});
</script>
