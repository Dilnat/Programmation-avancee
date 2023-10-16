function supprimerFeedy(event) {
    //Création de l'objet
    let xhr = new XMLHttpRequest();


    //Et si on a une route paramétrable :
    let button = event.target;
    let publicationId = button.dataset.publicationId;
    let URL = Routing.generate('deletePublication', {'id': publicationId});
    //La méthode utilisée (GET, POST, PUT, PATCH ou DELETE), l'URL et si la requête est asynchrone ou non.
    xhr.open('DELETE', URL, true);
    xhr.onload = function () {
        //Fonction déclenchée quand on reçoit la réponse du serveur.

        //xhr.status permet d'accèder au code de réponse HTTP (200, 204, 403, 404, etc...)
        if (xhr.status === 204){
            let feedy = button.closest(".feedy");
            feedy.remove();
        }
    };
    xhr.send(null);
}

let buttons = document.getElementsByClassName("delete-feedy");
Array.from(buttons).forEach(function (button) {
    button.addEventListener("click", supprimerFeedy);
});