jQuery(document).ready(function($) {
    $('#simple-booking-form').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            booking_date: $('#booking_date').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            notes: $('#notes').val()
        };
        fetch(simple_booking_ajax.rest_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': simple_booking_ajax.nonce
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            var messageClass = data.success ? 'success' : 'error';
            $('#simple-booking-message').removeClass('success error').addClass(messageClass).html('<p>' + data.message + '</p>');
            if (data.success) {
                $('#simple-booking-form')[0].reset();
            }
        })
        .catch(error => {
            $('#simple-booking-message').removeClass('success').addClass('error').html('<p>Error submitting form.</p>');
        });
    });
});
