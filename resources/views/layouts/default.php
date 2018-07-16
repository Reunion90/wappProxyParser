<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="<?php echo csrf_token() ?>">

	<title></title>
	<link rel="stylesheet" href="<?php echo mix('/css/app.css') ?>">
	<script src="<?php echo mix('/js/app.js') ?>"></script>
</head>
<body>
	<div class="container">
		<div>
			<h2>Сканер прокси-сайтов</h2>
		</div>
		<?php echo $sContent ?>
	</div>
</body>
</html>