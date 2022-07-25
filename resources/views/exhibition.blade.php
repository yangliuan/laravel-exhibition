<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>websocket test</title>
    {{-- <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.css"> --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    {{-- <script src="https://unpkg.com/swiper@8/swiper-bundle.js"> </script> --}}
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"> </script>
    {{-- <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js.map"> </script> --}}
    <style>
        .swiper {
            width: 800px;
            height: 600px;
        }
    </style>
</head>
<body>
    <div class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="/show/1.jpeg"></div>
            <div class="swiper-slide"><img src="/show/2.gif"></div>
            <div class="swiper-slide"><img src="/show/3.gif"></div>
            <div class="swiper-slide"><img src="/show/4.gif"></div>
            <div class="swiper-slide"><img src="/show/5.jpg"></div>
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>
    </div>
    <script>
        //DOC:https://www.swiper.com.cn/api/methods/107.html
        var mySwiper = new Swiper ('.swiper', {
          direction: 'horizontal', // 垂直切换选项
          loop: true, // 循环模式选项

          // 如果需要分页器
          pagination: {
            el: '.swiper-pagination',
          },
        });
    </script>
</body>
</html>
