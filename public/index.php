<?php

use PhpCsFixerPlayground\Fixer;

if (isset($_GET['code']) && is_string($_GET['code'])) {
	require __DIR__.'/../vendor/autoload.php';

	$code = $_GET['code'];

	try {
		$fixed = (new Fixer())->fix($code);

		$result = highlight_string($fixed, true);
	} catch (ParseError $e) {
		$result = htmlentities($e->getMessage());
	}
} else {
	$code = "<?php\n\n";
}

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
