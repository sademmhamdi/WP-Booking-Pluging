# Simple Date Booking

A WordPress plugin for simple date booking with admin approval.

## Installation

1. Upload the `simple-date-booking` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. The plugin will create the necessary database table upon activation.

## Usage

### Frontend

Add the shortcode `[simple_booking]` to any page or post to display the booking form.

### Admin

Go to the "Bookings" menu in the WordPress admin dashboard to view and manage bookings.

- Approve or decline pending bookings.
- Delete bookings.
- Configure settings under "Bookings > Settings".

## Hooks and Filters

- `simple_booking_before_insert` - Filter before inserting a booking.
- `simple_booking_after_insert` - Action after inserting a booking.
- `simple_booking_email_subject` - Filter email subject.
- `simple_booking_email_message` - Filter email message.

## Example Usage

Create a page titled "Book a Date" and add the following content:

[simple_booking]

This will display the booking form on that page.
