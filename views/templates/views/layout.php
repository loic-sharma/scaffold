<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo '<?php'; ?> echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo '<?php'; ?> echo asset('bundles/scaffold/css/bootstrap.css'); ?>">
	<style>
		body { margin: 40px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span16">
				<h1><?php echo '<?php'; ?> echo $title; ?></h1>
				<hr>

				<?php echo '<?php'; ?> if (Session::has('message')): ?>
					<div class="alert-message success">
						<p><?php echo '<?php'; ?> echo Session::get('message'); ?></p>
					</div>
				<?php echo '<?php endif; ?>'.PHP_EOL; ?>

				<?php echo '<?php'; ?> if($errors->has()): ?>
					<div class="alert-message error">
						<?php echo '<?php '; ?> foreach($errors->all('<p>:message</p>') as $error): ?>
							<?php echo '<?php'; ?> echo $error; ?>
						<?php echo '<?php endforeach; ?>'.PHP_EOL; ?>
					</div>
				<?php echo '<?php endif; ?>'.PHP_EOL; ?>
			</div>
			<div class="span16">
				<?php echo '<?php '; ?> echo $content; ?>
			</div>
		</div>
	</div>
</body>
</html>
