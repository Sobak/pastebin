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

// Convert tab presses to 4 spaces in paste textarea
if (document.querySelector('textarea#content') !== null) {
    const indentationLevel = 4;

    // @fixme breaks undo when indented
    // @todo add shift+tab support
    document.querySelector('textarea#content').addEventListener('keydown', function (e) {
       if (e.key === 'Tab') {
           e.preventDefault();

           const start = this.selectionStart;
           const end = this.selectionEnd;

           // noinspection JSValidateTypes
           /** @type {HTMLTextAreaElement} phpStorm inspection fix */
           const textarea = e.target;

           // Set textarea value to: text before caret + indentation + text after caret
           textarea.value =
             textarea.value.substring(0, start) +
             ' '.repeat(indentationLevel) +
             textarea.value.substring(end);

           // Put caret at right position again
           this.selectionStart = this.selectionEnd = start + indentationLevel;
       }
    });
}

// Try auto-detecting language when submitting paste with no lang set
if (document.getElementById('paste-form')) {
    let hasRejectedSuggestion = false;

    document.getElementById('paste-form').addEventListener('submit', event => {
        const title = event.target.querySelector('input[name="title"]').value.trim();
        const content = event.target.querySelector('textarea[name="content"]').value;
        const languageField = event.target.querySelector('input[name="language"]');

        if (hasRejectedSuggestion) {
            // User already rejected our suggestion once, don't bother them again
            // This allows to still submit the paste without language provided
            return;
        }

        if (languageField.value.trim() !== '') {
            // Language already provided, no need to detect
            return;
        }

        if (content.trim() === '') {
            // Content is empty, save one API call and let validator do its part
            return;
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);

        event.preventDefault();

        fetch('/api/detect-language', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw Error(response.statusText);
                }

                return response.json();
            })
            .then((json) => {
                if (json.language !== null) {
                    let message = "You didn't specify the language for your paste but ";
                    message += "pastebin suspects it might be " + json.language + ". Apply?";

                    if (confirm(message)) {
                        languageField.value = json.language;
                        event.target.submit();
                    } else {
                        hasRejectedSuggestion = true;
                        languageField.focus();
                    }
                } else {
                    event.target.submit();
                }
            })
            .catch(error => {
                console.log('Error while detecting language', error);

                event.target.submit();
            });
    });
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

    // Convert parts to integers for valid comparison
    parts = parts.map(part => parseInt(part, 10));

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

        if (e.shiftKey && lastHighlightedLine !== null) {
            // Make sure that lines are sorted ascending
            let lines = [];

            lines[0] = lastHighlightedLine;
            lines[1] = lineNumber;
            lines.sort((a, b) => a - b);

            setUrlHashForLines(...lines);
            lastHighlightedLine = null;
        } else {
            deHighlightLines();

            setUrlHashForLines(lineNumber);
            lastHighlightedLine = lineNumber;
        }
    }
});

if (document.getElementById('key') !== null) {
    document.getElementById('key').value = window.localStorage.getItem("key");
}

if (document.getElementById('key') !== null && document.getElementById('key-save') !== null) {
    document.getElementById('key-save').addEventListener('click', function(e) {
        window.localStorage.setItem('key', document.getElementById('key').value);
        e.stopPropagation();
        e.preventDefault();

        createAlert('The key has been saved in LocalStorage and will be automatically supplied in the future.');
    });
}
