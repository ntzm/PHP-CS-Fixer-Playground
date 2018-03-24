<?php
use PhpCsFixer\Console\Application;
use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        #code {
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP-CS-Fixer Playground <span class="badge badge-primary"><?= Application::VERSION ?></span></h1>
        <a href="https://github.com/ntzm/PHP-CS-Fixer-Playground/">GitHub</a>
        <div class="row">
            <div class="col-sm-6">
                <form method="post" action="/">
                    <div class="form-group">
                        <textarea class="form-control" name="code" id="code" cols="30" rows="10"><?= e($code) ?></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Run</button>
                    </div>
                    <ul class="list-group">
                        <?php foreach ($availableFixers as $fixer): ?>
                            <?php
                            /** @var PhpCsFixer\Fixer\DefinedFixerInterface $fixer */
                            $name = $fixer->getName();
                            $checked = in_array($name, $fixers, true);
                            ?>
                            <li class="list-group-item<?= $fixer->isRisky() ? ' list-group-item-warning' : '' ?>">
                                <label>
                                    <input type="checkbox" name="fixers[]" value="<?= e($name) ?>"<?= $checked ? ' checked' : '' ?>> <?= e($name) ?>
                                </label>
                                <p><?= format(e($fixer->getDefinition()->getSummary())) ?></p>
                                <?php if ($fixer->isRisky()): ?>
                                    <p><strong>Risky rule: <?= format(e($fixer->getDefinition()->getRiskyDescription())) ?></strong></p>
                                <?php endif ?>
                                <?php if ($fixer instanceof ConfigurationDefinitionFixerInterface): ?>
                                    <?php foreach ($fixer->getConfigurationDefinition()->getOptions() as $option): ?>
                                        <div class="form-group">
                                            <label><?= e($option->getName()) ?></label>
                                            <p><?= format(e($option->getDescription())) ?></p>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </form>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <pre><?= $result ? e($result) : '' ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
