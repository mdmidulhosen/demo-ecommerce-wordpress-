import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import { Listbox } from '@headlessui/react'
import apiFetch from '@wordpress/api-fetch';

const expirationOptions = [
  { days: 7, name:  __('7 Days', 'login-me-now')},
  { days: 30, name:  __('30 Days', 'login-me-now')},
  { days: 60, name:  __('60 Days', 'login-me-now')},
  { days: 90, name:  __('90 Days', 'login-me-now')}
]

const LogsExpiration = () => {

  const enableLogs = useSelector((state) => state.enableLogs);
	const enableLogsStatus = false === enableLogs ? false : true;

	const dispatch = useDispatch();
  
  const updateLogsExpiration = (days) => {

    dispatch({ type: 'UPDATE_LOGS_EXPIRATION', payload: days });

    const formData = new window.FormData();
    formData.append('action', 'login_me_now_update_admin_setting');
    formData.append('security', lmn_admin.update_nonce);
    formData.append('key', 'logs_expiration');
    formData.append('value', days);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: 'POST',
      body: formData,
    }).then(() => {
      dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
    });
  };

	let days = useSelector((state) => state.logsExpiration);
  const currentOption = expirationOptions.find(option => option.days == days);

  return (
    <section className={`login-me-now-dep-field-${enableLogsStatus} text-sm block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className='mr-16 w-full flex items-center'></div>
      {/* ul#headlessui-listbox-options-5 li {
    padding: 5px;
    border-bottom: 1px solid #99999920;
}

ul#headlessui-listbox-options-5 {
    padding-top: 15px;
} */}
      <Listbox onChange={updateLogsExpiration}>

        <Listbox.Button>{ __('Store Log Data for ', 'login-me-now') + ' ' + currentOption.name}</Listbox.Button>

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

export default LogsExpiration;