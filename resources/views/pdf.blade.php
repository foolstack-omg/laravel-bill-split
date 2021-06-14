<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                margin: 0;
                padding: 0;
                width:273mm;
                height:152mm;
            }
            .page{
                page-break-inside:avoid;
                position: relative;
                width:273mm;
                height:152mm;
            }
        </style>
    </head>
    <body>
       <div class="page">
           <img src="{{ asset('images/1.png') }}" style="position: absolute; width: 53mm; height: 12mm; left: 15mm; top: 15mm;"/>
           <img src="{{ asset('images/2.png') }}" style="position: absolute; width: 136mm; height: 152mm; right: 0; top: 0;"/>
           <div style="top: 60mm; left: 20mm; position: absolute; color: #1b4b72">
               <div style="display: inline;font-size: 10mm; font-weight: bold;">世茂广场</div>
               <div style="display: inline;font-size: 6mm;font-weight: bold;">上画检测报告</div>
           </div>
       </div>
       <div class="page" >
           <img src="{{ asset('images/4.png') }}" style="position: absolute; width: 273mm; height: 17mm; left: 0; top: 10mm;"/>
           <img src="{{ asset('images/5.png') }}" style="position: absolute; width: 64mm; height: 13mm; right: 2mm; top: 13mm;"/>
           <div style="top: 16mm; left: 10mm; position: absolute; font-size: 6mm; color: white; font-weight: bold;">天艺传媒简介</div>
           <img src="{{ asset('images/3.png') }}" style="position: absolute; width: 273mm; height: 82mm; left: 0; top: 27mm;"/>
           <div style="padding: 5mm; white-space: pre-line; line-height: 1.5; color: black; font-size: 5mm; position: absolute; top: 110mm; left: 0; width: 273mm; box-sizing: border-box; word-break: break-all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;厦门市天艺传媒股份有限公司创立于1993年，业务范围覆盖品牌管理、创意策划、整合营销、媒体投放等传播链条。公司发 展近26年，在户外传媒领域极具市场竞争优势，形成了候车亭、站名牌、路名牌、高速大牌、厦门国际邮轮中心媒体、厦门航空 舱内座椅头片媒体、游轮冠名权及船身媒体等“海·陆·空”360°全媒体覆盖。2015年10月天艺成功登陆新三板，成为福建省户外 传媒类先行上市企业。</div>
       </div>
       <div class="page">
           <img src="{{ asset('images/4.png') }}" style="position: absolute; width: 273mm; height: 17mm; left: 0; top: 10mm;"/>
           <img src="{{ asset('images/5.png') }}" style="position: absolute; width: 64mm; height: 13mm; right: 2mm; top: 13mm;"/>
           <div style="top: 16mm; left: 10mm; position: absolute; font-size: 6mm; color: white; font-weight: bold;">广告投放详情</div>

           <img src="{{ asset('images/3.png') }}" style="position: absolute; width: 90mm; height: 68mm; left: 33mm; top: 33mm;"/>
           <img src="{{ asset('images/3.png') }}" style="position: absolute; width: 90mm; height: 68mm; right: 33mm; top: 33mm;"/>
           <div style="top: 110mm; left: 33mm; position: absolute; font-size: 5mm; color: #1b4b72; font-weight: bold; width: 200cm;">位 置: 杏锦路</div>
           <div style="top: 120mm; left: 33mm; position: absolute; font-size: 5mm; color: #1b4b72; font-weight: bold; width: 200cm;">点 位: 园博苑西门站</div>
           <div style="top: 130mm; left: 33mm; position: absolute; font-size: 5mm; color: #1b4b72; font-weight: bold; width: 200cm;">编 号: hct054</div>
       </div>

       <div class="page">
           <div style="top: 70mm; left: 20mm; position: absolute; font-size: 18mm; color: #1b4b72; font-weight: bold;">THANK YOU</div>
           <img src="{{ asset('images/2.png') }}" style="position: absolute; width: 136mm; height: 152mm; right: 0; top: 0;"/>
       </div>
    </body>
</html>
