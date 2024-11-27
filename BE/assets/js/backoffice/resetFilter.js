(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const resetButtons = document.querySelectorAll('.resetFilter');
    resetButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const form = btn.closest('form');

        const inputsText = form.querySelectorAll('input[type="text"]');
        inputsText.forEach((input) => input.value = '');

        const inputsCheckbox = form.querySelectorAll('input[type="checkbox"]');
        inputsCheckbox.forEach((input) => input.checked = false);

        const selects = form.querySelectorAll('select');
        selects.forEach((dropdown) => dropdown.selectedIndex = 0);
      });
    })
  })
})();
