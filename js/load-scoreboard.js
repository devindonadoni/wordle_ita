function loadClassificaParole() {
  $.ajax({
    url: 'api/classificaParola.php', // Assicurati che il path sia corretto
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      $('#scoreboard').empty(); // Pulisce il contenuto precedente

      data.slice(0, 10).forEach((item, index) => {
        let icona = `<i class="posizione">${index + 1}</i>`;

        // Medaglie per le prime tre posizioni
        if (index === 0)
          icona = `<i class="fa-solid fa-medal" style="color: #ffd700;"></i>`;
        else if (index === 1)
          icona = `<i class="fa-solid fa-medal" style="color: #c0c0c0;"></i>`;
        else if (index === 2)
          icona = `<i class="fa-solid fa-medal" style="color: #cd7f32;"></i>`;

        // Crea l'HTML per ogni posizione
        const html = `
          <div class="single-position medal" id="${index + 1}">
            ${icona}
            <h1>${item.parola}</h1>
            <h1 style="transform: translateX(20px);">${item.vittorie}</h1>
          </div>
        `;

        $('#scoreboard').append(html);
      });

      autoScale(); // Richiama la funzione di styling dinamico se già definita
    },
    error: function (xhr, status, error) {
      console.error("Errore nel recupero della classifica parole:", error);
    }
  });
}
