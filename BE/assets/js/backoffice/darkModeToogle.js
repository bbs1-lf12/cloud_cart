(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const body = document.querySelector('html');

    const darkModeKey = 'darkMode';
    const isDarkMode = localStorage.getItem(darkModeKey);

    function setLightMode() {
      localStorage.setItem(darkModeKey, 'false');
      icon.classList.remove('moon-icon');
      icon.classList.add('sun-icon');
      body.classList.remove('dark');
    }

    function setDarkMode() {
      localStorage.setItem(darkModeKey, 'true');
      icon.classList.remove('sun-icon');
      icon.classList.add('moon-icon');
      body.classList.add('dark');
    }

    function toggleMode() {
      if (body.classList.contains('dark')) {
        setLightMode();
      } else {
        setDarkMode();
      }
    }

    if (isDarkMode !== 'true') {
      localStorage.setItem(darkModeKey, 'false');
      body.classList.remove('dark');
    } else {
      localStorage.setItem(darkModeKey, 'true');
      body.classList.add('dark');
    }

    const icon = document.getElementById('dark-mode');
    icon.addEventListener('click', toggleMode);
  });
})();
