<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">
    <div class="col-md-12">
        Site Dili: {{\Lang::getLocale()}}<br>
        Dil Değiştir <a href="{{url('/lang/tr')}}">Türkçe</a> | <a href="{{url('/lang/en')}}">İngilizce</a>
        <br>
        <h3>{{\Lang::get('home.hello')}}</h3>

        @if($lang_msg= session('msj'))
            <p style="background-color: #1d643b; padding: 15px;">{{$lang_msg}}</p>
        @endif
    </div>
</div>
</body>
</html>