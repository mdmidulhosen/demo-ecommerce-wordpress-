import React, { useState } from 'react';
import { __ } from "@wordpress/i18n";
import apiFetch from '@wordpress/api-fetch';
import { useSelector, useDispatch } from "react-redux";

const FlushLocalFonts = () => {
	const dispatch = useDispatch();

	const enableLoadFontsLocally = useSelector( ( state ) => state.enableLoadFontsLocally );
	const [ regenerateAssetsState, setRegenerateAssetsState ] = useState( false );

	const regenerateLocalFontAssets = () => {
		setRegenerateAssetsState( 'loading' );
		const formData = new window.FormData();

		formData.append( 'action', 'login_me_now_regenerate_fonts_folder' );
		formData.append( 'security', lmn_admin.update_nonce );

		apiFetch( {
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		} ).then( ( data ) => {
			if ( data.success ) {
				dispatch( { type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __( 'Assets Regenerated!', 'login-me-now' ) } );
			} else {
				dispatch( { type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: data.data.message } );
			}
			setRegenerateAssetsState( false );
		} );
	};

	return (
		<section className={`login-me-now-dep-field-${enableLoadFontsLocally} block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className="w-full flex flex-col sm:flex-row justify-between">
				<div>
					<h3 className="p-0 text-xl leading-6 font-semibold text-slate-800">
						{__("Flush Local Fonts Cache", "login-me-now")}
					</h3>
					<p className="mt-2 text-sm text-slate-600">
						{ __( 'Click the button to reset the local fonts cache.', 'login-me-now' ) }
					</p>
				</div>
				<div className="mt-2 sm:mt-0">
					<button
						type="button"
						className="inline-flex px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none"
						onClick={ regenerateLocalFontAssets }
					>
						{__("Flush Local Font Files", "login-me-now")}
						{ 'loading' === regenerateAssetsState && (
							<svg className="animate-spin -mr-1 ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
								<path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
						) }
					</button>
				</div>
			</div>
		</section>
	);
};

export default FlushLocalFonts;
