function AfficherDate() {
    var date = new Date();
    var jours = [
        "Dimanche",
        "Lundi",
        "Mardi",
        "Mercredi",
        "Jeudi",
        "Vendredi",
        "Samedi",
    ];
    var mois = [
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Août",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre",
    ];
    var divDate = document.getElementById("date");
    divDate.innerHTML =
        jours[date.getDay()] +
        ", le " +
        date.getDate() +
        " " +
        mois[date.getMonth()] +
        " " +
        date.getFullYear();
}

function EnvoyerCommande() {
    var email = prompt("Veuillez entrer votre email pour la commande :");
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        alert("⚠ Email requis pour valider la commande.");
        return;
    }
    if (!emailRegex.test(email)) {
        alert("⚠ Veuillez entrer un email valide.");
        EnvoyerCommande();
    }
    // Ajouter l'email au champ caché du formulaire
    document.getElementById("emailInput").value = email;
    // Petite pause pour s'assurer que l'email est bien ajouté avant soumission
    setTimeout(() => {
        document.querySelector("form").submit();
    }, 20000);
}

function RecupererEmailMdpOublie() {
    var email = document.getElementById("courriel").value;
    if (email.trim().length !== 0) {
        console.log(email)
        window.location.href = `connexion.php?identifiantsOublies=${encodeURIComponent(email)}`;
    } else {
        var placeErreur = document.getElementById("placeErreur");
        placeErreur.innerHTML = "Entrez votre courriel dans le champ requis"
    }
}

function VerifierMdp() {
    var mdp1 = document.getElementById("password1").value,
        mdp2 = document.getElementById("password2").value;

    if (mdp1 !== mdp2) {
        var placeErreur = document.getElementById("msgErreur")
        placeErreur.innerHTML = "Les mots de passe ne correspondent pas";
        return false;
    }
    return true
}