/**
 * Retrieve the admin bar data for the current context.
 *
 * @param {string} siteurl Root URL for the current site.
 * @param {object} context Current browsing context.
 * @return {Promise} Promise, which when fulfilled, resolves with markup, scripts, and styles.
 */
const getAdminBar = async ( siteurl, context ) => {
	const ajaxParams = new URLSearchParams( { ...context, action: 'admin_bar_render' } );
	const response = await fetch(
		`${siteurl}/wp-admin/admin-ajax.php?${ajaxParams}`,
		{ credentials: 'include' }
	);

	return response.json();
};

/**
 * Render the admin bar for the current page.
 *
 * @param adminBarData object containing markup, scripts, and styles.
 * @return void
 */
const render = adminBarData => {
	const { markup, scripts, styles } = adminBarData;
	const template = document.createElement( 'template' );
	template.innerHTML = markup + styles + scripts;

	document.body.append( template.content.cloneNode( true ) );
	document.body.classList.add( 'admin-bar' );
};

/**
 * Refresh the contents of the admin bar, if already rendered to the page.
 *
 * @param adminBarData object containing markup, scripts, and styles.
 * @return void
 */
const refresh = adminBarData => {
	const adminBar = document.getElementById( 'wpadminbar' );

	if ( adminBar ) {
		adminBar.outerHTML = adminBarData.markup;
	}
};
