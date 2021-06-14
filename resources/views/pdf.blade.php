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
                width: 1000px;
                height: 600px;
                page-break-inside:avoid;
                position: relative;
            }
        </style>
    </head>
    <body>
       <div class="page">
           <img src="https://www.baidu.com/img/pc_4dd272233fa6c632f9dfadf9d948974f.gif" style="position: absolute; width: 500px; height: 500px; left: 100px; top: 100px;"/>
       </div>
       <div class="page" >
           <div style="top: 100px; right: 100px;">100000</div>
       </div>
       <div class="page">
           <div style="top: 100px; right: 100px; color: #1b4b72">100000</div>
       </div>
    </body>
</html>
