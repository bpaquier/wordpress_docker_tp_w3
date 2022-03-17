function toggleNavList() {

    const $recipesLink = document.getElementById('nav-link-recipes');
    const $recipesNavList = document.getElementById('nav-list-recipes')

    $recipesLink.addEventListener('mouseover', (e) => {
        e.preventDefault();
        $recipesNavList.classList.add('header__recipesList--isOpen')
    })

    $recipesNavList.addEventListener('mouseleave', (e) => {
        e.preventDefault();
        $recipesNavList.classList.remove('header__recipesList--isOpen')
    })


}

window.onload = function() {
    toggleNavList();
}
