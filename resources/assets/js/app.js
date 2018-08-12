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

function checkUrlHashForLines() {
    if (window.location.hash && /^#L\d+(-\d+)?$/.test(window.location.hash)) {
        highlightLine(window.location.hash.substring(2));
    }
}

function setUrlHashForLines(start, end = null) {
    let hashValue = 'L' + start;

    if (end) {
        hashValue += '-' + end;
    }

    // Use history.replaceState instead of setting
    // window.location.hash to prevent page scroll
    const urlWithNoHash = document.location.href.split('#', 2)[0];

    history.replaceState({}, document.title, urlWithNoHash + '#' + hashValue);

    // ...since window.location.hash was not changed
    // we need to trigger associated function here
    highlightLine(hashValue.substring(1));
}

function highlightLine(line) {
    let parts = line.split('-', 2);
    parts[1] = parts[1] || parts[0];

    // Convert parts to ints for valid comparison
    parts = parts.map(function (part) {
        return parseInt(part, 10);
    });

    if (parts[0] > parts[1]) {
        return;
    }

    for (let i = parts[0]; i <= parts[1]; i++) {
        const lineElement = document.getElementById('L' + i);

        if (lineElement) {
            lineElement.classList.add('highlight');
        }
    }
}

function deHighlightLines() {
    document.querySelectorAll('.line.highlight').forEach(function (line) {
        line.classList.remove('highlight');
    });
}

// Highlight selected lines on page load
checkUrlHashForLines();

// For highlight ranges scroll to the start line
if (window.location.hash && /^#L\d+-\d+$/.test(window.location.hash)) {
    const rangeStart = window.location.hash.substring(2).split('-')[0];
    const lineElement = document.getElementById('L' + rangeStart);

    if (lineElement !== null) {
        lineElement.scrollIntoView();
    }
}

addEventListener('hashchange', function () {
    checkUrlHashForLines();
});

document.body.querySelectorAll('.alert-box').forEach(function (element) {
    autoHideAlert(element);
});

document.body.addEventListener('click', function (e) {
    if (e.target.matches('.alert-box')) {
        e.target.parentElement.removeChild(e.target);
    }
});

let lastHighlightedLine = null;
document.body.addEventListener('click', function (e) {
    if (e.target.matches('.counter')) {
        const lineNumber = e.target.getAttribute('data-ln');

        if (window.event.ctrlKey && lastHighlightedLine !== null) {
            // Make sure that lines are sorted ascending
            let lines = [];

            lines[0] = lastHighlightedLine;
            lines[1] = lineNumber;
            lines.sort(function (a, b) {
                return a - b;
            });

            setUrlHashForLines(...lines);
            lastHighlightedLine = null;
        } else {
            deHighlightLines();

            setUrlHashForLines(lineNumber);
            lastHighlightedLine = lineNumber;
        }
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
