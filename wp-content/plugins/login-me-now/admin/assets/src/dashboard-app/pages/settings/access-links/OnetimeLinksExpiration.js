import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import { Listbox } from '@headlessui/react'
import apiFetch from '@wordpress/api-fetch';

const expirationOptions = [
  { days: 1, name:  __('1 Hour', 'login-me-now')},
  { days: 3, name:  __('3 Hours', 'login-me-now')},
  { days: 6, name:  __('6 Hours', 'login-me-now')},
  { days: 8, name:  __('8 Hours', 'login-me-now')},
  { days: 12, name:  __('12 Hours', 'login-me-now')},
  { days: 24, name:  __('24 Hours', 'login-me-now')},
  { days: 48, name:  __('48 Hours', 'login-me-now')},
  { days: 72, name:  __('72 Hours', 'login-me-now')}
]

const OnetimeLinksExpiration = () => {

  const enableOnetimeLinks = useSelector((state) => state.enableOnetimeLinks);
	const enableOnetimeLinksStatus = false === enableOnetimeLinks ? false : true;

	const dispatch = useDispatch();
  
  const updateLogsExpiration = (days) => {

    dispatch({ type: 'UPDATE_ONETIME_LINKS_EXPIRATION', payload: days });

    const formData = new window.FormData();
    formData.append('action', 'login_me_now_update_admin_setting');
    formData.append('security', lmn_admin.update_nonce);
    formData.append('key', 'onetime_links_expiration');
    formData.append('value', days);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: 'POST',
      body: formData,
    }).then(() => {
      dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
    });
  };

	let days = useSelector((state) => state.onetimeLinksExpiration);
  const currentOption = expirationOptions.find(option => option.days == days);
  
  return (
    <section className={`login-me-now-dep-field-${enableOnetimeLinksStatus} text-sm block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className='mr-16 w-full flex items-center'></div>
    
      <Listbox onChange={updateLogsExpiration}>

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

export default OnetimeLinksExpiration;