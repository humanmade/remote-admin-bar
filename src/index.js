/**
 * Check if the current user has the wp_remote_admin_bar cookie set.
 *
 * On sites where the front end is served from the same domain and path as the
 * admin, this function can be used so that non-logged in users don't trigger
 * origin requests to the admin-ajax endpoint.
 *
 * @return {Boolean} Whether the current user appears to be logged into the admin.
 */
const isLoggedIn = () => document.cookie.match( /^(.*;)?\s*wp_remote_admin_bar\s*=\s*[^;]+(.*)?$/ );

/**
 * Retrieve the admin bar data for the current context.
 *
 * @param {string} siteurl Root URL for the current site.
 * @param {object} context Current browsing context.
 * @return {Promise} Promise, which when fulfilled, resolves with markup, scripts, and styles.
 */
const getAdminBar = ( siteurl, context ) => {
	const ajaxParams = new URLSearchParams( { ...context, action: 'admin_bar_render' } );
	return fetch(
		`${siteurl}/wp-admin/admin-ajax.php?${ajaxParams}`,
		{ credentials: 'include' }
	).then( response => response.json() );
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

export {
  isLoggedIn,
  getAdminBar,
  render,
  refresh,
};
