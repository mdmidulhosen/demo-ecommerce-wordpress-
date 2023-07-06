import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import apiFetch from '@wordpress/api-fetch';

const License = () => {

  let lmnProLic = useSelector((state) => state.lmnProLic);
  const dispatch = useDispatch();
  console.log(lmnProLic)
  const updateLicense = (License) => {
    dispatch({ type: 'UPDATE_LMN_PRO_LIC', payload: License.target.value });
    
    const formData = new window.FormData();
    formData.append('action', 'login_me_now_update_admin_setting');
    formData.append('security', lmn_admin.update_nonce);
    formData.append('key', 'lmn_pro_lic');
    formData.append('value', License.target.value);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: 'POST',
      body: formData,
    }).then(() => {
      dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
    });
  };

  return (
    <section className={`text-sm block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
      <div className='mr-16 w-full flex flex-col space-y-3'>
        <h3 className="p-0 flex-1 justify-right inline-flex text-xl leading-6 font-semibold text-slate-800">
          {__('License', 'login-me-now')}
        </h3>
        <input onChange={updateLicense} className='block w-full h-[50px] !p-3 !border-slate-200' type='password' name='lmn_pro_lic' value={lmnProLic} placeholder='Enter your license here...' />
        <span class="text-blue-400">
          {__('Add your purchased license here for future updates', 'login-me-now')} 
        </span>
      </div>
    </section>
  )
}

export default License;