const selectAllCheckbox = document.getElementById('select_all');
selectAllCheckbox.addEventListener('change', event => {
    document.querySelectorAll('input[name="pastes[]"]').forEach(checkbox => {
        checkbox.checked = event.currentTarget.checked;

        updateRowClass(checkbox);
        updateBulkRemoveButton();
    });
});

document.querySelector('.datatable').addEventListener('click', event => {
    if (event.target.tagName === 'TD') {
        const checkbox = event.target.parentElement.querySelector('input[name="pastes[]"]');
        checkbox.checked = !checkbox.checked;

        updateRowClass(checkbox);
        updateBulkRemoveButton();
    }
});

const checkboxes = document.querySelectorAll('input[name="pastes[]"]');
checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', event => {
        updateRowClass(event.target);
        updateBulkRemoveButton();
    });
});

document.querySelectorAll('.datatable a').forEach(function (link) {
    link.addEventListener('click', event => {
        if (!event.target.dataset.confirm) {
            return;
        }

        if (!confirm('Do you want to remove paste ' + event.target.dataset.confirm + '?')) {
            event.preventDefault();
        }
    });
});

document.getElementById('bulk_remove_form').addEventListener('submit', event => {
    const selectedCount = document.querySelectorAll('input[name="pastes[]"]:checked').length;

    if (!confirm('Do you really want to remove ' + selectedCount + ' pastes?')) {
        event.preventDefault();
    }
});

function updateRowClass(checkbox) {
    const tableRow = checkbox.parentElement.parentElement;
    if (checkbox.checked) {
        tableRow.classList.add('checked');
    } else {
        tableRow.classList.remove('checked');
    }
}

function updateBulkRemoveButton() {
    const selectedCount = document.querySelectorAll('input[name="pastes[]"]:checked').length;
    const bulkRemoveButton = document.getElementById('bulk_remove_button');

    bulkRemoveButton.disabled = selectedCount < 1;
}
