

    //-------------
    //- BAR CHART -
    //-------------
    $(document).ready(function() {
  var barChartCanvas = $('#barChart').get(0).getContext('2d');

  var barChartData = {
    labels: window.chartData.labels,
    datasets: [{
      label: 'Daily Expenses',
      backgroundColor: 'rgba(60,141,188,0.9)',
      borderColor: 'rgba(160,141,188,0.8)',
      borderWidth: 1,
      hoverBackgroundColor: 'rgba(60,141,188,1)',
      hoverBorderColor: 'rgba(60,141,188,1)',
      data: window.chartData.daily
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
          callback: function(value) {
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
          label: function(context) {
            return '₱' + context.parsed.y;
          }
        }
      }
    }
  };

  new Chart(barChartCanvas, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions
  });
});

    
