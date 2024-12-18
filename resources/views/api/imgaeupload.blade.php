<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zendo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for the loading spinner */
        .spinner-border {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Zendo Upload Image</h3>
            </div>
            <div class="card-body">
                <form id="imageUploadForm">
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload</button>
                </form>
                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center mt-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- Result Area -->
                <div id="uploadResult" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios for API requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const form = document.getElementById('imageUploadForm');
        const uploadResult = document.getElementById('uploadResult');
        const loadingSpinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Clear previous results
            uploadResult.innerHTML = '';
            loadingSpinner.style.display = 'block'; // Show loading spinner

            // Get the file input
            const formData = new FormData();
            const imageFile = document.getElementById('image').files[0];

            // Frontend file validation
            if (!imageFile) {
                uploadResult.innerHTML = '<div class="alert alert-danger">Please choose an image file.</div>';
                loadingSpinner.style.display = 'none';
                return;
            }

            // Check file type (only images)
            if (!imageFile.type.match('image.*')) {
                uploadResult.innerHTML = '<div class="alert alert-danger">Please upload a valid image (JPG, JPEG, PNG).</div>';
                loadingSpinner.style.display = 'none';
                return;
            }

            // Check file size (max 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (imageFile.size > maxSize) {
                uploadResult.innerHTML = '<div class="alert alert-danger">File size must be less than 2MB.</div>';
                loadingSpinner.style.display = 'none';
                return;
            }

            formData.append('image', imageFile);

            try {
                // Make the API call
                const response = await axios.post('http://127.0.0.1:8000/api/upload-image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                    }
                });

                // Display the success message and image
                const data = response.data.data;
                uploadResult.innerHTML = `
                    <div class="alert alert-success">Image uploaded successfully!</div>
                    <img src="${data.file_path}" class="img-fluid mt-3" alt="Uploaded Image">
                `;
            } catch (error) {
                // Handle errors
                const errorMessage = error.response?.data?.message || 'An error occurred';
                uploadResult.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
            } finally {
                // Hide the loading spinner
                loadingSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>
