/**
 * Starts hiding the flash messages after 5 seconds
 */
(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const flashMessages = document.querySelectorAll('.flash__message');
    if (flashMessages.length === 0) return;

    let counter = 1000;
    flashMessages.forEach((el) => {
      setTimeout(() => {
        el.classList.add('hidden');
      }, 5000 + counter);
      counter += 1000;
    });
  });
})();
