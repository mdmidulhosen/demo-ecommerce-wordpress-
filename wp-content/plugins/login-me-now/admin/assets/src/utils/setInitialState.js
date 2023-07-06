import apiFetch from '@wordpress/api-fetch';

const setInitialState = (store) => {
	apiFetch({
		path: '/login-me-now/v1/admin/settings/',
	}).then((data) => {
		const initialState = {
			settingsSavedNotification: '',
			magicLinkPopup: '',
			initialStateSetFlag: true,
			activeSettingsNavigationTab: 'global-settings',
			enableLoadFontsLocally: data.self_hosted_gfonts,

			lmnProLic: data.lmn_pro_lic,

			enableLogs: data.logs,
			logsExpiration: data.logs_expiration,

			enableOnetimeLinks: data.onetime_links,
			onetimeLinksExpiration: data.onetime_links_expiration,

			enableReusableLinks: data.reusable_links,
			reusableLinksExpiration: data.reusable_links_expiration,

			enableUserSwitching: data.user_switching,

			enableGoogleLogin: data.google_login,
			enableGoogleClientID: data.google_client_id,
			enableGoogleNativeLogin: data.google_native_login,
			enableGoogleAutoSignIn: data.google_auto_sign_in,
			enableGoogleUpdateExistingUserData: data.google_update_existing_user_data,
			enableGoogleCancelOnTapOutside: data.google_cancel_on_tap_outside,

			selectGoogleProExcludePages: data.google_pro_exclude_pages,
			selectGoogleProDefaultUserRole: data.google_pro_default_user_role,
			inputGoogleProRedirectUrl: data.google_pro_redirect_url,

			getUserRoles: data.get_user_roles,
			getPages: data.get_pages,

			enablePreloadLocalFonts: data.preload_local_fonts,
			blocksStatuses: data.pro_addons,
		};

		store.dispatch({ type: 'UPDATE_INITIAL_STATE', payload: initialState });
	});
};

export default setInitialState;
