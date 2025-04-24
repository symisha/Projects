<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
  </head>
  <body>
    <video id="qr-video" width="640" height="480" style="border: 1px solid black;"></video>
    <script>
      const video = document.getElementById('qr-video');
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');

      // Access the camera
      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
          video.srcObject = stream;
          video.play();
          video.addEventListener('loadedmetadata', function () {
            scanQRCode(); // Start scanning after metadata is loaded
          });
        })
        .catch(function(error) {
          console.error('Error accessing camera: ', error);
        });

      // Function to scan QR Code
      function scanQRCode() {
        if (video.videoWidth > 0 && video.videoHeight > 0) {
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          context.drawImage(video, 0, 0, canvas.width, canvas.height);

          const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, canvas.width, canvas.height);

          if (code) {
            // Use JavaScript to handle redirection
            window.location.href = code.data;  // Redirect to the QR code URL
            video.srcObject.getTracks().forEach(track => track.stop()); // Stop video stream after scanning
          }
        }
        requestAnimationFrame(scanQRCode); // Keep scanning
      }
    </script>
  </body>
</html>
