<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>

</head>
<body>
    <div>
        <!-- ページ本体 -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>