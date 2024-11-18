(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const body = document.querySelector('html');

    function toggleMode() {
      if (icon.classList.contains('sun-icon')) {
        icon.classList.remove('sun-icon');
        icon.classList.add('moon-icon');
        body.classList.add('dark')
      } else if (icon.classList.contains('moon-icon')) {
        icon.classList.remove('moon-icon');
        icon.classList.add('sun-icon');
        body.classList.remove('dark')
      }
    }

    const icon = document.getElementById('dark-mode');
    icon.addEventListener('click', toggleMode);
  })
})();
