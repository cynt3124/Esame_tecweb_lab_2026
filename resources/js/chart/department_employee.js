const bcDE = document.getElementById('barChartDE');
const lcDE = document.getElementById('lineChartDE');
const dcDE = document.getElementById('doughnutChartDE');

let data   = window.chartDepEmp;
let labels = data.map(d => d.department);
let counts = data.map(d => d.count);

new Chart(bcDE, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Numero di Employee',
            data: counts,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1,
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        },
        plugins: {
            legend: { display: true },
            title: {
                display: true,
                text: 'Numero di Employee per Department'
            }
        },
    }
    
});


data = window.chartGrowth;
labels   = data.labels;

new Chart(lcDE, {
    type: 'line',
    data: { 
        labels, 
        datasets: data.datasets.map((dept, i) => ({
            label:           dept.department,
            data:            dept.data,
            fill:            false,
            tension:         0.3,
            pointStyle:      ['circle','triangle','rect','rectRot','star'][i],
            pointRadius:     5,
            pointHoverRadius:10,
        }))
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: { display: true, text: 'Data di creazione' },
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Numero dipendenti (cumulativo)' },
                ticks: {
                    precision: 0,
                }
            }
        },
        plugins: {
            legend: { display: true },
            title: {
                display: true,
                text: 'Top 5 Dipartimenti – Crescita cumulativa dipendenti',
            },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} dipendenti`,
                }
            }
        },
    }
});

data = window.chartGender;
labels = data.map(d => d.label);
counts = data.map(d => d.count);

// colore assegnato in base al label
    const colorMap = {
        'Maschio':         { bg: 'rgba(75, 192, 192, 0.2)',  border: 'rgb(75, 192, 192)' },
        'Femmina':         { bg: 'rgba(255, 99, 132, 0.2)',  border: 'rgb(255, 99, 132)'},
        'Non specificato': { bg: 'rgba(201, 203, 207, 0.2)', border: 'rgb(201, 203, 207)' },
    };

    const backgroundColors = labels.map(l => colorMap[l]?.bg );
    const borderColors     = labels.map(l => colorMap[l]?.border);

new Chart(dcDE, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: 'Numero di dipendenti',
            data: counts,
            backgroundColor: backgroundColors,
            borderColor: borderColors,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: {
                display: true,
                text: 'Numero di Employee per Genere',
            },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        // legge la percentuale pre-calcolata dal controller
                        const percentage = window.chartGender[ctx.dataIndex].percentage;
                        return `${ctx.label}: ${ctx.parsed} (${percentage}%)`;
                    }
                }
            }
        }
    }
});