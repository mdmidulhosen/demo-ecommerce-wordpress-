
<?php
/**
 * @author  HeyMehedi
 * @since   0.93
 * @version 0.97
 */
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
	* {
	box-sizing: border-box;
	}

	#myInput {
	background-image: url('/css/searchicon.png');
	background-position: 10px 10px;
	background-repeat: no-repeat;
	width: 100%;
	font-size: 16px;
	padding: 12px 20px 12px 40px;
	border: 1px solid #ddd;
	margin-bottom: 12px;
	}

	#tokensTable {
	border-collapse: collapse;
	width: 100%;
	border: 1px solid #ddd;
	font-size: 18px;
	}

	#tokensTable th, #tokensTable td {
	text-align: left;
	padding: 12px;
	}

	#tokensTable tr {
	border-bottom: 1px solid #ddd;
	}

	#tokensTable tr.header, #tokensTable tr:hover {
	background-color: #f1f1f1;
	}
</style>

<div class="wrap">

	<h2><?php esc_html_e( 'Login Me Now Tokens', 'login-me-now' );?> </h2>

	<form method="post">
		<?php
		$args->prepare_items();
		$args->display(); 
		?>

	</form>

</div>

<script>
	// Show the alert when the page loads
	const Toast = Swal.mixin({
		toast: true,
		position: 'bottom-end',
		showConfirmButton: false,
		timer: 1500,
	});

	function updateStatus(event) {
		let formData = new FormData();
		formData.append( 'action', 'update_status_of_token' );
		formData.append( 'id', event.target.getAttribute('data-id'));
		formData.append( 'status', event.target.value);

		let url = `<?php echo admin_url( 'admin-ajax.php' ); ?>`;

		fetch(url, {
				method: 'POST',
				body: formData,
			} )
			.then( res => res.json() )
			.then( res => {
				Toast.fire({
					icon: 'success',
					title: 'Status updated',
				})
			} )
			.catch( err => {
				Toast.fire({
					icon: 'error',
					title: "Something wen't wrong",
				})
			} );
	}
</script>