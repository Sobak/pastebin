function createAlert(content, type = 'success') {
    let html = '<div class="alert-box alert-' + type + '"><p>' + content + '</p></div>';

    document.body.insertAdjacentHTML('beforeend', html);

    autoHideAlert(document.body.lastChild);
}

function autoHideAlert(element) {
    setTimeout(function () {
        element.parentElement.removeChild(element);
    }, 4000);
}

document.body.querySelectorAll('.alert-box').forEach(function (element) {
    autoHideAlert(element);
});

document.body.addEventListener('click', function (e) {
    if (e.target.matches('.alert-box')) {
        e.target.parentElement.removeChild(e.target);
    }
});

if (document.getElementById('key') !== null && document.getElementById('key-save') !== null) {
    document.getElementById('key').value = window.localStorage.getItem("key");
    document.getElementById('key-save').addEventListener('click', function(e) {
        window.localStorage.setItem('key', document.getElementById('key').value);
        e.stopPropagation();
        e.preventDefault();

        createAlert('The key has been saved in LocalStorage and will be automatically supplied in the future.');
    });
}
