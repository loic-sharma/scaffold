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

				<?php if (false && Session::get_flash('success')): ?>
					<div class="alert-message success">
						<p>
						<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
						</p>
					</div>
				<?php endif; ?>

				<?php echo '<?php'; ?> if($errors->has()): ?>
					<div class="alert-message error">
						<?php echo '<?php '; ?> foreach($errors->all('<p>:message</p>') as $error): ?>
							<?php echo '<?php'; ?> echo $error; ?>
						<?php echo '<?php endforeach; ?>'; ?>
					</div>
				<?php echo '<?php endif; ?>'; ?>
			</div>
			<div class="span16">
				<?php echo '<?php'; ?> echo $content; ?>
			</div>
		</div>
	</div>
</body>
</html>
