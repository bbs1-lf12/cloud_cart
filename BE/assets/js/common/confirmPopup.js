/**
 * Add to an element with class 'confirm-popup' the following data attributes:
 * - data-popup-body: the text that will be displayed in the popup
 * - data-popup-href: the href that will be redirected to if the user confirms the action
 *
 * Check that the partial @see 'BE/templates/common/popup/_confirm_popup.html.twig'
 * is included in the base template
 *
 */
(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.confirm-popup');
    if (elements.length === 0) return;

    const popupTemplate = document.querySelectorAll('.popup');
    if (popupTemplate.length > 1) {
      console.error('There are more than one popup template, check base template');
      return;
    }

    function initConfirmPopup(element) {
      const body = element.dataset.popupBody;
      const href = element.dataset.popupHref;

      const popup = popupTemplate[0].cloneNode(true);

      const p = document.createElement('p');
      p.classList.add('text-center');
      p.innerHTML = body;
      popup.querySelector('.popup__body').appendChild(p);

      element.classList.add('relative');
      element.appendChild(popup);

      element.addEventListener('click', () => {
        popup.classList.remove('hidden');
      });

      popup.querySelector('.popup-confirm').addEventListener('click', () => {
        window.location.href = href;
      });

      popup.querySelector('.popup-cancel').addEventListener('click', (event) => {
        event.stopPropagation();
        popup.classList.remove('hidden');
        popup.classList.add('hidden');
      });
    }

    elements.forEach(element => {
      if (element.dataset.init) return;
      initConfirmPopup(element)
      element.dataset.init = true;
    });
  })
})();
