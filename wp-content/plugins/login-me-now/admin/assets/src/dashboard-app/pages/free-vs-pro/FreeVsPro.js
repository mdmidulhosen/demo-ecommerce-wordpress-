import { __ } from "@wordpress/i18n";
import { features } from "./data";
import Login_Me_Now_Admin_Icons from '@Common/block-icons';
import apiFetch from '@wordpress/api-fetch';

const FreeVsPro = () => {
	const checkStatus = (value) => {
		if (value === "yes") {
			return Login_Me_Now_Admin_Icons['check'];
		} else if (value === "no") {
			return Login_Me_Now_Admin_Icons['xclose'];
		} else {
			return value;
		}
	};

	const getLoginMeNowProTitle = () => {
		return lmn_admin.pro_installed_status ? __('Activate Now') : __('Upgrade Now');
	}

	const redirectUpgradeLoginMeNowProPurchase = () => {
		window.open(
			lmn_admin.upgrade_url,
			'_blank'
		);
	}

	const onGetLoginMeNowPro = (e) => {
		if (lmn_admin.pro_installed_status) {
			const formData = new window.FormData();
			formData.append('action', 'login_me_now_recommended_plugin_activate');
			formData.append('security', lmn_admin.plugin_manager_nonce);
			formData.append('init', 'login-me-now-addon/login-me-now-addon.php');
			e.target.innerText = lmn_admin.plugin_activating_text;

			apiFetch({
				url: lmn_admin.ajax_url,
				method: 'POST',
				body: formData,
			}).then((data) => {
				if (data.success) {
					window.open(lmn_admin.login_me_now_base_url, '_self');
				}
			});
		} else {
			redirectUpgradeLoginMeNowProPurchase();
		}
	};

	return (
		<main className="py-[2.43rem]">
			<div className="max-w-3xl mx-auto px-6 lg:max-w-7xl">
				<h1 className="sr-only"> {__("Login Me Now Free Vs Pro", "login-me-now")} </h1>
				<div className="flex flex-row justify-between items-center">
					<h2 className="text-lg sm:text-2xl font-semibold capitalize">
						{__('Login Me Now Free vs Pro', 'login-me-now')}
					</h2>
					<button onClick={onGetLoginMeNowPro} className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none">
						{getLoginMeNowProTitle()}
					</button>
				</div>
				{ /* Free VS Pro Data Table */}
				<div className="mt-8 flex flex-col">
					<div className="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
						<div className="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
							<div className="overflow-hidden shadow-overlay-light md:rounded-lg">
								<table className="min-w-full divide-y divide-slate-200">
									<thead className="bg-white">
										<tr>
											<th
												scope="col"
												className="py-3.5 pl-4 pr-3 text-left text-base font-medium text-slate-800 sm:pl-8"
											>
												{__("Features", "login-me-now")}
											</th>
											<th
												scope="col"
												className="px-3 py-3.5 text-center text-base font-medium text-slate-800"
											>
												{__("Free", "login-me-now")}
											</th>
											<th
												scope="col"
												className="px-3 py-3.5 text-center text-base font-medium text-slate-800"
											>
												{__("Pro", "login-me-now")}
											</th>
										</tr>
									</thead>
									<tbody className="divide-y divide-slate-200 bg-white">
										{features.map((feature, key) => (
											<tr key={key}>
												<td className="whitespace-nowrap py-4 pl-4 pr-3 text-base text-slate-600 sm:pl-8">
													{feature.name}
												</td>
												<td className="whitespace-nowrap capitalize px-3 py-4 text-base text-center text-slate-600">
													<div className="flex justify-center font-medium">
														{checkStatus(feature.free)}
													</div>
												</td>
												<td className="whitespace-nowrap capitalize px-3 py-4 text-base text-center text-slate-600">
													<div className="flex justify-center font-medium">
														{checkStatus(feature.pro)}
													</div>
												</td>
											</tr>
										))}
									</tbody>
								</table>
								<div className="flex items-center justify-center text-lmn hover:text-lmn-hover text-base font-medium text-center bg-white py-4 border-t border-t-slate-200">
									<button onClick={redirectUpgradeLoginMeNowProPurchase} className="flex items-center justify-center">
										<span className="mr-2">
											{__(
												"Upcoming features",
												"login-me-now"
											)}
										</span>
										{Login_Me_Now_Admin_Icons['redirect']}
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<section className="mt-6 py-10 flex flex-col bg-slate-200 items-center justify-center shadow-overlay-light rounded-md">
					<div className="mb-3">
						<span className="ml-1 sm:ml-2 text-[0.625rem] leading-[1rem] font-medium text-white border border-slate-800 bg-slate-800 rounded-[0.1875rem] relative inline-flex flex-shrink-0 py-[0rem] px-1.5">
							{__("PRO", "login-me-now")}
						</span>
					</div>
					<h4 className="text-2xl font-semibold text-slate-800 mb-3">
						{__("Do More with Login Me Now Pro", "login-me-now")}
					</h4>
					<div className="max-w-2xl text-center text-base text-slate-600 mb-7">
						{__("Get access to powerful features for painless WordPress designing, without the high costs. With all the time you will save, it’s a product that pays for itself!", "login-me-now")}
					</div>
					<button onClick={onGetLoginMeNowPro} className="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none">
						{getLoginMeNowProTitle()}
					</button>
				</section>
			</div>
		</main>
	);
};

export default FreeVsPro;
