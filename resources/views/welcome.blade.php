<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JsonApi Playground</title>

        <!-- Fonts -->
        <link href="//fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

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
                    JSON:API playground
                </div>

                <h2>Data</h2>
                <ul class="uris">
                    <li><span>GET</span> <a href="/v2/authors">/v2/authors</a></li>
                    <li><span>GET</span> <a href="/v2/authors/1">/v2/authors/1</a></li>
                    <li><span>GET</span> <a href="/v2/authors/1/books">/v2/authors/1/books</a></li>
                    <li><span>GET</span>
                        <a href="/v2/authors?page[number]=1&page[size]=5&filter[name]=S&include=books,photos">
                            /v2/authors?page[number]=1&page[size]=5&filter[name]=S&include=books,photos
                        </a>
                    </li>
                    <li><span>GET</span>
                        <a href="/v2/books?filter[date_published][since]=1900-01-01&filter[date_published][until]=2020-01-01">
                            /v2/books?filter[date_published][since]=1900-01-01&filter[date_published][until]=2020-01-01
                        </a>
                    </li>
                    <li><span>GET</span> <a href="/v2/stores?filter[created_by]=1,3">/v2/stores?filter[created_by]=1,3</a></li>
                </ul>
                <p>More json:api resources?
                    <a href="/v2/books">books</a>
                    <a href="/v2/series">series</a>
                    <a href="/v2/chapters">chapters</a>
                    <a href="/v2/photos">photos</a>
                    <a href="/v2/stores">stores</a>
                </p>

                <h3>Business rules</h3>
                <ul>
                    <li>
                        chapters resource can't be created, updated or deleted.
                        <a href="https://github.com/reyesoft/jsonapi-playground/blob/master/app/ChapterPolicy.php#L15" target="_blank">↗</a>
                    </li>
                    <li>
                        stores.address can be only set on create, can't be updated.
                        <a href="https://github.com/reyesoft/jsonapi-playground/blob/master/app/StoreSchema.php#L25" target="_blank">↗</a>
                    </li>
                    <li>
                        stores.created_by can be only read.
                        <a href="https://github.com/reyesoft/jsonapi-playground/blob/master/app/StoreSchema.php#L29" target="_blank">↗</a>
                    </li>
                    <li>
                        chapter 1 cant be removed.
                        <a href="https://github.com/reyesoft/jsonapi-playground/blob/master/app/AuthorPolicy.php#L15" target="_blank">↗</a>
                    </li>
                </ul>

                <h2>More info</h2>
                <ul>
                    <li><a href="http://ngx-jsonapi.reyesoft.com/" target="_blank">Online JSON API client example</a></li>
                    <li><a href="http://jsonapi.org" target="_blank">JSON API Specification</a></li>
                    <li><a href="https://github.com/reyesoft/jsonapi-playground" target="_blank">Source code of this project</a></li>
                </ul>

                <p><small>NOTE: Information is reset every day.</small></p>

                <p><small>Made and served with 💖 by <a href="https://www.reyesoft.com/" target="_blank">Reyesoft</a></small></p>
            </div>
        </div>
    </body>
</html>
