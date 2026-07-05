const data   = window.chartProjDep;
    const labels = data.map(d => d.department);
    const counts = data.map(d => d.count);

    new Chart(document.getElementById('barChartPD'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Numero di Projects',
                data: counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Progetti per Department',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });