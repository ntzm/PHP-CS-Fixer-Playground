<?php
use function PhpCsFixerPlayground\escape as e;
use function PhpCsFixerPlayground\format;
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
    <h1>PHP-CS-Fixer Playground</h1>
    <a href="https://github.com/ntzm/PHP-CS-Fixer-Playground/">GitHub</a>
    <div class="container">
        <div>
            <form id="form">
                <textarea name="code" id="code" cols="30" rows="10"><?= e($code) ?></textarea>
                <button>Run</button>

                <ul>
                    <?php foreach ($availableFixers as $fixer): ?>
                        <?php
                        /** @var PhpCsFixer\Fixer\DefinedFixerInterface $fixer */
                        $name = $fixer->getName();
                        $checked = in_array($name, $fixers, true);
                        ?>
                        <label>
                            <input type="checkbox" name="fixers[]" value="<?= e($name) ?>"<?= $checked ? ' checked' : '' ?>> <?= e($name) ?>
                        </label>
                        <br>
                        <span><?= format(e($fixer->getDefinition()->getSummary())) ?></span>
                        <?php if ($fixer->isRisky()): ?>
                            <br>
                            <strong>Risky rule: <?= format(e($fixer->getDefinition()->getRiskyDescription())) ?></strong>
                        <?php endif ?>
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
