jQuery(document).ready(function ($) {
  var lubusWdwCountry = ''

// Initialize Datatables
  $.fn.dataTable.ext.classes.sPageButton = 'button button-primary'
  $('.lubus-wordcamp-table').DataTable({
    'order': [[ 1, 'asc' ]],
    'pagingType': 'simple',
    'oSearch': {'sSearch': lubusWdwCountry}
  })
})
