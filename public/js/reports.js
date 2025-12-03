(function(){
    const data = window.REPORTS || {};
    const categories = data.categories || [];
    const months = data.months || [];
    const monthlyTotals = data.monthlyTotals || [];

    function randColor(i) {
        // simple palette
        const palette = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796','#5a5c69','#2e59d9','#17a673'];
        return palette[i % palette.length];
    }

    // Pie chart for categories
    const pieCtx = document.getElementById('categoriesPie');
    if (pieCtx && categories.length) {
        const labels = categories.map(c => c.category_name + ' (' + c.category_type + ')');
        const values = categories.map(c => parseFloat(c.total));
        const bg = values.map((v,i) => randColor(i));

        new Chart(pieCtx, {
            type: 'pie',
            data: { labels, datasets: [{ data: values, backgroundColor: bg }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    // Monthly line/bar chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx && months.length) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Net Total',
                    data: monthlyTotals,
                    backgroundColor: '#4e73df'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

})();
