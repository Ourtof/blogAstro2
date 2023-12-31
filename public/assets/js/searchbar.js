// Barre de recherche 

const searchBar = document.querySelector("#searchbar");

searchBar.addEventListener("keyup", (e) => {
    const searchedLetters = e.target.value;
    const cards = document.querySelectorAll(".card");
    filterElements(searchedLetters, cards);
});

function filterElements(letters, elements) {
    if(letters.length > 1) {
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].textContent.toLowerCase().includes(letters)) {
                elements[i].style.display = "block";
            } else {
                elements[i].style.display = "none";
            }
        }
    }
}
