const showWidth = () => {
	let windowWidth = window.innerWidth;
	let breakPoint  = 'Undefined';

	W83ShowCurrentWidth.breakpoints_definition.split('\n').forEach( line => {
		items = line.trim().split( /\s*,\s*/ );
		if ( items[0] <= windowWidth && windowWidth < items[1] ) {
			breakPointShort = items[2];
			breakPointLong  = items[3];
		}
	});

	document.querySelector( '#wp-admin-bar-w83-show-current-width .ab-icon .width' ).textContent = windowWidth;
	document.querySelector( '#wp-admin-bar-w83-show-current-width .ab-label .width' ).textContent = windowWidth;
	if( 1 == W83ShowCurrentWidth.breakpoints_show ) {
		document.querySelector( '#wp-admin-bar-w83-show-current-width .breakpoint' ).textContent = breakPointShort;
		document.querySelector( '#wp-admin-bar-w83-show-current-width-breakpoint .breakpoint' ).textContent = breakPointLong;
	}
};

const whenResized = () => { 
    let timeoutID = 0;
    let delay = 500;
    window.addEventListener( 'resize', () => {
        clearTimeout( timeoutID );
        timeoutID = setTimeout( () => {
			showWidth();
        }, delay );
    }, false );
};

window.addEventListener( 'load', showWidth );
window.addEventListener( 'load', whenResized );
