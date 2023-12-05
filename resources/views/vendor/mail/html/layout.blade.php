<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">

        <style type="text/css">
            body {
                margin: 0;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: "Poppins", sans-serif;
            }

            p, span, div, li {
                font-family: "Roboto", sans-serif;
                font-size: 1rem;
            }

            .main-color {
                color: #0a3066;
            }

            .preheader {
                display: none;
                visibility: hidden;
                opacity:0;
                color:transparent;
                height:0;
                width:0;
                margin-right: 20vw;
            }

            .main {
                padding: 2rem;
                background-color: #efefef;
            }

            @media (min-width: 768px) {
                .main {
                    padding: 3rem !important;
                }
            }

            .main .card {
                margin: 0 auto;
                background-color: white;
                border-radius: 3px;
                width: 100%;
                max-width: 800px;
            }

            .main .footer {
                margin: 0 auto;
                margin-top: 5vh;
                width: 100%;
            }
        </style>

        @stack('styles')

    </head>
    <body>
        <div class="preheader">
            {{ $preheader ?? '' }}
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;
            &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847; &#847;

        </div>

        <div class="main">
            <div class="card">
                {{ $header ?? '' }}

                {{ $slot }}
            </div>

            <div class="footer">
                {{ $footer ?? '' }}
            </div>
        </div>
    </body>
</html>
