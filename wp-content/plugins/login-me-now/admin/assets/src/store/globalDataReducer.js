const globalDataReducer = (state = {}, action) => {
	let actionType = wp.hooks.applyFilters('login_me_now_dashboard/data_reducer_action', action.type);
	switch (actionType) {
		case 'UPDATE_INITIAL_STATE':
			return {
				...action.payload,
			};
		case 'UPDATE_BLOCK_STATUSES':
			return {
				...state,
				blocksStatuses: action.payload
			};
		case 'UPDATE_INITIAL_STATE_FLAG':
			return {
				...state,
				initialStateSetFlag: action.payload,
			};
		case 'UPDATE_SETTINGS_ACTIVE_NAVIGATION_TAB':
			return {
				...state,
				activeSettingsNavigationTab: action.payload
			};
		case 'UPDATE_ENABLE_LOAD_FONTS_LOCALLY':
			return {
				...state,
				enableLoadFontsLocally: action.payload,
			};
		case 'UPDATE_ENABLE_LOGS':
			return {
				...state,
				enableLogs: action.payload,
			};
		case 'UPDATE_LOGS_EXPIRATION':
			return {
				...state,
				logsExpiration: action.payload,
			};
		case 'UPDATE_ENABLE_ONETIME_LINKS':
			return {
				...state,
				enableOnetimeLinks: action.payload,
			};
		case 'UPDATE_ONETIME_LINKS_EXPIRATION':
			return {
				...state,
				onetimeLinksExpiration: action.payload,
			};
			
		case 'UPDATE_ENABLE_REUSABLE_LINKS':
			return {
				...state,
				enableReusableLinks: action.payload,
			};
		case 'UPDATE_REUSABLE_LINKS_EXPIRATION':
			return {
				...state,
				reusableLinksExpiration: action.payload,
			};

		case 'UPDATE_ENABLE_USER_SWITCHING':
				return {
					...state,
					enableUserSwitching: action.payload,
				};

		case 'UPDATE_ENABLE_GOOGLE_LOGIN':
			return {
				...state,
				enableGoogleLogin: action.payload,
			};
		case 'UPDATE_GOOGLE_CLIENT_ID':
			return {
				...state,
				enableGoogleClientID: action.payload,
			};
		case 'UPDATE_ENABLE_GOOGLE_NATIVE_LOGIN':
			return {
				...state,
				enableGoogleNativeLogin: action.payload,
			};
		case 'UPDATE_ENABLE_GOOGLE_AUTO_SIGN_IN':
			return {
				...state,
				enableGoogleAutoSignIn: action.payload,
			};
		case 'UPDATE_ENABLE_GOOGLE_UPDATE_EXISTING_USER_DATA':
			return {
				...state,
				enableGoogleUpdateExistingUserData: action.payload,
			};
		case 'UPDATE_ENABLE_CANCEL_ON_TAP_OUTSIDE':
			return {
				...state,
				enableGoogleCancelOnTapOutside: action.payload,
			};
		
		case 'UPDATE_SELECT_GOOGLE_PRO_DEFAULT_USER_ROLE':
			return {
				...state,
				selectGoogleProDefaultUserRole: action.payload,
			};
		case 'UPDATE_SELECT_GOOGLE_PRO_EXCLUDE_PAGES':
			return {
				...state,
				selectGoogleProExcludePages: action.payload,
			};
		case 'UPDATE_INPUT_GOOGLE_PRO_REDIRECT_URL':
			return {
				...state,
				inputGoogleProRedirectUrl: action.payload,
			};


		case 'UPDATE_ENABLE_PRELOAD_LOCAL_FONTS':
			return {
				...state,
				enablePreloadLocalFonts: action.payload,
			};
		case 'UPDATE_SETTINGS_SAVED_NOTIFICATION':
			return {
				...state,
				settingsSavedNotification: action.payload,
			};
		case 'GENERATE_MAGIC_LINK_POPUP':
			return {
				...state,
				magicLinkPopup: action.payload,
			};

		case 'UPDATE_LMN_PRO_LIC':
			return {
				...state,
				lmnProLic: action.payload,
			};
		default:
			return state;
	}
}

export default globalDataReducer;