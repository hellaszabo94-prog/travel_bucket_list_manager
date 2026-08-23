document.addEventListener("DOMContentLoaded",() => {

    const btn = document.querySelector("#mobile button#navRwd");
    const navliste = document.querySelector("#mobile  ul");

    const menuIcon = document.querySelector("#menuIcon");
    const closeIcon = document.querySelector("#closeIcon");

    btn.addEventListener("click",() => {

        if (navliste.classList.contains("hidden")) {
                navliste.classList.remove("hidden");
                navliste.classList.add("flex");

                menuIcon.classList.add("hidden");
                closeIcon.classList.remove("hidden");
        } else {
                navliste.classList.add("hidden");
                navliste.classList.remove("flex");

                menuIcon.classList.remove("hidden");
                closeIcon.classList.add("hidden");
        }
        
    });
    
    const navelemente = document.querySelectorAll("#mobile li");

    for(let i=0; i<navelemente.length; i++) {

        navelemente[i].addEventListener("click",() => {

            navliste.classList.add("hidden");
            navliste.classList.remove("flex");

        });
    }
});