<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Device Management</h2>
        
        <!-- Form to Add Device -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add a New Device</h5>
                <form id="addDeviceForm">
                    <div class="mb-3">
                        <label for="devise_name" class="form-label">Device Name</label>
                        <input type="text" class="form-control" name="devise_name" id="devise_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Device</button>
                </form>
            </div>
        </div>

        <!-- Form to Check Device Count -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Check Device Count</h5>
                <form id="checkDeviceCountForm">
                    <div class="mb-3">
                        <label for="devise_name" class="form-label">Device Name</label>
                        <input type="text" class="form-control" name="devise_name" id="devise_name" required>
                    </div>
                    <button type="submit" class="btn btn-success">Check Count</button>
                </form>
            </div>
        </div>

        <!-- Display Device Count -->
        <div class="mt-4">
            <h5 id="deviceCountResult" class="text-success"></h5>
        </div>
    </div>

    <script>
       $(document).ready(function() {
    // Add a new device
    $('#addDeviceForm').submit(function(event) {
        event.preventDefault();
        var devise_name = $('#devise_name').val();

        $.ajax({
            url: 'http://127.0.0.1:8000/api/devices_name',
            method: 'POST',
            data: { devise_name: devise_name },
            success: function(response) {
                alert(response.message);
                $('#devise_name').val(''); // Clear the input field
            },
            error: function(error) {
                alert('Error adding device.');
            }
        });
    });

    // Check device count
  // Check device count
$('#checkDeviceCountForm').submit(function(event) {
    event.preventDefault();
    var devise_name = $('#devise_name').val();

    $.ajax({
        url: 'http://127.0.0.1:8000/api/get_count_devise_name',
        method: 'POST',
        data: { name: devise_name },
        success: function(response) {
            if (response.success) {
                $('#deviceCountResult').text('Number of devices with the name "' + devise_name + '": ' + response.data.count);
            } else {
                $('#deviceCountResult').text(response.message || 'An error occurred.');
            }
            $('#devise_name').val(''); // Clear the input field
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            alert('Error retrieving device count: ' + xhr.responseText);
        }
    });
});

});    </script>
</body>
</html>
