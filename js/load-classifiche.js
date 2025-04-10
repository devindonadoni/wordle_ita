function loadClassifiche() {
    $.ajax({
      url: 'api/classificaUtenti.php', // cambia se necessario
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        $('#leaderboard').empty();

        data.slice(0, 10).forEach((item, index) => {
          let icona = `<i class="posizione">${index + 1}</i>`; // Posizione visuale

          if (index === 0)
            icona = `<i class="fa-solid fa-medal" style="color: #ffd700;"></i>`;
          else if (index === 1)
            icona = `<i class="fa-solid fa-medal" style="color: #c0c0c0;"></i>`;
          else if (index === 2)
            icona = `<i class="fa-solid fa-medal" style="color: #cd7f32;"></i>`;

          // Costruisce l'HTML
          const html = `
            <div class="single-position medal" id="${index + 1}">
              ${icona}
              <h1>${item.utente}</h1>
              <h1 style="transform: translateX(20px);">${item.vittorie}</h1>
            </div>
          `;
          $('#leaderboard').append(html);
        });

        autoScale(); // Applica lo stile dinamico se usato
      },
      error: function (xhr, status, error) {
        console.error('Errore nel recupero della classifica:', error);
      }
    });
};


function redirect(link){
  window.location.href = link;
}