<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <style>
        :root {
            --brand-brown: #8b6844;
            --brand-green: #4CAF50;
            --bg-light: #f7f7f7;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-light);
            font-family: "Inter", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .success-box {
            position: relative;
            z-index: 1;
            background: #fff;
            padding: 40px 50px;
            border-radius: 16px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.08);
            animation: fadeIn 0.7s ease-in-out;
        }

        .success-icon {
            font-size: 60px;
            color: var(--brand-green);
            margin-bottom: 10px;
            animation: pop 0.4s ease-out;
        }

        h1 {
            margin: 0 0 10px;
            color: var(--brand-green);
            font-size: 28px;
            font-weight: 700;
        }

        p {
            margin: 0 0 25px;
            color: #444;
            font-size: 16px;
        }

        button {
            background: var(--brand-green);
            color: #fff;
            padding: 12px 28px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #91da88ff;
        }

                /* Page wrapper */
        .success-page {
          position: relative;
          min-height: 100vh;
          background: #f7f9fc;
          display: flex;
          align-items: center;
          justify-content: center;
          overflow: hidden;
        }

        /* Blob container */
        .bg-blobs {
          position: absolute;
          inset: 0;
          z-index: 0;
        }

        /* Base blob */
        .blob {
          position: absolute;
          width: 320px;
          height: 320px;
          border-radius: 50%;
          filter: blur(80px);
          opacity: 0.5;
          animation: float 12s ease-in-out infinite;
        }

        /* Blob colors */
        .blob-green {
          background: #4caf50;
          top: 10%;
          left: 15%;
        }

        .blob-yellow {
          background: #dac612;
          bottom: 15%;
          right: 10%;
          animation-delay: 4s;
        }

        /* Floating animation */
        @keyframes float {
          0%, 100% {
            transform: translateY(0) translateX(0);
          }
          50% {
            transform: translateY(-40px) translateX(20px);
          }
        }


        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pop {
            0% { transform: scale(0.3); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body>

<div class="bg-blobs">
  <span class="blob blob-green"></span>
  <span class="blob blob-yellow"></span>
</div>


    <div class="success-box">
        <div class="success-icon">✔</div>
        <h1>Payment Successful!</h1>
        <p>Salamat! Masaya kaming maging kasama mo sa paglalakbay</p>
        

        <button onclick="window.location.href='home.php';">Back to Home</button>
    </div>

</body>
</html>
