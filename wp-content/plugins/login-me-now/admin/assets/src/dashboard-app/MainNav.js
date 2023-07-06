import { Disclosure } from "@headlessui/react";
import { Link, useLocation } from "react-router-dom";
import { __ } from "@wordpress/i18n";
import { Fragment } from 'react';

export default function MainNav() {
	let navMenus = [];

	navMenus = [
		{
			name: __('Welcome', 'login-me-now'),
			slug: lmn_admin.home_slug,
			path: '',
		},
		{
			name: __('Settings', 'login-me-now'),
			slug: lmn_admin.home_slug,
			path: 'settings',
		},
		// {
		// 	name: __('Free vs Pro', 'login-me-now'),
		// 	slug: lmn_admin.home_slug,
		// 	path: 'free-vs-pro',
		// },
	];

	const onInstallExtension = () => {
		window.open(lmn_admin.extension_url, "_blank");
	};

	const menus = wp.hooks.applyFilters('login_me_now_dashboard.main_navigation', navMenus);

	const query = new URLSearchParams(useLocation()?.search);
	const activePage = query.get('page')
		? query.get('page')
		: lmn_admin.home_slug;
	const activePath = query.get("path") ? query.get("path") : "";

	return (
		<Disclosure as="nav" className="bg-white border-b border-slate-200">
			<div className="max-w-3xl mx-auto px-3 sm:px-6 lg:max-w-full">
				<div className="relative flex flex-col lg:flex-row justify-between h-28 lg:h-16 py-3 lg:py-0">
					<div className="lg:flex-1 flex items-start justify-center sm:items-stretch sm:justify-start">
						<a
							href={lmn_admin.login_me_now_base_url}
							className="flex-shrink-0 flex items-center"
						>
							<img
								className="lg:block h-[2.6rem] w-auto"
								src={lmn_admin.logo_url}
								alt="Workflow"
							/>
						</a>
						<div className="ml-4 sm:ml-8 sm:flex gap-y-4 gap-x-8">
							{menus.map((menu, key) => (
								<Fragment key={`?page=${menu.slug}&path=${menu.path}`}>
									<Link
										index={key}
										to={{
											pathname: 'admin.php',
											search: `?page=${menu.slug}${'' !== menu.path ? '&path=' + menu.path : ''
												}`,
										}
										}
										className={`${activePage === menu.slug && activePath === menu.path
											? 'mb-4 sm:mb-0 border-lmn text-lmn active:text-lmn focus:text-lmn focus-visible:text-lmn-hover hover:text-lmn-hover inline-flex items-center px-1 border-b-2 text-sm leading-[0.875rem] font-medium'
											: 'mb-4 sm:mb-0 border-transparent text-slate-600 active:text-lmn focus-visible:border-slate-300 focus-visible:text-slate-800 hover:border-slate-300 hover:text-slate-800 inline-flex items-center px-1 border-b-2 text-sm leading-[0.875rem] font-medium'
											}`}
									>
										{menu.name}
									</Link>
								</Fragment>
							))}
						</div>
					</div>
					{lmn_admin.show_self_branding && (
						<div className="absolute bottom-2 lg:inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto ml-auto lg:ml-6 sm:pr-0">
							<div className="flex items-center text-[0.625rem] sm:text-sm font-medium leading-[1.375rem] text-slate-400 mr-1 sm:mr-3 divide-x divide-slate-200 gap-3 pl-1 sm:pl-3">
							<div className="flex items-center">
									<button
										type="button"
										className="sm:inline-flex items-center px-4 py-2 border border-lmn-hover text-sm font-medium rounded-md shadow-sm text-black hover:text-white bg-white focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mr-4 mb-2 sm:mb-0"
										onClick={onInstallExtension}
									>
										{__(
											"Install Chrome Extension",
											"login-me-now"
										)}
										<img
											className="lg:block h-[25px] w-auto ml-[5px]"
											src="https://img.icons8.com/color/512/chrome.png"
											alt="Workflow"
										/>
									</button>
								</div>
								<div className="flex items-center pl-3">
									<span>{lmn_admin.version}</span>
									<span className="ml-1 sm:ml-2 text-[0.625rem] leading-[1rem] font-medium border border-slate-400 rounded-[0.1875rem] relative inline-flex flex-shrink-0 py-[0rem] px-1.5"> {__('BETA', 'login-me-now')} </span>
								</div>
								{lmn_admin.pro_available && (
									<div className="flex items-center pl-3">
										<span>{lmn_admin.plugin_ver}</span>
										<span className="ml-1 sm:ml-2 text-[0.625rem] leading-[1rem] font-medium text-white border border-slate-800 bg-slate-800 rounded-[0.1875rem] relative inline-flex flex-shrink-0 py-[0rem] px-1.5"> {__('PRO', 'login-me-now')} </span>
									</div>
								)}
								{wp.hooks.applyFilters(
									"login_me_now_dashboard.after_navigation_version",
									<span />
								)}
							</div>
						</div>
					)}
				</div>
			</div>
		</Disclosure>
	);
}