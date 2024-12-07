(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.input-popup');
    if (elements.length === 0) return;

    const popupTemplate = document.querySelectorAll('.input-popup__template');
    if (popupTemplate.length > 1) {
      console.error('There are more than one popup template, check base template');
      return;
    }

    function initInputPopup(element) {
      const message = element.dataset.popupMessage;
      const action = element.dataset.popupAction;
      const method = element.dataset.popupSubmitMethod;
      const placeholder = element.dataset.popupPlaceholder;

      const popup = popupTemplate[0].cloneNode(true);

      const p = document.createElement('p');
      p.classList.add('text-center');
      p.innerHTML = message;
      popup.querySelector('.popup__message').appendChild(p);

      element.classList.add('relative');
      element.appendChild(popup);

      element.addEventListener('click', () => {
        popup.classList.remove('hidden');
      });

      const form = popup.querySelector('form');
      form.action = action;
      form.method = method;
      const input = form.querySelector('.input-popup__input');
      input.placeholder = placeholder;

      popup.querySelector('.popup-confirm').addEventListener('click', () => {
        if (input && input.value) {
          input.value = input.value.trim();
          form.submit();
        } else {
          alert('Please, insert a value...');
        }
      });

      popup.querySelector('.popup-cancel').addEventListener('click', (event) => {
        event.stopPropagation();
        popup.classList.remove('hidden');
        popup.classList.add('hidden');
      });
    }

    elements.forEach(element => {
      if (element.dataset.init) return;
      initInputPopup(element)
      element.dataset.init = true;
    });
  })
})();
