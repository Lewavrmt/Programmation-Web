// fonction  de cette bibliotheque : 

//1- loadSlotsData()
//2- stringToColor(str)
//3- addMinutes(time, minutes)
//4- fillTable(slots)
//5- onSlotEditButtonClick(slot, button)


// Gestionnaires d'événements 

// "click" event pour les boutons "td button"
// "click" event pour le bouton "annuler"
// "submit" event pour le formulaire "slot-form"







// charger les donnees des creneaux horaires a partir d'un fichier JSON
function loadSlotsData() {
    fetch("slots.json") // envoyer une requete afin de recuperer le contenu du fichier "slots.json".
        .then((response) => response.json()) // convertir le contenu JSON en un objet JavaScript.
        .then((data) => {
            fillTable(data); // appelle la fonction fillTable(data) en lui passant les donnees des creneaux horaires (data) recuperees a partir du fichier JSON.
                             // fillTable est une fonction que nous avons coder plus loin dans le code 
        })
        .catch((error) => console.error("Erreur lors de la récupération des slots : ", error));
}
loadSlotsData();



  
  


  // genere une couleur unique pour chaque cour differnent . 
  function stringToColor(str) {
    let hashCode = 0;
    for (let i = 0; i < str.length; i++) { // parcourt chaque caractere de la chaene de caracteres
      hashCode = str.charCodeAt(i) + ((hashCode << 5) - hashCode); //  calcule un code de hachage
    }
     
    //  extrait les composants rouge (r), vert (g) et bleu (b) de la couleur en utilisant des operations de masquage et de decalage.
    const r = ((hashCode & 0xFF0000) >> 16) | 0x80;
    const g = ((hashCode & 0x00FF00) >> 8) | 0x80;
    const b = (hashCode & 0x0000FF) | 0x80;
    
    // elle utilise l'operateur logique OR avec 0x80 pour definir au moins la moitie de l'intensite pour chaque composant de couleur. pour etre lumineut et clair 
    const color = `rgb(${r}, ${g}, ${b})`;
    return color;
  }






  // elle prend en argument une heur et un noubre de min, Elle ajoute ces minutes a l'heure donnee et retourne l'heure resultante sous forme de chaine de caracteres.
  function addMinutes(time, minutes) {
    let newTime = new Date('2023-02-13 ' + time);
    newTime.setMinutes(newTime.getMinutes() + minutes);//utilise la méthode setMinutes() sur l'objet Date pour ajouter le nombre de minutes fourni a l'heure actuelle
    return newTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    // utilise  la methode toLocaleTimeString() pour retourne l'heure avec le format "HH:mm

  }






  // remplit  un tableau HTML avec les informations de chaque creneau
  function fillTable(slots) {
    // parcourt chaque creneau (slot) du tableau
    slots.forEach((slot) => {     
        // Pour chaque créneau, elle extrait les numéros de groupe à partir du champ groupes et stocke ces numéros dans la variable groupNumbers.
        const groupNumbers = slot.groupes.map((group) => group.replace("groupe ", "").trim());
        let date = slot.date;
        let heureDebut = slot.heureDebut;
        let heureFin = slot.heureFin;

        const timeDifference = new Date(`1970-01-01T${heureFin}:00`) - new Date(`1970-01-01T${heureDebut}:00`);
        const rowspan = timeDifference / (15 * 60 * 1000);

        groupNumbers.forEach((groupe) => {
            let heure = addMinutes(heureDebut, 0).replace(":", "h");

            const cell = document.querySelector(`.cell-${heure}-${date}-Gr${groupe}`);
            if (cell) {
                cell.innerHTML = `<div class="slot">
                                    <p>${slot.type}</p>
                                    <p>${slot.matiere}</p>
                                    <p>${slot.enseignant}</p>
                                    <p>${slot.salle}</p>
                                    <button class="edit-button">Modifié</button>
                                  </div>`;
                cell.style.backgroundColor = stringToColor(slot.matiere);
                cell.rowSpan = rowspan;

                

                

                const editButton = cell.querySelector(".edit-button");
                editButton.addEventListener("click", () => onSlotEditButtonClick(slot, editButton));
            }
        });
    });
}









// Affiche le formulaire pop-up lorsque l'utilisateur clique sur un bouton (+) et est autorise a modifier le calendrier
document.querySelectorAll("td button").forEach((button) => {
  button.addEventListener("click", () => {
      const userRole = document.querySelector("body").getAttribute("data-role");
      if (userRole === "responsable" || userRole === "coordinateur") {
          const cell = button.closest("td");
          const cellInfo = cell.className.split("-");

          const heure = cellInfo[1].replace("h", ":");
          const date = cellInfo[2];
          const groupe = cellInfo[4].replace("Gr", "");

          document.getElementById("date").value = date;
          document.getElementById("groupe").value = groupe;
          document.getElementById("debut-cour").value = heure;

          const popup = document.getElementById("popup-form");
          popup.classList.remove("hidden");
      }
  });
});





// Ferme le formulaire pop-up lorsque l'utilisateur clique sur le bouton "Annuler"
document.getElementById("annuler").addEventListener("click", () => {
  const popup = document.getElementById("popup-form");
  popup.classList.add("hidden");
});



// Soumet le formulaire et met à jour le fichier JSON
document.getElementById("slot-form").addEventListener("submit", (event) => {
  event.preventDefault();

  const groupe = document.getElementById("groupe").value;
  const type = document.getElementById("type").value;
  const salle = document.getElementById("salle").value;

  const data = {
      type: type,
      matiere: document.getElementById("cours").value,
      enseignant: document.getElementById("prof").value,
      salle: salle,
      date: document.getElementById("date").value,
      heureDebut: document.getElementById("debut-cour").value,
      heureFin: document.getElementById("fin-cour").value,
      groupes: [`groupe ${groupe}`],
  };

  $.ajax({
      url: "update_slots.php",
      method: "POST",
      dataType: "json",
      data: { slot: JSON.stringify(data) },
  })
      .done((response) => {
          if (response.success) {
              location.reload();
          } else {
              alert("Erreur lors de l'ajout du cours.");
          }
      })
      .fail((error) => {
          console.error("Erreur lors de l'envoi de la requête AJAX :", error);
      });
});










// Affiche le formulaire pop-up pour modifier ou supprimer un slot
function onSlotEditButtonClick(slot, button) {
  const userRole = document.querySelector("body").getAttribute("data-role");
  if (userRole === "responsable" || userRole === "coordinateur") {
      document.getElementById("date").value = slot.date;
      document.getElementById("groupe").value = slot.groupes[0].replace("groupe ", "");
      document.getElementById("type").value = slot.type;
      document.getElementById("cours").value = slot.matiere;
      document.getElementById("prof").value = slot.enseignant;
      document.getElementById("salle").value = slot.salle;
      document.getElementById("debut-cour").value = slot.heureDebut;
      document.getElementById("fin-cour").value = slot.heureFin;

      const popup = document.getElementById("popup-form");
      popup.classList.remove("hidden");
  }
}










document.getElementById("supprimer").addEventListener("click", () => {
  const date = document.getElementById("date").value;
  const groupe = `groupe ${document.getElementById("groupe").value}`;
  const heureDebut = document.getElementById("debut-cour").value;

  const slotIndex = slots.findIndex(
      (slot) =>
          slot.date === date &&
          slot.groupes.includes(groupe) &&
          slot.heureDebut === heureDebut
  );

  if (slotIndex !== -1) {
      slots.splice(slotIndex, 1);
      fetch("update_slots.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ slots }),
      })
          .then((response) => {
              if (response.ok) {
                  location.reload();
              } else {
                  throw new Error("Erreur lors de la suppression du slot");
              }
          })
          .catch((error) => {
              console.error("Erreur lors de l'envoi de la requête AJAX :", error);
          });
  }
});

// Vous pouvez ajouter ici des fonctions pour gérer les événements des boutons d'édition des listes,
// ainsi que pour envoyer des requêtes AJAX pour mettre à jour les fichiers JSON.








