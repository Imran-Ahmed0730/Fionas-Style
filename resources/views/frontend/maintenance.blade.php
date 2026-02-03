<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiona's Style | Coming Soon</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 30px 10px;
            color: white;
        }

        .hero-box {
            background: #111;
            border-radius: 25px;
            padding: 60px 35px;
            box-shadow: 0px 15px 60px rgba(255, 204, 0, 0.18);
            text-align: center;
        }

        .brand-title {
            font-size: 3rem;
            font-weight: 700;
            color: #ffcc00;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 1.15rem;
            color: #ccc;
            margin-top: 15px;
            line-height: 1.6;
        }

        .countdown-wrapper {
            margin-top: 45px;
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .time-box {
            width: 95px;
            height: 95px;
            border-radius: 18px;
            background: #ffcc00;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            animation: pulseGlow 1.5s infinite;
            transition: transform 0.3s ease;
        }

        .time-box:hover {
            transform: scale(1.08);
        }

        .time-box h2 {
            font-size: 1.9rem;
            margin: 0;
        }

        .time-box small {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 0 0px rgba(255, 204, 0, 0.4);
            }

            50% {
                box-shadow: 0 0 25px rgba(255, 204, 0, 0.75);
            }

            100% {
                box-shadow: 0 0 0px rgba(255, 204, 0, 0.4);
            }
        }

        @media(max-width: 576px) {
            .brand-title {
                font-size: 2.2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .time-box {
                width: 75px;
                height: 75px;
            }

            .time-box h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-box">

                    <!-- Brand Name -->
                    <h1 class="brand-title">Fiona's Style</h1>

                    <!-- Updated Brand Message -->
                    <p class="subtitle">
                        A premium fashion destination for every age is coming soon. <br>
                        Trendy. Comfortable. Designed for everyday confidence. âœ¨
                    </p>

                    <!-- Countdown Heading -->
                    <h4 class="mt-4 fw-bold text-warning">Launching On</h4>

                    <!-- Countdown Timer -->
                    <div class="countdown-wrapper" id="countdown">
                        <div class="time-box">
                            <h2 id="days">00</h2>
                            <small>Days</small>
                        </div>
                        <div class="time-box">
                            <h2 id="hours">00</h2>
                            <small>Hours</small>
                        </div>
                        <div class="time-box">
                            <h2 id="minutes">00</h2>
                            <small>Minutes</small>
                        </div>
                        <div class="time-box">
                            <h2 id="seconds">00</h2>
                            <small>Seconds</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Countdown Script -->
    <script>
        $(document).ready(function () {

            // Launch Date: 10 February 2026
            const launchDate = new Date("February 10, 2026 00:00:00").getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = launchDate - now;

                if (distance < 0) {
                    $("#countdown").html(
                        "<h3 class='text-success fw-bold mt-4'>ðŸŽ‰ Fionaâ€™s Style is Live Now!</h3>"
                    );
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $("#days").text(days);
                $("#hours").text(hours);
                $("#minutes").text(minutes);
                $("#seconds").text(seconds);
            }

            setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>
</body>

</html>