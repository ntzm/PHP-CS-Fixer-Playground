<?php
use function PhpCsFixerPlayground\escape as e;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP-CS-Fixer Playground</title>
    <style>
        * {
            box-sizing: border-box;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .container > div {
            flex-grow: 1;
            flex-basis: 0;
        }

        #code {
            font-family: monospace;
            width: 100%;
        }

        #form {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <form id="form">
                <textarea name="code" id="code" cols="30" rows="10"><?= e($code) ?></textarea>
                <button>Run</button>

                <ul>
                    <?php foreach ($fixers as $fixer): ?>
                        <label>
                            <input type="checkbox" name="fixers[]" value="<?= e($fixer->getName()) ?>"> <?= e($fixer->getName()) ?>
                        </label>
                        <br>
                    <?php endforeach ?>
                </ul>
            </form>
        </div>
        <div>
            <pre id="result"><?= $result ?? '' ?></pre>
        </div>
    </div>
</body>
</html>
