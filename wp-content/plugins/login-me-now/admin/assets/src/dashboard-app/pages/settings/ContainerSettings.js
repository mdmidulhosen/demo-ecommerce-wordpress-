import { __ } from '@wordpress/i18n';
import { useSelector } from 'react-redux';

import Logs from './general/Logs';
import LogsExpiration from './general/LogsExpiration';
import OnetimeLinks from './access-links/OnetimeLinks';
import OnetimeLinksExpiration from './access-links/OnetimeLinksExpiration';
import ReusableLinks from './access-links/ReusableLinks';
import ReusableLinksExpiration from './access-links/ReusableLinksExpiration';
import UserSwitching from './general/UserSwitching';

import GoogleLogin from './google/GoogleLogin';
import GoogleClientID from './google/GoogleClientID';
import GoogleInNativeLogin from './google/GoogleInNativeLogin';
import GoogleInAutoSignIn from './google/GoogleInAutoSignIn';
import GoogleInUpdateExistingUserData from './google/GoogleInUpdateExistingUserData';
import GoogleCancelOnTapOutside from './google/GoogleCancelOnTapOutside';
import GoogleProDefaultUserRole from './google/GoogleProDefaultUserRole';
import GoogleProExcludePages from './google/GoogleProExcludePages';
import GoogleProRedirectUrl from './google/GoogleProRedirectUrl';

import License from './license/License';

function SettingsWrapper({ state }) {
	const wrappers = wp.hooks.applyFilters(
		'login_me_now_dashboard.settings_tab_wrappers',
		{
			'global-settings': <> <Logs /> <LogsExpiration /> <UserSwitching /> </>,
			'access-links': <> <OnetimeLinks/> <OnetimeLinksExpiration/> <ReusableLinks /> <ReusableLinksExpiration /></>,
			'google': <>
				<GoogleLogin/> 
				<GoogleClientID/>
				<GoogleInNativeLogin/>
				<GoogleInAutoSignIn/>
				<GoogleProDefaultUserRole/>
				<GoogleInUpdateExistingUserData/>
				<GoogleProExcludePages/>
				<GoogleCancelOnTapOutside/>
				<GoogleProRedirectUrl/>
			</>,
			'license':<>
				<License/>
			</>
		}
	);
	return <div>{wrappers[state]}</div>;
}

const ContainerSettings = () => {

	const activeSettingsNavigationTab = useSelector((state) => state.activeSettingsNavigationTab);

	// Parent Div is Required to add Padding to the Entire Structure for Smaller Windows.
	return (
		<>
			<div className='lg:col-span-9 border-l'>
				{wp.hooks.applyFilters(`login_me_now_dashboard.settings_screen_before_${activeSettingsNavigationTab}`, <span />)}
				<SettingsWrapper state={activeSettingsNavigationTab}></SettingsWrapper>
				{wp.hooks.applyFilters(`login_me_now_dashboard.settings_screen_after_${activeSettingsNavigationTab}`, <span />)}
			</div>
		</>
	);
};

export default ContainerSettings;
