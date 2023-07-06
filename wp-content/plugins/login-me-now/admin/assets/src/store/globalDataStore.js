import { createStore } from 'redux';
import globalDataReducer from './globalDataReducer';

const initialState = wp.hooks.applyFilters('login_me_now_dashboard/datastore', {
	initialStateSetFlag: false,
	enableLoadFontsLocally: false,

	lmnProLic: '',

	enableLogs: true,
	logsExpiration: 7,

	enableOnetimeLinks: true,
	onetimeLinksExpiration: 8,

	enableReusableLinks: true,
	reusableLinksExpiration: 365,

	enableUserSwitching: true,

	enableGoogleLogin: false,
	enableGoogleClientID: '',
	enableGoogleNativeLogin: true,
	enableGoogleAutoSignIn: false,
	enableGoogleUpdateExistingUserData: true,
	enableGoogleCancelOnTapOutside: true,
	
	selectGoogleProExcludePages: [],
	selectGoogleProDefaultUserRole: 'subscriber',
	inputGoogleProRedirectUrl: '',

	getUserRoles: 'subscriber',
	get_pages: '',

	enablePreloadLocalFonts: false,
	enableWhiteLabel: false,
	enableBeta: 'disable',
	settingsSavedNotification: '',
	magicLinkPopup: '',
	blocksStatuses: [],
	enableFileGeneration: 'disable',
	activeSettingsNavigationTab: '',
}
);

const globalDataStore = createStore(
	globalDataReducer,
	initialState,
	window.__REDUX_DEVTOOLS_EXTENSION__ &&
	window.__REDUX_DEVTOOLS_EXTENSION__()
);

export default globalDataStore;
