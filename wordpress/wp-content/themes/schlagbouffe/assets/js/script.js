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

    /* Navigation */
    toggleNavList();

    /* Details */
    const $detailsBtn = document.querySelectorAll("[data-detail]");
    const $detailsContent = document.querySelectorAll("[data-detail-content]");
    if ($detailsBtn.length > 0) {
        $detailsBtn.forEach(($btn) => {
            $btn.addEventListener("click", (e) => {
                e.preventDefault();
                const detail = $btn.getAttribute("data-detail");
                $detailsBtn.forEach(($btn) => {
                    if ($btn.getAttribute("data-detail") === detail) {
                        $btn.classList.add("active");
                    } else {
                        $btn.classList.remove("active");
                    }
                });

                $detailsContent.forEach(($content) => {
                    if ($content.getAttribute("data-detail-content") === detail) {
                        $content.classList.add("active");
                    } else {
                        $content.classList.remove("active");
                    }
                });
            });
        });
    }


    /* Input Number */
    const $inputsNumber = document.querySelectorAll("[data-input-number]");
    if ($inputsNumber.length > 0) { 
        $inputsNumber.forEach(($input) => {
            const $minus = $input.querySelector("[data-input-number-minus]");
            const $plus = $input.querySelector("[data-input-number-plus]");
            const $value = $input.querySelector("[data-input-number-value]");
            function updateValue($value, type) {
                const current = parseInt($value.innerHTML);
                if (type === "minus") { 
                    if (current > 1) {
                        $value.innerHTML = current - 1;
                    }
                } else if (type === "plus") {
                    $value.innerHTML = current + 1;
                }
            }
            
            $minus.addEventListener("click", (e) => {
                e.preventDefault();
                updateValue($value, "minus");
            });

            $plus.addEventListener("click", (e) => { 
                e.preventDefault();
                updateValue($value, "plus");
            })
        });
    }
}
