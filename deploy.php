<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'deploy') {
        // Command to deploy the pod
        $command = 'echo "rsi@123" | sudo -S /usr/local/bin/deploy-pod.sh 2>&1';
        $output = shell_exec($command);

        // Send JSON response to start the countdown
        echo json_encode(['status' => 'deleted', 'output' => $output]);
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Command to delete the pod
        $command = 'echo "rsi@123" | sudo -S /usr/local/bin/delete-pod.sh 2>&1';
        $output = shell_exec($command);

        // Send JSON response indicating pod deletion
        echo json_encode(['status' => 'deleted', 'output' => $output]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deploy Pod</title>
    <script>
        function startCountdown(seconds) {
            let countdownElement = document.getElementById('countdown-timer');
            let timeRemaining = seconds;

            let countdownInterval = setInterval(() => {
                let minutes = Math.floor(timeRemaining / 60);
                let seconds = timeRemaining % 60;
                countdownElement.innerText = `Time remaining: ${minutes}m ${seconds}s`;

                if (timeRemaining <= 0) {
                    clearInterval(countdownInterval);
                    deletePod(); // Automatically delete the pod after countdown
                }

                timeRemaining--;
            }, 1000);
        }

        function deletePod() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=delete'
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('output').innerText = 'Pod deleted.\n' + data.output;
            });
        }

        document.getElementById('deploy-button').addEventListener('click', function() {
            // Disable button to prevent multiple clicks
            this.disabled = true;

            // Start deployment via AJAX
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=deploy'
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('output').innerText = 'Pod deployed successfully.\n' + data.output;
                startCountdown(120); // Start 2-minute countdown
            });
        });
    </script>
</head>
<body>
    <h1>Deploy Pod</h1>
    <button id="deploy-button">Deploy Pod</button>
    <p id="countdown-timer"></p>
    <pre id="output"></pre>
</body>
</html>
