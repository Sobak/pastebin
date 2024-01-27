<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ page_title('HTTP ' . $exception->getStatusCode()) }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300,500&subset=latin-ext">
    <style>
        html, body {
            background-color: #181818;
            color: #ccc;
            font-family: "Raleway", sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .error-container {
            text-align: center;
            height: 100%;
        }
        @media (max-width: 480px) {
            .error-container {
                position: relative;
                top: 50%;
                height: initial;
                transform: translateY(-50%);
            }
        }
        .error-container h1 {
            color: #f45145;
            font-size: 80px;
            font-weight: 300;
            margin: 0;
        }
        .error-container h1 .http {
            color: #ccc;
            font-size: 0.8em;
        }
        @media (min-width: 480px) {
            .error-container h1 {
                position: relative;
                top: 50%;
                transform: translateY(-50%);
            }
        }
        @media (min-width: 768px) {
            .error-container h1 {
                font-size: 165px;
            }
        }
        a {
            color: #f45145;
            text-decoration: none;
        }
        a:hover {
            color: #f77e75;
        }
        .return {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
            font-size: 1.1em;
            margin: 0;
            text-transform: lowercase;
        }
        @media (min-width: 480px) {
            .return {
                position: absolute;
                width: 100%;
                bottom: 30px;
            }
        }
    </style>
</head>
<body>

<div class="error-container">
    <h1><span class="http">HTTP</span> {{ $exception->getStatusCode() }}</h1>
    <p class="return">Try the <a href="/">main page</a></p>
</div>

</body>
</html>
