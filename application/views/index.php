<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pod Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #343a40;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        .alert {
            display: none;
            margin-top: 20px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .modal-footer .btn {
            min-width: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h2>Pod Management</h2>
            </div>
            <div class="card-body">
                <div id="responseMessage" class="alert" role="alert"></div>
                <button id="createPod" class="btn btn-success">Create Pod</button>
                <button id="deletePod" class="btn btn-danger">Delete Pod</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modalConfirmButton">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function displayMessage(message, type) {
            var responseMessage = $('#responseMessage');
            responseMessage.removeClass('alert-success alert-danger alert-warning alert-info');
            responseMessage.addClass('alert-' + type);
            responseMessage.html(message);
            responseMessage.show();
        }

        function createPod() {
            $.ajax({
                url: 'index.php/pod/create',
                type: 'POST',
                data: {
                    'csrf_test_name': '<?= $this->security->get_csrf_hash(); ?>' // Include CSRF token
                },
                success: function(data) {
                    try {
                        var response = JSON.stringify(data);
                        if (response.error) {
                            displayMessage(response.details, 'danger');
                        } else if (response.message) {
                            displayMessage(response.message, 'success');
                        } else {
                            displayMessage('Unexpected response from server.', 'warning');
                        }
                    } catch (e) {
                        displayMessage('An error occurred while parsing the response.', 'danger');
                    }
                },
                error: function(data) {
                    var response = JSON.stringify(data);
                    displayMessage('An error occurred while creating the pod: '+response, 'danger');
                }
            });
        }

        function deletePod() {
            $.ajax({
                url: 'index.php/pod/delete',
                type: 'POST', // Use POST for CSRF protection
                data: {
                    'csrf_test_name': '<?= $this->security->get_csrf_hash(); ?>' // Include CSRF token
                },
                success: function(data) {
                    try {
                        var response = JSON.parse(data);

                        if (response.error) {
                            displayMessage(response.details, 'danger');
                        } else if (response.message) {
                            displayMessage(response.message, 'success');
                        } else {
                            displayMessage('Unexpected response from server.', 'warning');
                        }
                    } catch (e) {
                        displayMessage('An error occurred while parsing the response.', 'danger');
                    }
                },
                error: function() {
                    var response = JSON.stringify(data);
                    displayMessage('An error occurred while creating the pod: '+response, 'danger');
                }
            });
        }


        function showModal(action) {
            var modalBody = $('#modalBody');
            var modalConfirmButton = $('#modalConfirmButton');
            if (action === 'create') {
                modalBody.html('Are you sure you want to create a new pod?');
                modalConfirmButton.off('click').on('click', function() {
                    createPod();
                    $('#confirmationModal').modal('hide');
                });
            } else if (action === 'delete') {
                modalBody.html('Are you sure you want to delete the pod?');
                modalConfirmButton.off('click').on('click', function() {
                    deletePod();
                    $('#confirmationModal').modal('hide');
                });
            }
            $('#confirmationModal').modal('show');
        }

        $('#createPod').click(function() {
            showModal('create');
        });

        $('#deletePod').click(function() {
            showModal('delete');
        });
    </script>
</body>
</html>
