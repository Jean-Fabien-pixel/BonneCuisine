// Déclaration des variables globales pour stocker les traductions
var translations = {};
var lang = document.documentElement.lang || "fr";  // Détection automatique de la langue

// Charger les traductions au démarrage
function ChargerTraductions() {
    fetch(`json/${lang}.json`)
        .then(response => response.json())
        .then(data => {
            translations = data;  // Stocker les traductions dans la variable globale
        })
        .catch(error => {
            console.error("Erreur lors du chargement des traductions :", error);
            translations = {
                "email_prompt": "Veuillez entrer votre email pour la commande :",
                "email_requis": "⚠ Email requis.",
                "email_invalide": "⚠ Veuillez entrer un email valide.",
                "email_envoi": "Envoi en cours..."
            };
        });
}

// Appeler le chargement des traductions dès le chargement du script
ChargerTraductions();

function AfficherDate() {
    var date = new Date();
    var lang = document.documentElement.lang || "fr";

    var options = {weekday: "long", day: "numeric", month: "long", year: "numeric"};
    var dateFormatee = date.toLocaleDateString(lang, options);

    dateFormatee = dateFormatee.charAt(0).toUpperCase() + dateFormatee.slice(1);

    document.getElementById("date").innerHTML = dateFormatee;
}

// Fonction d'envoi de la commande
function EnvoyerCommande() {
    var email = prompt(translations["email_prompt"] || "Veuillez entrer votre email :");
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email) {
        alert(translations["email_requis"] || "⚠ Email requis.");
        return false;
    }
    if (!emailRegex.test(email)) {
        alert(translations["email_invalide"] || "⚠ Veuillez entrer un email valide.");
        return false;
    }

    // Ajouter l'email au champ caché
    document.getElementById("emailInput").value = email;

    // Afficher un message temporaire
    document.getElementById("msgErreur").innerHTML = translations["email_envoi"] || "Envoi en cours...";

    setTimeout(() => {
        document.querySelector("form").submit();
    }, 1000);
}


function RecupererEmailMdpOublie() {
    var email = document.getElementById("courriel").value.trim();
    var lang = document.documentElement.lang || "fr"; // Récupérer la langue de la page

    fetch(`json/${lang}.json`)
        .then(response => response.json())
        .then(translations => {
            if (email.length !== 0) {
                console.log(email);
                window.location.href = `connexion.php?identifiantsOublies=${encodeURIComponent(email)}`;
            } else {
                var placeErreur = document.getElementById("placeErreur");
                placeErreur.innerHTML = translations["email_mdp_oublie"];
            }
        })
        .catch(error => console.error("Erreur lors du chargement des traductions :", error));
}


function VerifierMdp() {
    var mdp1 = document.getElementById("password1").value;
    var mdp2 = document.getElementById("password2").value;

    if (mdp1 !== mdp2) {
        var placeErreur = document.getElementById("msgErreur");
        placeErreur.innerHTML = translations["mdp_non_correspondant"];
        return false;
    }

    return true;
}

function ValiderSuppression() {
  var cpt = 0,
    valide = false;
  var checked = document.querySelectorAll("input[type=checkbox]");
  for (var i = 0; i < checked.length; i++) {
    if (checked[i].checked === true) {
      cpt++;
    }
  }

  if (cpt === 1) {
    if (confirm("Voulez-vous supprimer ce produit ?")) {
      valide = true;
    }
  } else if (cpt > 1) {
    if (confirm(`Voulez-vous supprimer ces ${cpt} produits ?`)) {
      valide = true;
    }
  }

  return valide;
}
