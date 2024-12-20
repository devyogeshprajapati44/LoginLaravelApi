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
                        <label for="device_name" class="form-label">Device Name</label>
                        <input type="text" class="form-control" name="device_name" id="device_name" required>
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
                        <label for="device_name" class="form-label">Device Name</label>
                        <input type="text" class="form-control" name="device_name" id="device_name" required>
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
    // Handle Add Device
    $('#addDeviceForm').submit(function(event) {
        event.preventDefault();
        var device_name = $('#device_name').val();

        $.ajax({
            url: 'http://127.0.0.1:8000/api/devices_name',
            method: 'POST',
            data: JSON.stringify({ name: device_name }),  // Sending name as part of the request body
            contentType: 'application/json',
            success: function(response) {
                alert('Device added successfully!');
                $('#device_name').val(''); // Clear the input field
            },
            error: function(error) {
                alert('Failed to add device.');
            }
        });
    });

    });

</script>
</body>
</html>
