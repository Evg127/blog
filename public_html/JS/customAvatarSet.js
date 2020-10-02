const attachment = document.querySelector('#attachment');
attachment.oninput = () => {
    document.querySelector(`.choose-file`).disabled = false;
    document.querySelector(`.choose-file`).checked = true;
}