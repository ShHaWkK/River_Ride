<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupation des Hôtels</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
        }
        #occupationChart {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
        }
        div {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div style="width:80%; margin: auto;">
    <canvas id="occupationChart"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch_occupation_data.php')
        .then(response => response.json())
        .then(data => {
            const dates = [...new Set(data.map(item => item.calendar_date))];
            const logements = [...new Set(data.map(item => item.logement_nom))];
            const datasets = logements.map(logement => {
                return {
                    label: logement,
                    data: dates.map(date => {
                        const entry = data.find(item => item.logement_nom === logement && item.calendar_date === date);
                        return entry ? (entry.reservations / entry.capacite) * 100 : 0;
                    }),
                    backgroundColor: '#' + Math.floor(Math.random()*16777215).toString(16) // Random color for each logement
                };
            });
            
            const ctx = document.getElementById('occupationChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Changed to bar type
                data: {
                    labels: dates,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Dates'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 100, // as we're dealing with percentage
                            title: {
                                display: true,
                                text: 'Taux d\'occupation (%)'
                            }
                        }
                    }
                }
            });
        });
});
</script>

</body>
</html>
