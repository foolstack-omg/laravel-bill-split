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
            }
            .page{
                page-break-inside:avoid;
                position: relative;
                width:273mm;
                height:210mm;
            }
        </style>
    </head>
    <body>
       <div class="page">
           <img src="https://wkhtmltopdf.org/images/banner.jpg" style="position: absolute; width: 136mm; height: 210mm; left: 0; top: 0;"/>
           <div style="top: 10mm; right: 10mm; position: absolute;">100000</div>
       </div>
       <div class="page" >
           <div style="top: 100px; right: 100px; position: absolute;">100000</div>
       </div>
       <div class="page">
           <div style="top: 100px; right: 100px; color: #1b4b72;  position: absolute;">100000</div>
       </div>
    </body>
</html>
