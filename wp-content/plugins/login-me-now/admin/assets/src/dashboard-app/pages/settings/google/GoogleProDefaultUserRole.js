import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import { Listbox } from '@headlessui/react'
import apiFetch from '@wordpress/api-fetch';
import { ChevronDownIcon } from '@heroicons/react/solid'

const GoogleProDefaultUserRole = () => {
	const enableGoogleLogin = useSelector((state) => state.enableGoogleLogin);
	const enableGoogleLoginStatus = false === enableGoogleLogin ? false : true;
	const dispatch = useDispatch();
	const isProAvailable = lmn_admin.pro_available ? true : false;
	const getUserRoles = useSelector((state) => state.getUserRoles);
	const updateGoogleProDefaultUserRole = (role) => {
		dispatch({ type: 'UPDATE_SELECT_GOOGLE_PRO_DEFAULT_USER_ROLE', payload: role });
	
		const formData = new window.FormData();
		formData.append('action', 'login_me_now_update_admin_setting');
		formData.append('security', lmn_admin.update_nonce);
		formData.append('key', 'google_pro_default_user_role');
		formData.append('value', role);
	
		apiFetch({
		  url: lmn_admin.ajax_url,
		  method: 'POST',
		  body: formData,
		}).then(() => {
		  dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
		});
	};

	const role = useSelector((state) => state.selectGoogleProDefaultUserRole);
	let currentOption = 'Subscriber';
	if (getUserRoles && Object.hasOwnProperty.call(getUserRoles, role)) {
		currentOption = getUserRoles[role];
	}

	return (
		<section className={`login-me-now-dep-field-${(isProAvailable && enableGoogleLoginStatus ) ? 'true' : 'false'} block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className='mr-16 w-full flex flex-col'>
				
				<h3 className="p-0 flex-1 justify-right inline-flex text-xl leading-6 font-semibold text-slate-800">
					{__('Default user role', 'login-me-now')}
					{ ! lmn_admin.pro_available ? (
						<span className="ml-2 h-full inline-flex leading-[1rem] font-medium flex-shrink-0 py-[0rem] px-1.5 text-[0.625rem] text-white bg-slate-800 border border-slate-800 rounded-[0.1875rem] -tablet:mt:10">
							{__('PRO', 'login-me-now')}
						</span>)
						: 
						''
					}
				</h3>
		
				<Listbox onChange={updateGoogleProDefaultUserRole}>
					
					<Listbox.Button className="block w-full text-left h-[50px] p-3 mt-3 text-lg border !border-slate-200">
						<span className="block truncate">{currentOption}</span>
						<span className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
							<ChevronDownIcon className="h-5 w-5" aria-hidden="true" />
						</span>
					</Listbox.Button>

					<Listbox.Options className='p-3 absolute mt-1 max-h-60 w-[400px] overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm'>
						{Object.keys(getUserRoles).map((key) => (
							<Listbox.Option
								key={key}
								value={key}
								className='text-lg text-slate-800 relative cursor-pointer py-2 pr-1 mb-1'
							>
								{getUserRoles[key]}
							</Listbox.Option>
						))}
					</Listbox.Options>

				</Listbox>
				
			</div>
			<p className="mt-2 w-9/12 text-sm text-slate-500 tablet:w-full">
				{__("Update First, Last, Display & Nick Name according to Google profile.", 'login-me-now')}
			</p>
		</section>
	);
};

export default GoogleProDefaultUserRole;