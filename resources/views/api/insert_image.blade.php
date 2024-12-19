<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Images</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .image-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Swipe Images</h1>
        <div id="imageGrid" class="row"></div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch images from API
        async function fetchImages() {
            const response = await fetch('http://127.0.0.1:8000/api/insert_image'); // Replace with your actual API URL
            const data = await response.json();
            
            if (data.status_code === 200) {
                const images = data.data;
                const imageGrid = document.getElementById('imageGrid');

                // Render images in Bootstrap grid
                images.forEach(image => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 image-card';
                    col.innerHTML = `
                        <div class="card shadow-sm">
                            <img src="${image.img_url}" alt="Image ${image.id}" class="card-img-top">
                            <div class="card-body text-center">
                                <p class="card-text">Image ID: ${image.id}</p>
                                <p class="card-text"><small class="text-muted">${new Date(image.created_at).toLocaleDateString()}</small></p>
                            </div>
                        </div>
                    `;
                    imageGrid.appendChild(col);
                });
            } else {
                document.getElementById('imageGrid').innerHTML = `<p class="text-danger text-center">Failed to load images.</p>`;
            }
        }

        // Call the function to fetch and display images
        fetchImages();
    </script>
</body>
</html>
