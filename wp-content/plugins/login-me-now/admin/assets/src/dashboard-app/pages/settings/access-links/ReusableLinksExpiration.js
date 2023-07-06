import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import { Listbox } from '@headlessui/react'
import apiFetch from '@wordpress/api-fetch';

const expirationOptions = [
  { days: 1, name:  __('1 Day', 'login-me-now')},
  { days: 7, name:  __('1 Week', 'login-me-now')},
  { days: 30, name:  __('1 Month', 'login-me-now')},
  { days: 365, name:  __('1 Year', 'login-me-now')},
  { days: 10000, name:  __('Lifetime ', 'login-me-now')},
]

const ReusableLinksExpiration = () => {

  const enableReusableLinks = useSelector((state) => state.enableReusableLinks);
	const enableReusableLinksStatus = false === enableReusableLinks ? false : true;

	const dispatch = useDispatch();
  
  const updateReusableLinksExpiration = (days) => {

    dispatch({ type: 'UPDATE_REUSABLE_LINKS_EXPIRATION', payload: days });

    const formData = new window.FormData();
    formData.append('action', 'login_me_now_update_admin_setting');
    formData.append('security', lmn_admin.update_nonce);
    formData.append('key', 'reusable_links_expiration');
    formData.append('value', days);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: 'POST',
      body: formData,
    }).then(() => {
      dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
    });
  };

	let days = useSelector((state) => state.reusableLinksExpiration);
  const currentOption = expirationOptions.find(option => option.days == days);
  
  return (
    <section className={`login-me-now-dep-field-${enableReusableLinksStatus} text-sm block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className='mr-16 w-full flex items-center'></div>
    
      <Listbox onChange={updateReusableLinksExpiration}>

        <Listbox.Button>{ __('Expire after ', 'login-me-now') + ' ' + currentOption.name}</Listbox.Button>

        <Listbox.Options className='bg-slate-10 pt-3 divide-y divide-dashed'>
          
          {expirationOptions.map((option) => (

            <Listbox.Option 
              key={option.days} 
              value={option.days} 
              className='text-sm text-slate-500 relative cursor-pointer select-none py-2 pr-1 mb-1'
              >
              {option.name}
            </Listbox.Option>

          ))}

        </Listbox.Options>

      </Listbox>

    </section>
  )
}

export default ReusableLinksExpiration;