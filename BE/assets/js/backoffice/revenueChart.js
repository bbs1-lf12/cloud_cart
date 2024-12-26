import Chart from 'chart.js/auto';

(function () {
  document.addEventListener('DOMContentLoaded', () => {
    let chart = null;
    const canvas = document.querySelector('.dashboard-revenue');
    const btns = document.querySelectorAll('.dashboard-revenue__btn');

    if (!canvas || !btns || !(btns.length > 0)) {
      console.error('HTML element not found.');
      return;
    }

    async function updateChart(date) {
      const response = await fetch(`/dashboard/revenue/${date}`);

      const data = await response.json();

      chart.data = {
        labels: data.label,
        datasets: [
          {
            label: 'Daily Revenue',
            data: data.data,
            borderWidth: 1,
            borderColor: '#17A2B8',
            backgroundColor: 'rgba(23,162,184,0.7)',
          },
        ],
      };
      chart.update();
    }

    function selectBtn(btn) {
      btns.forEach((btn) => btn.classList.remove('selected'));
      btn.classList.add('selected');
    }

    async function initDashboard() {
      chart = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: [],
          datasets: [
            {
              label: '# of Votes',
              data: [],
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });

      await updateChart(1);

      btns.forEach((btn) => {
        const days = btn.dataset.days;
        btn.addEventListener('click', async () => {
          await updateChart(days);
          selectBtn(btn);
        });
      });
    }

    if (!canvas.dataset.init) {
      initDashboard();
      canvas.dataset.init = true;
    }
  });
})();
