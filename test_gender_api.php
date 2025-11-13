<?php
session_start();

// Simulate admin session for testing
$_SESSION['admin'] = true;
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'main_admin';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gender Distribution API Test</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1a1a1a; color: white; }
        .container { max-width: 800px; margin: 0 auto; }
        .chart-container { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; margin: 20px 0; }
        .api-response { background: #333; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: #ff6b6b; }
        .success { color: #51cf66; }
        .warning { color: #ffd700; }
        button { background: #e76f2c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #f3d35c; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gender Distribution API Test</h1>
        
        <div>
            <button onclick="testAPI()">Test Gender Distribution API</button>
            <button onclick="createTestChart()">Create Test Chart</button>
            <button onclick="checkSession()">Check Session</button>
        </div>
        
        <div id="apiResponse" class="api-response">
            <h3>API Response will appear here...</h3>
        </div>
        
        <div class="chart-container">
            <h3>Gender Distribution Chart</h3>
            <canvas id="genderChart" width="400" height="400"></canvas>
        </div>
        
        <div id="debugInfo" class="api-response">
            <h3>Debug Information</h3>
            <p><strong>Session Status:</strong> <span id="sessionStatus">Checking...</span></p>
            <p><strong>Current URL:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></p>
            <p><strong>Admin ID:</strong> <?php echo $_SESSION['admin_id'] ?? 'Not set'; ?></p>
            <p><strong>Admin Role:</strong> <?php echo $_SESSION['admin_role'] ?? 'Not set'; ?></p>
        </div>
    </div>

    <script>
        let genderChart;
        
        async function testAPI() {
            const responseDiv = document.getElementById('apiResponse');
            responseDiv.innerHTML = '<h3>Testing API...</h3>';
            
            try {
                const response = await fetch('Action/gender_distribution_api.php', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                responseDiv.innerHTML = `
                    <h3 class="success">API Response (Status: ${response.status})</h3>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                `;
                
                if (data.success && data.data) {
                    createGenderChart(data.data);
                } else {
                    console.warn('API returned unsuccessful response:', data);
                }
                
            } catch (error) {
                responseDiv.innerHTML = `
                    <h3 class="error">API Error</h3>
                    <p>Error: ${error.message}</p>
                `;
                console.error('API Error:', error);
            }
        }
        
        function createGenderChart(data) {
            const ctx = document.getElementById('genderChart').getContext('2d');
            
            if (genderChart) {
                genderChart.destroy();
            }
            
            const labels = data.map(item => item.gender);
            const counts = data.map(item => item.count);
            const colors = data.map(item => item.color);
            
            genderChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#fff',
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#51cf66',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const gender = context.label;
                                    const count = context.parsed;
                                    const genderIcon = gender === 'Male' ? '♂' : gender === 'Female' ? '♀' : '⚧';
                                    return `${gender}: ${count} ${genderIcon}`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
            
            console.log('Chart created successfully with data:', data);
        }
        
        function createTestChart() {
            const testData = [
                {gender: 'Male', count: 3, color: '#36A2EB'},
                {gender: 'Female', count: 4, color: '#FF6384'},
                {gender: 'Other', count: 1, color: '#9966FF'}
            ];
            
            createGenderChart(testData);
            
            document.getElementById('apiResponse').innerHTML = `
                <h3 class="warning">Test Chart Created</h3>
                <p>Using dummy data: Male(3), Female(4), Other(1)</p>
            `;
        }
        
        function checkSession() {
            const sessionStatus = document.getElementById('sessionStatus');
            sessionStatus.innerHTML = '<?php echo isset($_SESSION["admin"]) ? "Admin session active" : "No admin session"; ?>';
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkSession();
            // Automatically test the API on page load
            setTimeout(testAPI, 1000);
        });
    </script>
</body>
</html>
