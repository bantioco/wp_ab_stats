let modChart = {

    //Chart: require('chart.js'),

    ChartBar: ( labels, data, backgroundColor, borderColor, elementId, chartTitle="Post stats" )=> {

        let ctx             = document.getElementById( elementId );

        Chart.defaults.global.defaultFontFamily = 'Dosis';
        Chart.defaults.global.defaultFontSize = 11;

        let wpabStatsChart  = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de vue',
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
                    text: chartTitle,
                    fontSize: 13
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'black'
                    }
                }
            }
        });
    }
}
module.exports = modChart;