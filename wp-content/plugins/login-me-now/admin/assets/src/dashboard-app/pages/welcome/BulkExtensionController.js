import { __ } from "@wordpress/i18n";
import apiFetch from '@wordpress/api-fetch';
import { useSelector, useDispatch } from 'react-redux';

const BulkExtensionController = () => {

	const dispatch = useDispatch();

	const blocksStatuses = useSelector((state) => state.blocksStatuses);

	const handleDeactivateAllTrigger = () => {

		const value = { ...blocksStatuses };

		for (const block in blocksStatuses) {
			value[block] = '';
		}

		// Update modules Statuses.
		dispatch({ type: 'UPDATE_BLOCK_STATUSES', payload: value });

		const formData = new window.FormData();

		formData.append('action', 'login_me_now_addon_bulk_deactivate_modules');
		formData.append('security', login_me_now_addon_admin.update_nonce);

		apiFetch({
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		}).then((data) => {
			if (data.success) {
				dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully Deactivated!', 'login-me-now') });

				const reFormData = new window.FormData();

				reFormData.append('action', 'login_me_now_refresh_assets_files');
				reFormData.append('security', login_me_now_addon_admin.update_nonce);

				apiFetch({
					url: lmn_admin.ajax_url,
					method: 'POST',
					body: reFormData,
				}).then((data) => {
					dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Cache Cleared!', 'login-me-now') });
					location.reload();
				});
			}
		});
	};

	const handleActivateAllTrigger = () => {

		const value = { ...blocksStatuses };

		for (const block in blocksStatuses) {
			value[block] = block;
		}

		// Update modukes Statuses as deactivate.
		dispatch({ type: 'UPDATE_BLOCK_STATUSES', payload: value });

		const formData = new window.FormData();

		formData.append('action', 'login_me_now_addon_bulk_activate_modules');
		formData.append('security', login_me_now_addon_admin.update_nonce);

		apiFetch({
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		}).then((data) => {
			if (data.success) {
				dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully Activated!', 'login-me-now') });

				const reFormData = new window.FormData();

				reFormData.append('action', 'login_me_now_refresh_assets_files');
				reFormData.append('security', login_me_now_addon_admin.update_nonce);

				apiFetch({
					url: lmn_admin.ajax_url,
					method: 'POST',
					body: reFormData,
				}).then((data) => {
					dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Cache Cleared!', 'login-me-now') });
					location.reload();
				});
			}
		});
	};

	return (
		<span className="z-0 flex shadow-sm rounded-[0.2rem] sm:justify-center w-fit sm:w-auto">
			<button
				type="button"
				className="focus:bg-indigo-50 focus:text-slate-500 focus-visible:text-lmn hover:bg-indigo-50 hover:text-lmn -ml-px relative inline-flex items-center px-4 py-2 border border-slate-200 bg-white text-sm font-medium text-slate-500 focus:z-10 focus:outline-none rounded-l-md transition"
				onClick={handleActivateAllTrigger}
			>
				{__('Activate all', 'login-me-now')}
			</button>
			<button
				type="button"
				className="focus:bg-indigo-50 focus:text-slate-500 focus-visible:text-lmn hover:bg-indigo-50 hover:text-lmn -ml-px relative inline-flex items-center px-4 py-2 border border-slate-200 bg-white text-sm font-medium text-slate-500 focus:z-10 focus:outline-none rounded-r-md transition"
				onClick={handleDeactivateAllTrigger}
			>
				{__('Deactivate all', 'login-me-now')}
			</button>
		</span>
	);
};

export default BulkExtensionController;
