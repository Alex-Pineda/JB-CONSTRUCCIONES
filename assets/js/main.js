document.addEventListener('DOMContentLoaded', () => {
  let data = {};
  const cardsContainer = document.getElementById('cards-container');

  // Cargar JSON con servicios
  fetch('assets/js/data.json')
    .then(res => res.json())
    .then(jsonData => {
      data = jsonData;
      renderCards('obra_negra');
    });

  // Botones de categorías
  const btnObraNegra = document.getElementById('btn-obra-negra');
  const btnObraBlanca = document.getElementById('btn-obra-blanca');
  const btnMantenimiento = document.getElementById('btn-mantenimiento');

  btnObraNegra.addEventListener('click', () => {
    setActiveCategory(btnObraNegra);
    renderCards('obra_negra');
  });
  btnObraBlanca.addEventListener('click', () => {
    setActiveCategory(btnObraBlanca);
    renderCards('obra_blanca');
  });
  btnMantenimiento.addEventListener('click', () => {
    setActiveCategory(btnMantenimiento);
    renderCards('mantenimiento');
  });

  function setActiveCategory(activeBtn) {
    [btnObraNegra, btnObraBlanca, btnMantenimiento].forEach(btn => {
      btn.classList.remove('active-category', 'border-green-700', 'pb-1');
      btn.classList.add('text-green-600');
    });
    activeBtn.classList.add('active-category', 'border-green-700', 'pb-1');
    activeBtn.classList.remove('text-green-600');
  }

  // Renderizar cards según categoría
  function renderCards(category) {
    cardsContainer.innerHTML = '';
    if (!data[category]) return;
    data[category].forEach(service => {
      cardsContainer.innerHTML += createCard(service);
    });

    // Añadir evento click para cada card
    document.querySelectorAll('.service-card').forEach(card => {
      card.addEventListener('click', () => {
        const id = card.getAttribute('data-id');
        // Guardar info en localStorage y redirigir
        localStorage.setItem('selectedServiceId', id);
        localStorage.setItem('selectedServiceCategory', category);
        window.location.href = 'cotizacion.html';
      });
    });
  }

function createCard({ title, description, image, link }) {
  const onclick = link ? `onclick="window.location.href='${link}'"` : "";
  return `
    <div class="service-card" ${onclick} role="button" tabindex="0" onkeypress="if(event.key==='Enter'){this.click();}">
      <img src="${image}" alt="${title}" />
      <h3>${title}</h3>
      <p>${description}</p>
    </div>
  `;
}

});