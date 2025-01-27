function alter() {
    const main = document.getElementById('maindiv');
    const alter = document.getElementById('alter');
    alter.style.display = 'grid';
    alter.style.padding = '0px 20px 20px';
    main.style.display = 'none';
}

function alter_approved() {
    const approved = document.getElementById('approved');
    const declined = document.getElementById('declined');
    const pending = document.getElementById('pending');
    approved.style.display = 'block';
    declined.style.display = 'none';
    pending.style.display = 'none';
}

function alter_declined() {
    const declined = document.getElementById('declined');
    const approved = document.getElementById('approved');
    const pending = document.getElementById('pending');
    declined.style.display = 'block';
    approved.style.display = 'none';
    pending.style.display = 'none';
}

function alter_pending() {
    const pending = document.getElementById('pending');
    const approved = document.getElementById('approved');
    const declined = document.getElementById('declined');
    pending.style.display = 'block';
    approved.style.display = 'none';
    declined.style.display = 'none';
}

function approved() {
    const approved = document.getElementById('approved');
    approved.style.display = 'none';
}

function declined() {
    const declined = document.getElementById('declined');
    declined.style.display = 'none';
}

function pending() {
    const pending = document.getElementById('pending');
    pending.style.display = 'none';
}