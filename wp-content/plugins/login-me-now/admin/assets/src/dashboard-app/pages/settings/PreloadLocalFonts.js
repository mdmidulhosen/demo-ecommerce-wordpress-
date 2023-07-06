import { __ } from '@wordpress/i18n';
import { useSelector, useDispatch } from 'react-redux';
import { Switch } from '@headlessui/react';
import apiFetch from '@wordpress/api-fetch';

function classNames( ...classes ) {
	return classes.filter( Boolean ).join( ' ' )
}

const PreloadLocalFonts = () => {

	const dispatch = useDispatch();

	const enableLoadFontsLocally = useSelector( ( state ) => state.enableLoadFontsLocally );
	const enablePreloadLocalFonts = useSelector( ( state ) => state.enablePreloadLocalFonts );

	const enablePreloadLocalFontsStatus = false === enablePreloadLocalFonts ? false : true;

	const updatePreloadLocalFontsStatus = () => {

		let assetStatus;
		if ( enablePreloadLocalFonts === false ) {
			assetStatus = true;
		} else {
			assetStatus = false;
		}

		dispatch( { type: 'UPDATE_ENABLE_PRELOAD_LOCAL_FONTS', payload: assetStatus } );

		const formData = new window.FormData();

		formData.append( 'action', 'login_me_now_update_admin_setting' );
		formData.append( 'security', lmn_admin.update_nonce );
		formData.append( 'key', 'preload_local_fonts' );
		formData.append( 'value', assetStatus );

		apiFetch( {
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		} ).then( () => {
			dispatch( { type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __( 'Successfully saved!', 'login-me-now' ) } );
		} );
	};

	return (
		<section className={ `login-me-now-dep-field-${ enableLoadFontsLocally } block border-b border-solid border-slate-200 px-8 py-8 justify-between` }>
			<div className='mr-16 w-full flex items-center'>
				<h3 className="p-0 flex-1 justify-right inline-flex text-xl leading-6 font-semibold text-slate-800">
					{ __( 'Preload Local Fonts', 'login-me-now' ) }
				</h3>
				<Switch
					checked={ enablePreloadLocalFontsStatus }
					onChange={ updatePreloadLocalFontsStatus }
					className={ classNames(
						enablePreloadLocalFontsStatus ? 'bg-lmn' : 'bg-slate-200',
						'group relative inline-flex h-4 w-9 flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-lmn focus:ring-offset-2'
					) }
				>
					<span aria-hidden="true" className="pointer-events-none absolute h-full w-full rounded-md bg-white" />
					<span
						aria-hidden="true"
						className={ classNames(
							enablePreloadLocalFontsStatus ? 'bg-lmn' : 'bg-gray-200',
							'pointer-events-none absolute mx-auto h-4 w-9 rounded-full transition-colors duration-200 ease-in-out'
						) }
					/>
					<span
						aria-hidden="true"
						className={ classNames(
							enablePreloadLocalFontsStatus ? 'translate-x-5' : 'translate-x-0',
							'toggle-bubble pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out'
						) }
					/>
				</Switch>
			</div>
			<p className="mt-2 w-9/12 text-sm text-slate-500 tablet:w-full">
				{ __( 'This option will load the font files right away on page load. Preloading Local Fonts can speeds up your website even further.', 'login-me-now' ) }
			</p>
		</section>
	);
};

export default PreloadLocalFonts;
