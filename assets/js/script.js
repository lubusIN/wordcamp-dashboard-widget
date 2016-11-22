jQuery(document).ready(function($) {
	// Initialize Datatables
	$.fn.dataTable.ext.classes.sPageButton = 'button button-primary';
    $('#lubus-wordcamp').DataTable( {
        "order": [[ 1, "asc" ]],
        "pagingType": "simple"
    } );
} );