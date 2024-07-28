document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('search-input');
    const gameFilter = document.getElementById('game-filter');
    const userCards = document.querySelectorAll('.user-card');
    const resultCount = document.querySelector('.result-count');

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedGameId = gameFilter.value;

        let count = 0;

        userCards.forEach(card => {
            const userNameRaw = card.querySelector('.user-info').children[0].textContent.toLowerCase().trim();
            const username = userNameRaw.split(":")[1].trim();
            const games = Array.from(card.querySelectorAll('.user-games .game p')).map(p => p.textContent.toLowerCase().trim());
            console.log(games)

            const matchesSearchTerm = username.includes(searchTerm);
            const matchesGameFilter = selectedGameId === '-1' || games.includes(selectedGameId.toLowerCase());

            if (matchesSearchTerm && matchesGameFilter) {
                card.style.display = '';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        resultCount.textContent = `${count} results found`;
    }

    searchInput.addEventListener('input', filterUsers);
    gameFilter.addEventListener('change', filterUsers);

    // Initial filtering on page load
    filterUsers();
});

function redirectToProfile(email) {
    var profileUrl = '/profile/?profile=' + email;
    window.location.href = profileUrl;
}

function randomBackground() {
    let random = Math.floor(Math.random() * 3) + 1;
    return "url('/assets/img/user/user_bg_" + random + ".jpg')";
}