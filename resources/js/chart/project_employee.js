const pcPE = document.getElementById('pieChartPE');
const lcPH = document.getElementById('lineChartPH');

let data   = window.chartProjEmp;
let labels = data.map(d => d.label)
let counts = data.map(d => d.value);

new Chart(pcPE, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Numero di Progetti',
            data: counts,
            borderWidth: 2,
        }]
    },
    plugins: [ChartDataLabels],
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
            title: {
                display: true,
                text: 'Numero di Employee per Numero di Projects',
            },
            datalabels: {
                formatter: (value, ctx) => {
                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                    return ((value / total) * 100).toFixed(1) + '%';
                },
                color: '#fff',
                font: { weight: 'bold' }
            }
        },
    }
});

data = window.chartProjHours;

new Chart(lcPH, {
    type: 'line',
    data: {
        labels: data.labels,
        datasets: data.datasets.map((ds, i) => ({
            label:            ds.label,
            data:             ds.data,
            pointStyle:       ['circle', 'triangle', 'rect', 'rectRot', 'star'][i],
            pointRadius:      5,
            pointHoverRadius: 10,
        }))
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: {
                display: true,
                text: `${data.employee} – Ore settimanali: ${data.total_hours}h`,
            },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}h`,
                }
            }
        },
        scales: {
            x: {
                title: { display: true, text: 'Data inserimento nel progetto' },
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Ore' },
                ticks: { callback: val => `${val}h` },
            }
        },
    }
});