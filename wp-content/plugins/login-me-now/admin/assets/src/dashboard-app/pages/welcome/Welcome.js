import { __ } from "@wordpress/i18n";
import { useLocation } from "react-router-dom";
import ProModules from "@DashboardApp/pages/welcome/ProModules";
import apiFetch from '@wordpress/api-fetch';
import VideoPopup from "./VideoPopup";
import { useState } from "react";
import { useDispatch } from 'react-redux';
import MagicLinkPopup from "./MagicLinkPopup";

const classNames = (...classes) => classes.filter(Boolean).join(" ");

const Welcome = () => {
	const query = new URLSearchParams(useLocation()?.search);
	const dispatch = useDispatch();

	const allowAutoPlay =
		"1" === query.get("login-me-now-activation-redirect") ? 1 : 0;

	const onGenerateOnetimeToken = (e) => {
		e.preventDefault();
		e.stopPropagation();

		const formData = new window.FormData();
		formData.append('action', 'login_me_now_generate_onetime_link');
		formData.append('security', lmn_admin.generate_token_nonce);

		apiFetch({
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		}).then((data) => {
			if (data.success) {
				dispatch( {type: 'GENERATE_MAGIC_LINK_POPUP', payload: { ... data.data }} );
			}
		});
	};

	const onGenerateReusableToken = (e) => {
		e.preventDefault();
		e.stopPropagation();

		const formData = new window.FormData();
		formData.append('action', 'login_me_now_generate_extension_token');
		formData.append('security', lmn_admin.generate_token_nonce);

		apiFetch({
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		}).then((data) => {
			if (data.success) {
				dispatch( {type: 'GENERATE_MAGIC_LINK_POPUP', payload: { ... data.data }} );
			}
		});
	};

	const getLoginMeNowProTitle = () => {
		// return lmn_admin.pro_installed_status ? __('Activate Now', 'login-me-now') : __('Upgrade Now', 'login-me-now');
	}

	const onGetLoginMeNowPro = (e) => {
		e.preventDefault();
		e.stopPropagation();

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
			window.open(
				lmn_admin.upgrade_url,
				'_blank'
			);
		}
	};

	const [videoPopup, setVideoPopup] = useState(false);

	const toggleVideoPopup = () => {
		setVideoPopup(!videoPopup);
	};

	return (<>
		<MagicLinkPopup/>
		<p>
		</p>
		<main className="py-[2.43rem]">
			<div className="max-w-3xl mx-auto px-6 lg:max-w-7xl">
				<h1 className="sr-only"> Login Me Now </h1>

				{/* Banner section */}
				{lmn_admin.show_self_branding && (
					<div className="grid grid-cols-1 gap-4 items-start lg:grid-cols-5 lg:gap-0 xl:gap-0 rounded-md bg-white overflow-hidden shadow-sm px-8 py-8">
						<div className="grid grid-cols-1 gap-4 lg:col-span-3 h-full md:mr-[5.25rem]">
							<section aria-labelledby="section-1-title h-full">
								<h2 className="sr-only" id="section-1-title">
									Welcome Banner
								</h2>
								<div className="flex flex-col justify-center h-full">
									<div className="">
										<p className="pb-4 font-medium text-base text-slate-800">
											{__("Hello ", "login-me-now") +
												lmn_admin.current_user +
												","}
										</p>
										<div className="flex">
											<h2 className="text-slate-800 text-[2rem] leading-10 pb-3 font-semibold text-left">
												{__(
													`Share your dashboard access securely`,
													"login-me-now"
												)}
											</h2>
											{/* {lmn_admin.pro_available ? (
												<span className="ml-2 h-full inline-flex leading-[1rem] font-medium flex-shrink-0 py-[0rem] px-1.5 text-[0.625rem] text-white bg-slate-800 border border-slate-800 rounded-[0.1875rem] -tablet:mt:10">
													{__('PRO', 'login-me-now')}
												</span>)
												:
												(<span className="ml-2 h-full inline-flex leading-[1rem] flex-shrink-0 py-[0rem] px-1.5 text-[0.625rem] text-lmn bg-blue-50 border border-blue-50 rounded-[0.1875rem] font-medium -tablet:mt:10">
													{__('FREE', 'login-me-now')}
												</span>)
											} */}
										</div>

										<p className="text-base leading-[1.625rem] text-slate-600 pb-7">
											{__(
												`With the self-expiring automatic login link, granting temporary access WordPress site has never been more secure and convenient - no passwords needed, just generate the link!`,
												"login-me-now"
											)}
										</p>

										<span className="relative z-0 inline-flex flex-col sm:flex-row justify-start w-full">
											<button
												type="button"
												className="sm:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mr-4 mb-2 sm:mb-0"
												onClick={onGenerateOnetimeToken}
											>
												{__(
													"Generate Onetime Access",
													"login-me-now"
												)}
											</button>
											<button
												type="button"
												className="sm:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mr-4 mb-2 sm:mb-0"
												onClick={onGenerateReusableToken}
											>
												{__(
													"Generate Extension Token",
													"login-me-now"
												)}
											</button>
										</span>
									</div>
								</div>
							</section>
						</div>
					</div>
				)}

				{/* Left Column */}
				<div className="grid grid-cols-1 gap-[32px] items-start lg:grid-cols-3 lg:gap-[32px] xl:gap-[32px] mt-[32px]">
					{/* Left column */}
					<div
						className={classNames(
							lmn_admin.show_self_branding
								? "lg:col-span-2"
								: "lg:col-span-3",
							"grid grid-cols-1 gap-[32px]"
						)}
					>

						<section aria-labelledby="section-1-title h-full">
							<h2 className="sr-only" id="section-1-title">
								What’s coming next?
							</h2>
							<div className="p-[2rem] rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
								<div className="relative w-full flex flex-col sm:flex-row sm:items-center sm:justify-between">
									<span className="font-semibold text-xl leading-6 text-slate-800 mb-4 sm:mb-0">
										 {__(`What’s coming next?`, "login-me-now")}
									</span>
									{!lmn_admin.pro_available && (
										<a
											className="lg:flex-shrink-0 text-sm font-medium text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href={lmn_admin.upgrade_url}
											target="_blank"
											rel="noreferrer"
											onClick={onGetLoginMeNowPro}
										>
											{" "}
											{getLoginMeNowProTitle()}{" "}
										</a>
									)}
								</div>

								{wp.hooks.applyFilters(
									`login_me_now_dashboard.pro_extensions`,
									<ProModules />
								)}
							</div>
						</section>

						<section aria-labelledby="section-1-title h-full">
							<h2 className="sr-only" id="section-1-title">
								Your License
							</h2>
							<div className="ast-welcome-screen rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
								{wp.hooks.applyFilters(
									`login_me_now_dashboard.welcome_screen_after_integrations`,
									<span />
								)}
							</div>
						</section>
					</div>

					{/* Right Column */}
					{lmn_admin.show_self_branding && (
						<div className="grid grid-cols-1 gap-[32px]">
							<section aria-labelledby="section-2-title">
								<h2 className="sr-only" id="section-2-title">
									Need Support
								</h2>
								<div className="relative box-border border border-sky-500 rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
									<div className="p-6">
										<h3 className="relative flex items-center text-slate-800 text-base font-semibold pb-2">
											<span className="flex-1">
												{__(
													"Need Support?",
													"login-me-now"
												)}
											</span>
										</h3>
										<p className="text-slate-500 text-sm pb-5 pr-12">
											{__(
												"Whether you need help or have a new feature request, please create a topic in the support forum on WordPress.org.",
												"login-me-now"
											)}
										</p>
										<a
											className="text-sm text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href="https://wordpress.org/support/plugin/login-me-now/"
											target="_blank"
											rel="noreferrer"
										>
											{__("Support Forum →", "login-me-now")}
										</a>
									</div>
								</div>
							</section>

							<section aria-labelledby="section-2-title">
								<h2 className="sr-only" id="section-2-title">
									Share your feedback
								</h2>
								<div className="box-border rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
									<div className="p-6">
										<h3 className="text-slate-800 text-base font-semibold pb-2">
											{__("Share Your Feedback", "login-me-now")}
										</h3>
										<p className="text-slate-500 text-sm pb-5">
											{__(
												`If you find this plugin useful, we would greatly appreciate it if you could a spare take time to leave	a 5-star review for our plugin on WordPress.org. Your feedback helps us to improve our product and allows others to benefit from the same value	that you have experienced.`,
												"login-me-now"
											)}
										</p>
										<a
											className="text-sm text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href="https://wordpress.org/support/plugin/login-me-now/reviews/?rate=5#new-post"
											target="_blank"
											rel="noreferrer"
										>
											{__("Submit a Review →", "login-me-now")}
										</a>
									</div>
								</div>
							</section>
						</div>
					)}
				</div>
			</div>
			<VideoPopup allowAutoPlay={allowAutoPlay} videoPopup={videoPopup} toggleVideoPopup={toggleVideoPopup} />
		</main>
	</>
	);
};

export default Welcome;
