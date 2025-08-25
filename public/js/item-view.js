// item-view.js

export function initItemCharts(items) {
  const historyCtx = document.getElementById('historyChart').getContext('2d');
  new Chart(historyCtx, {
    type: 'line',
    data: {
      datasets: [{
        label: 'Chaos Value',
        data: items.map(item => ({
          x: new Date(item.fetchedAt),
          y: item.value
        })),
        borderColor: 'rgba(255, 206, 86, 1)',
        backgroundColor: 'rgba(53, 39, 5, 0.2)',
        tension: 0.2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          type: 'time',
          time: {
            tooltipFormat: 'MMM d, HH:mm',
            displayFormats: {
              hour: 'MMM d HH:mm',
              day: 'MMM d',
              week: 'MMM d',
              month: 'MMM yyyy'
            }
          },
          grid: {
            drawTicks: true,
            drawOnChartArea: true,
            color: 'rgba(212, 177, 106, 0.1)',
            borderColor: 'rgba(255, 255, 255, 0.2)'
          },
          ticks: { autoSkip: true, maxTicksLimit: 9 }
        },
        y: {
          beginAtZero: true,
          grid: {
            drawTicks: true,
            drawOnChartArea: true,
            color: 'rgba(212, 177, 106, 0.1)',
            borderColor: 'rgba(255, 255, 255, 0.2)'
          }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });

  const countCtx = document.getElementById('countChart').getContext('2d');
  new Chart(countCtx, {
    type: 'line',
    data: {
      datasets: [{
        label: 'Listing Count',
        data: items.map(item => ({
          x: new Date(item.fetchedAt),
          y: item.count ?? 0
        })),
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(53, 39, 5, 0.1)',
        tension: 0.2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          type: 'time',
          time: {
            tooltipFormat: 'MMM d, HH:mm',
            displayFormats: {
              hour: 'MMM d HH:mm',
              day: 'MMM d',
              week: 'MMM d',
              month: 'MMM yyyy'
            }
          },
          grid: {
            drawTicks: true,
            drawOnChartArea: true,
            color: 'rgba(212, 177, 106, 0.1)',
            borderColor: 'rgba(255, 255, 255, 0.2)'
          },
          ticks: { autoSkip: true, maxTicksLimit: 9 }
        },
        y: {
          beginAtZero: true,
          grid: {
            drawTicks: true,
            drawOnChartArea: true,
            color: 'rgba(212, 177, 106, 0.1)',
            borderColor: 'rgba(255, 255, 255, 0.2)'
          }
        }
      },
      plugins: { legend: { display: false } }
    }
  });
}
