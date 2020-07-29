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
    <div>
        <form action="/demo/save" method="post" enctype="multipart/form-data">
            @csrf
            <div>姓名：<input type="text"></div>
            <div>头像: <input type="file" name="avater" id=""></div>
            <div><button type="submit">提交</button></div>
        </form>
    </div>
</body>
</html>
