
<?php include 'success_counter.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backstage Renaissance</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #000;
            font-family: 'Courier New', monospace;
        }

        .logo_img{
            position: absolute center;
            bottom: 20;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        .slideshow-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .slideshow-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slideshow-image.active {
            opacity: 1;
        }

        .overlay {
            background: black;
            opacity: 0.8;
            z-index: 1;
        }

        .overlay-text {
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-top: 6%;
            font-family: 'Courier New', monospace;
        }

        /* @media only screen and (max-width: 600px) {
            .overlay-text {
            color: black;
            font-size: 80px;
            font-weight: bold;
            text-align: center;
            font-family: 'Courier New', monospace;
            }
        } */

    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
    
    <div class="container" >
        <img class="logo_img" src="imgs/head.png" alt="" style="opacity: 0.8;">
    </div>

    <div class="slideshow-container">
        <img class="slideshow-image active" src="imgs/1.webp" alt="Image 1">
        <img class="slideshow-image" src="imgs/2.webp" alt="Image 2">
        <img class="slideshow-image" src="imgs/3.webp" alt="Image 3">
        <img class="slideshow-image" src="imgs/4.webp" alt="Image 4">
        <img class="slideshow-image" src="imgs/5.webp" alt="Image 5">
        <img class="slideshow-image" src="imgs/6.webp" alt="Image 6">
        <img class="slideshow-image" src="imgs/7.webp" alt="Image 7">
        <img class="slideshow-image" src="imgs/8.webp" alt="Image 8">
        <img class="slideshow-image" src="imgs/9.webp" alt="Image 9">
    </div>

        <!-- <br><br><br><br>
        <br><br><br><br>
        <br><br><br><br>
        <br><br> -->

        <div id="phone-div" style="display: none;">
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br>
        </div>

        <div class="overlay" >
            <div class="overlay-text" style="display: flex; ">
            <br><br>
                <div class="container" style="display: flex; "><h1>Success!</h1></div>
            </div>
            <br>
            <div class="container">
                <h4 style="color: white; font-family: 'Courier New', monospace;">We will contact you on your mail if you are eligible for a backstage pass.</h2>
            </div>
            <br>
        </div>

    

    <script>
        const images = document.querySelectorAll('.slideshow-image');
        let currentImageIndex = 0;

        function updateSlideshow() {
            images.forEach((image, index) => {
                if (index === currentImageIndex) {
                    image.classList.add('active');
                } else {
                    image.classList.remove('active');
                }
            });
        }

        setInterval(() => {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateSlideshow();
        }, 2000);

        var isMobile = /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent);

        if (isMobile) {
            document.getElementById("phone-div").style.display = "block";
        }

    </script>
</body>
</html>
