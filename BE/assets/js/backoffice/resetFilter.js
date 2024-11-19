(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const resetButtons = document.querySelectorAll('.resetFilter');
    resetButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const form = btn.closest('form');
        const inputs = form.querySelectorAll('input');
        inputs.forEach((input) => input.value = '');
      });
    })
  })
})();
