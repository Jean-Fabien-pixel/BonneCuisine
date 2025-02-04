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
    