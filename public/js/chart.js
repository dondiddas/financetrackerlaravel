$(document).ready(function () {
  var ctx = $('#barChart').get(0).getContext('2d');

  // --- Chart datasets (replace with real data from PHP later) ---
  var chartDatasets = {
    week: {
      labels: window.chartData.labels,
      data: window.chartData.daily
    },
    month: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      data: [450, 380, 500, 620] // sample data
    },
    year: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      data: [1200, 980, 1400, 1100, 1250, 1500] // sample data
    }
  };

  // --- Base chart setup ---
  var barChartData = {
    labels: chartDatasets.week.labels,
    datasets: [{
      label: 'Daily Expenses',
      backgroundColor: 'rgba(60,141,188,0.9)',
      borderColor: 'rgba(160,141,188,0.8)',
      borderWidth: 1,
      hoverBackgroundColor: 'rgba(60,141,188,1)',
      hoverBorderColor: 'rgba(60,141,188,1)',
      data: chartDatasets.week.data
    }]
  };

  var barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        ticks: { color: '#333' },
        grid: { display: false }
      },
      y: {
        beginAtZero: true,
        ticks: {
          color: '#333',
          callback: function (value) {
            return '₱' + value;
          }
        },
        grid: { color: 'rgba(0,0,0,0.1)' }
      }
    },
    plugins: {
      legend: {
        labels: { color: '#333' }
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            return '₱' + context.parsed.y;
          }
        }
      }
    }
  };

  // --- Initialize chart ---
  var barChart = new Chart(ctx, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions
  });

  // --- Handle nav clicks ---
  $('#dateSwitcher a').on('click', function (e) {
    e.preventDefault();

    $('#dateSwitcher a').removeClass('active');
    $(this).addClass('active');

    var period = $(this).data('period');
    var dataset = chartDatasets[period];

    // Update chart
    barChart.data.labels = dataset.labels;
    barChart.data.datasets[0].data = dataset.data;
    barChart.data.datasets[0].label =
      period.charAt(0).toUpperCase() + period.slice(1) + ' Expenses';

    barChart.update();
  });
});
