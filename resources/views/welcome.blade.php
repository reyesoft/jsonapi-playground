<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JsonApi Playground</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #eee;
                color: #544;
                font-family: 'Raleway', sans-serif;
                font-weight: 800;
                height: 100vh;
                margin: 0;
            }

            .title {
                font-weight: 500;
                font-size: 5em;
            }

            .content {
                padding: 2em;
            }

            li {
                padding: 0.5em 0;
                list-style: none;
            }
            .uris {
            }
            .uris span {
                background: #888;
                color: #fff;
                font-size: 0.8em;
                font-weight: bold;
                padding: 0.3em;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{env('APP_NAME')}}
                </div>

                <h2>More info</h2>
                <ul>
                    <li><a href="http://jsonapi.org">JSON API Specification</a></li>
                    <li><a href="https://github.com/reyesoft/ts-angular-jsonapi">Client library for AngularJS in Typescript</a></li>
                </ul>

                <h2>Data</h2>
                <ul class="uris">
                    <li><span>GET</span> <a href="/v2/authors">/v2/authors</a></li>
                    <li><span>GET</span> <a href="/v2/authors/1">/v2/authors/1</a></li>
                    <li><span>GET</span> <a href="/v2/authors/1/books">/v2/authors/1/books</a></li>
                    <li><span>GET</span> <a href="/v2/authors/?page[number]=1&page[size]=3&filter[name]=S&include=books,photos">/v2/authors/?page[number]=1&page[size]=3&filter[name]=S&include=books,photos</a></li>
                </ul>
                <p>More resources?
                    <a href="/v2/books">books</a>
                    <a href="/v2/series">series</a>
                    <a href="/v2/books">books</a>
                    <a href="/v2/chapters">chapters</a>
                    <a href="/v2/photos">photos</a>
                    <a href="/v2/stores">stores</a>
                </p>
                <p><small>NOTE: Information is reset every day.</small></p>
            </div>
        </div>
    </body>
</html>
