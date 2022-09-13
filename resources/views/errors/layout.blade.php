<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="format-detection" content="telephone=no">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name='robots' content='noindex,nofollow,noarchive,nosnippet,notranslate,noimageindex' />

        <meta name="ddownloadcom" content="267121">
        <meta name="rapidgator" content="17dcfb7155c3f6daa92e077110b9b06fec3d4f37"/>

        <link rel="icon" type="image/png" sizes="128x128" href="storage/icons/favicon-128x128.png">
        <link rel="icon" type="image/png" sizes="96x96" href="storage/icons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="32x32" href="storage/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="storage/icons/favicon-16x16.png">
        <link rel="icon" type="image/ico" href="storage/icons/favicon.ico">
        <title>@yield('title')</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 36px;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height text-center">
            <div class="content">           
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(storage_path('app/public/icons/favicon-128x128.png'))) ?>" style="margin-bottom: 30px" />

                <div class="title">
                    @yield('message')
                </div>

                <div style="margin-top: 50px">
                    @yield('sub')
                </div>
            </div>
        </div>
    </body>
</html>
