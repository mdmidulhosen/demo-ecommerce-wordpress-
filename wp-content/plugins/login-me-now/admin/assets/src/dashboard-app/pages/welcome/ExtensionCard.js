import { Switch } from '@headlessui/react'
import apiFetch from '@wordpress/api-fetch';
import ToolTip from './ToolTip';
import { useSelector, useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';

const classNames = (...classes) => (classes.filter(Boolean).join(' '));

const ExtensionCard = (props) => {

	const {
		title,
		desc,
		condition = true,
	} = props.moduleInfo;

	const slug = props.slug;

	const dispatch = useDispatch();

	const blocksStatuses = useSelector((state) => state.blocksStatuses);

	const moduleActivationStatus = (blocksStatuses && undefined !== blocksStatuses[slug] && slug == blocksStatuses[slug]) ? true : false;

	function getAddonTitleColorClass(condition) {
		if (condition || !lmn_admin.pro_available) {
			return 'text-slate-800';
		} else {
			return 'text-[#475569]';
		}
	}

	function getAddonLinksColorClass(condition, classes) {
		if (condition || !lmn_admin.pro_available) {
			return classes;
		} else {
			return 'text-[#CBD5E1] ' + classes;
		}
	}

	function getWrapperClass(condition, addon) {
		if (condition || 'white-label' === addon) {
			return 'ast-addon-active';
		} else {
			return 'ast-addon-inactive';
		}
	}

	return (
		<div
			key={slug}
			className={classNames(
				!lmn_admin.pro_available || !condition
					? classNames(!lmn_admin.pro_available ? 'group' : '', 'bg-slate-50')
					: `bg-white ${getWrapperClass(moduleActivationStatus, slug)} `,
				'box-border relative border rounded-md z-0 px-4 py-3 flex items-start gap-x-4 snap-start hover:shadow-md transition login-me-now-icon-transition'
			)}
		>

			<div className="flex-1 min-w-0 h-auto">
				<div className={`flex items-center text-base font-medium leading-7 ${getAddonTitleColorClass(condition)}`}>
					{title}
				</div>
				<p
					key={Math.floor(Math.random() * 100000)}
					className={classNames('focus-visible:text-slate-500 active:text-slate-500 focus:text-slate-400 text-slate-400 text-base')}
				>
					{desc}
				</p>
			</div>
		</div>
	);
};

export default ExtensionCard;
