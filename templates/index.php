<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP-CS-Fixer Playground</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <div>
            <form id="form">
                <textarea name="code" id="code" cols="30" rows="10"><?= htmlentities($code) ?></textarea>
                <button>Run</button>
            </form>
        </div>
        <div>
            <pre id="result"><?= $result ?? '' ?></pre>
        </div>
    </div>
</body>
</html>
