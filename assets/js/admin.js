jQuery(document).ready(function($) {
    $('.row-actions a').on('click', function(e) {
        if ($(this).text() === 'Delete') {
            return confirm('Are you sure you want to delete this booking?');
        }
    });
});
