$(document).ready( function () {
    $.noConflict();
    $('.datatable').DataTable({
        "pageLength": 5,
        "lengthMenu": [[5, 10, 20, 30, 50, -1], [5, 10, 20, 30, 50, "All"]]
    });
});
