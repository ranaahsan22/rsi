<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pod extends CI_Controller {

    public function index(){
        $this->load->view('index');
    }

    public function create() {
        header('Content-Type: application/json');
        try {
            log_message('info', 'Creating pod...');
    
            $command = '/var/www/html/rsi/run_microk8s.sh /snap/bin/microk8s kubectl apply -f /home/4mb-pod.yaml 2>&1';
    
            $output = [];
            $return_var = null;
            exec($command, $output, $return_var);
    
            log_message('info', 'Command output: ' . implode("\n", $output));
    
            if ($return_var !== 0) {
                throw new Exception('Command failed: ' . implode("\n", $output));
            }
    
            echo json_encode(['message' => 'Pod created successfully!', 'output' => $output]);
        } catch (Exception $e) {
            log_message('error', 'Error creating pod: ' . $e->getMessage());
    
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while creating the pod.', 'details' => $e->getMessage()]);
        }
    }
    
    public function delete() {
        header('Content-Type: application/json');
        try {
            log_message('info', 'Deleting pod...');
    
            $command = '/var/www/html/rsi/run_microk8s.sh /snap/bin/microk8s kubectl delete pod /home/4mb-pod 2>&1';
    
            $output = [];
            $return_var = null;
            exec($command, $output, $return_var);
    
            log_message('info', 'Command output: ' . implode("\n", $output));
    
            if ($return_var !== 0) {
                throw new Exception('Command failed: ' . implode("\n", $output));
            }
    
            echo json_encode(['message' => 'Pod deleted successfully!', 'output' => $output]);
        } catch (Exception $e) {
            log_message('error', 'Error deleting pod: ' . $e->getMessage());
    
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while deleting the pod.', 'details' => $e->getMessage()]);
        }
    }
    
    
}
