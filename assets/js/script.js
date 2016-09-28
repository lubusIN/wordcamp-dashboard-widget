jQuery(document).ready(function($) {
	// Datatables
    $('#lubus-wordcamp').DataTable( {
        "order": [[ 1, "asc" ]],
        "pagingType": "simple"
    } );
} );