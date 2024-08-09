<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Command to execute the wrapper script
    $command = 'sudo /usr/local/bin/deploy-pod.sh';

    // Execute the command and capture the output and return status
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);

    // Display the output and return status
    echo "<pre>";
    echo "Command executed: $command\n";
    echo "Return status: $return_var\n";
    echo "Output:\n";
    echo implode("\n", $output);
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deploy Pod</title>
</head>
<body>
    <form method="post">
        <button type="submit">Deploy Pod</button>
    </form>
</body>
</html>
