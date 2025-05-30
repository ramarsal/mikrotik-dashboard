import './bootstrap';

import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('bandwidthChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const labels = [];
    const dataRx = [];
    const dataTx = [];

    const bandwidthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Rx (Download) kbps',
                    data: dataRx,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                },
                {
                    label: 'Tx (Upload) kbps',
                    data: dataTx,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function fetchTraffic() {
        fetch('/mikrotik-traffic')
            .then(response => response.json())
            .then(data => {
                const time = new Date().toLocaleTimeString();
                const rx = parseInt(data['rx-bits-per-second']) / 1024;
                const tx = parseInt(data['tx-bits-per-second']) / 1024;

                if (labels.length > 10) {
                    labels.shift();
                    dataRx.shift();
                    dataTx.shift();
                }

                labels.push(time);
                dataRx.push(rx.toFixed(2));
                dataTx.push(tx.toFixed(2));

                bandwidthChart.update();
            })
            .catch(err => console.error('Traffic fetch error:', err));
    }

    setInterval(fetchTraffic, 3000); // every 3 seconds
});
