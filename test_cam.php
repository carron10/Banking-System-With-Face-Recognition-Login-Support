<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Face Detection</title>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js" integrity="sha384-2huaZvOR9iDzHqslqwpR87isEmrfxqyWOF7hr7BY6KG0+hVKLoEXMPUJw3ynWuhO" crossorigin="anonymous"></script>

    <style>
        #container {
            position: relative;
            width: 250px;
            height: 200px;
        }

        #video {
            width: 100%;
            height: 100%;
            border-radius: 5%;
        }

        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            border-radius: 5%;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div id="container">
        <video id="video" autoplay></video>
        <canvas id="canvas"></canvas>
    </div>

    <script>
        const socket = io("127.0.0.1:5000");

        // Get access to the webcam
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                const video = document.getElementById('video');
                video.srcObject = stream;

                // Send frames from webcam to server
                const captureFrames = () => {
                    const canvas = document.getElementById('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL('image/jpeg');
                    socket.emit('image', imageData);
                };

                setInterval(captureFrames, 1000); // Adjust interval as needed
            })
            .catch(err => {
                console.error('Error accessing webcam:', err);
            });

        // Receive processed image data from server
        socket.on('faceLocation', faceLocation => {
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (faceLocation) {
                const [top, right, bottom, left] = faceLocation[0];
                console.log([top, right, bottom, left]);
                ctx.strokeStyle = 'red';
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.rect(left, top, right - left, bottom - top);
                ctx.stroke();
            }
        });
    </script>
</body>

</html>
