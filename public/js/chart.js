$(document).ready(function () {
  var ctx = $('#barChart').get(0).getContext('2d');

  // --- Chart datasets (replace with real data from PHP later) ---
var chartDatasets = {
    week: {
        labels: window.chartData.week.labels,
        data: window.chartData.week.data
    },
    month: {
        labels: window.chartData.month.labels,
        data: window.chartData.month.data
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
    },{
          label               : 'Limit Expenses',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          borderWidth: 1,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: window.limitData.week
        },]
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
  var lineChartOptions = $.extend(true, {}, barChartOptions)
    var lineChartData = $.extend(true, {}, barChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

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

    /// Update chart function
barChart.data.labels = dataset.labels;
barChart.data.datasets[0].data = dataset.data;
barChart.data.datasets[0].label = period.charAt(0).toUpperCase() + period.slice(1) + ' Expenses';

// Update limit dataset (second dataset)
if (barChart.data.datasets[1]) {
    barChart.data.datasets[1].data = window.limitData[period];
} else {
    // If limit dataset doesn't exist yet, add it
    barChart.data.datasets.push({
        label: period.charAt(0).toUpperCase() + period.slice(1) + ' Limit',
        data: window.limitData[period],
        type: 'line', // show as line
        borderColor: 'rgba(255, 99, 132, 0.8)',
        borderWidth: 2,
        fill: false,
        pointRadius: 3
    });
}

barChart.update();

  });
});
