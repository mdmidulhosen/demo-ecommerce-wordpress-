<?php
/**
 * Extension Popup.
 *
 * @package Login Me Now
 * @since 0.97
 */

$current_user = wp_get_current_user();
$html         = '<div class="lmnExt" id="lmnExt">';
$html .= sprintf( '<div id="lmnEmail" data-email="%s"></div>', $current_user->user_email );
$html .= sprintf( '<div id="lmnSiteUrl" data-siteurl="%s"></div>', get_site_url() );
$html .= sprintf( '<div id="lmnSecurity" data-security="%s"></div>', wp_create_nonce( 'login_me_now_generate_onetime_link_nonce' ) );
$html .= sprintf( '<div id="lmnAjaxUrl" data-ajaxurl="%s"></div>', admin_url( 'admin-ajax.php' ) );
$html .= __( '<p>To enjoy effortless and quick access, save this dashboard login</p>', 'login-me-now' );
$html .= sprintf( '<button id="lmn-save">%s</button>', __( 'Save Now <img src="https://img.icons8.com/color/512/chrome.png">', 'login-me-now' ) );
$html .= sprintf( '<button id="lmn-later">%s</button>', __( 'Later', 'login-me-now' ) );
$html .= '</div>';
echo $html;
?>
<script>
	let lmnLater = document.getElementById('lmn-later');
	const keyUrlEmail = '<?php echo get_site_url() . '_' . $current_user->user_email; ?>';
	let lmnExt = document.querySelector("#lmnExt");

	if( typeof lmnLater !== 'undefined' && lmnLater	) {
		lmnLater.addEventListener('click', function(e){
			const today = new Date();
			const nextWeek = new Date(today);
			nextWeek.setDate(today.getDate() + 3);
			localStorage.setItem(keyUrlEmail, nextWeek);
			lmnExt.style.display = 'none';
		})
	}

	let hasLater = localStorage.getItem(keyUrlEmail);
	if( typeof hasLater !== 'undefined' && hasLater	) {
		const today = new Date();
		const expectedTime = new Date(hasLater);
		if (today.getDate() !== expectedTime.getTime()) {
			lmnExt.innerHTML = '';
		} else {
			localStorage.removeItem(keyUrlEmail);
		}
	}
</script>

<style>
	.lmnExt {
		display: none;
		position: fixed;
		height: 60px;
		width: 100%;
		left: 0;
		bottom: 0;
		background: #3A86FF;
		color: #fff;
		text-align: center;
		z-index: 999999999;
		justify-content: center;
		align-items: center;
		transition: .3s;
	}
	.lmnExt p {
		font-size: 16px;
		margin-right: 10px;
	}
	#lmnExt button {
		padding: 5px 15px;
		margin: 0 5px;
		cursor: pointer;
		border: 0;
		background: #1A3B66;
		text-transform: uppercase;
		border-radius: 5px;
		color: #fff;
		transition: .3s;
	}

	#lmnExt button:hover {
		background: #062247;
	}
	#lmnExt #lmn-save {
		color: #26FF75;
		display: flex;
		align-items: center;
		justify-content: center;
		columns: #26FF75;
	}

	#lmn-save img {
		max-width: 20px;
		margin-left: 5px;
	}
</style>