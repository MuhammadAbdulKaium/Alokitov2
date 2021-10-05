
@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alokito Software</title>
    <style>
        .container{padding: 30px;}
    </style>
</head>
<body>
<div class="container">
    <embed src="/images/{{$publication->file}}" type="application/pdf" width="100%" height="600px">
</div>

</body>
</html>
@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif
