function showOverlay(id, name, description, description_long) {
    let overlayNameDiv = document.getElementById('overlay-text');
    document.getElementById('event-form').style.display = 'flex';
    overlayNameDiv.innerHTML = description_long;
}