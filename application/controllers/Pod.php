<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pod extends CI_Controller {

    public function index(){
        $this->load->view('index');
    }

    public function create() {
        header('Content-Type: application/json');
        try {
            // Add your logic to create a pod here
            log_message('info', 'Creating pod...');
    
            // Set the HOME environment variable to a valid directory within /home
            putenv('HOME=/home/your-username'); // Replace 'your-username' with the actual username
    
            // Use the full path to microk8s and redirect stderr to stdout
            $command = '/snap/bin/microk8s kubectl apply -f 4mb-pod.yaml 2>&1';
    
            // Execute the command and capture the output and return status
            $output = [];
            $return_var = null;
            exec($command, $output, $return_var);
    
            // Log the command output
            log_message('info', 'Command output: ' . implode("\n", $output));
    
            if ($return_var !== 0) {
                // Command failed
                throw new Exception('Command failed: ' . implode("\n", $output));
            }
    
            // Command succeeded
            echo json_encode(['message' => 'Pod created successfully!', 'output' => $output]);
        } catch (Exception $e) {
            // Log the exception message
            log_message('error', 'Error creating pod: ' . $e->getMessage());
    
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while creating the pod.', 'details' => $e->getMessage()]);
        }
    }
    

    public function delete() {
        header('Content-Type: application/json');
        try {
            // Add your logic to delete a pod here
            log_message('info', 'Deleting pod...');
    
            // Set the HOME environment variable to a valid directory within /home
            putenv('HOME=/home/your-username'); // Replace 'your-username' with the actual username
    
            // Use the full path to microk8s and redirect stderr to stdout
            $command = '/snap/bin/microk8s kubectl delete pod 4mb-pod 2>&1';
    
            // Execute the command and capture the output and return status
            $output = [];
            $return_var = null;
            exec($command, $output, $return_var);
    
            // Log the command output
            log_message('info', 'Command output: ' . implode("\n", $output));
    
            if ($return_var !== 0) {
                // Command failed
                throw new Exception('Command failed: ' . implode("\n", $output));
            }
    
            // Command succeeded
            echo json_encode(['message' => 'Pod deleted successfully!', 'output' => $output]);
        } catch (Exception $e) {
            // Log the exception message
            log_message('error', 'Error deleting pod: ' . $e->getMessage());
    
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while deleting the pod.', 'details' => $e->getMessage()]);
        }
    }
    
    
}
