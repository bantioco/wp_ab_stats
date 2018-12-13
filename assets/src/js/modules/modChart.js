let modChart = {

    //Chart: require('chart.js'),

    ChartBar: ( labels, data, backgroundColor, borderColor )=> {

        let ctx             = document.getElementById("wpAbStatsChartPage");
        let wpabStatsChart  = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# nombre de vue',
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            aspectRatio: 1,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Page Stats'
                }
            }
        });
    }
}
module.exports = modChart;