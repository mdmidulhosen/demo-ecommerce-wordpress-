<?php
/**
 * @author  HeyMehedi
 * @since   0.96
 * @version 0.96
 */
?>
<div class="wrap">

	<h2><?php esc_html_e( 'Login Me Now Logs', 'login-me-now' );?> </h2>

	<form method="post">
		<?php
		$args->prepare_items();
		$args->display(); 
		?>

	</form>

</div>