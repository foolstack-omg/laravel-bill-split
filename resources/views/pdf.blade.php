<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                margin: 0;
                padding: 0;
                width: 1000px;
            }
            .page{
                page-break-inside:avoid;
                position: relative;
                width:1362px;
                min-height:800px;
            }
        </style>
    </head>
    <body>
       <div class="page">
           <img src="https://wkhtmltopdf.org/images/banner.jpg" style="position: absolute; width: 500px; height: 100%; left: 0; top: 0;"/>
           <div style="top: 100px; right: 100px; position: absolute;">100000</div>
       </div>
       <div class="page" >
           <div style="top: 100px; right: 100px; position: absolute;">100000</div>
       </div>
       <div class="page">
           <div style="top: 100px; right: 100px; color: #1b4b72;  position: absolute;">100000</div>
       </div>
    </body>
</html>
