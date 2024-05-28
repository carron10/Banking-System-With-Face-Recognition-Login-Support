<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Face Detection</title>
    <style>
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* #canvas {
            display: block;
            width: 100%;
            max-width: 400px;
        } */

        #video,
        #canvas {
            border-radius: 5%;
            width: 250px;
            /* Adjust the size as needed */
            height: 200px;
            /* Adjust the size as needed */
            /* overflow: hidden; */
        }
    </style>
</head>

<body>
    <div id="container">
        <h1>Real-Time Face Detection</h1>
        <video id="video" style="display: none;" autoplay></video>
        <canvas id="canvas"></canvas>
    </div>

    <script>
        // Get access to the webcam
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                const video = document.getElementById('video');
                video.srcObject = stream;
                // Call the detectFace function repeatedly
                detectFace()
                setInterval(detectFace, 1000); // Adjust interval as needed
            })
            .catch(err => {
                console.error('Error accessing webcam:', err);
            });
            // fetch('http://127.0.0.1:5000/test',{'method':"POST"}).then(data => {
            //     alert(data)
            // });
        function detectFace() {
           
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            // Set canvas dimensions to match video stream
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame onto canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Get data URL of captured image
            const imageData = canvas.toDataURL('image/jpeg');

            // Send image data to server for processing
            fetch('http://127.0.0.1:5000/api/face_detection', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pic: imageData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Clear the canvas
                    // context.clearRect(0, 0, canvas.width, canvas.height);

                    if (data.faceLocation) {
                        // Draw face location rectangle on canvas
                        const [top, right, bottom, left] = data.faceLocation[0];
                        context.strokeStyle = 'red';
                        context.lineWidth = 3;
                        context.beginPath();
                        context.rect(left, top, right - left, bottom - top);
                        context.stroke();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>