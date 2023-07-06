import { __ } from '@wordpress/i18n';
import ExtensionCard from '@DashboardApp/pages/welcome/ExtensionCard';

const ProModules = () => {

	const lmnExtensions = lmn_admin.extensions;
	const renderBlockCards = Object.entries( lmnExtensions ).map( ( [ index, module ] ) => {
		return ( <ExtensionCard key={ index } slug={ index } moduleInfo={ module } /> );
	} );

	return (
		<div className="grid grid-flow-row auto-rows-min grid-cols-1 gap-4 sm:grid-cols-2 pt-6">
			{ renderBlockCards }
		</div>
	);
};

export default ProModules;
