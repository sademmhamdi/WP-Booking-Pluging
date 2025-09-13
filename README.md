# 📅 Simple Date Booking - WordPress Plugin

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)](https://wordpress.org/plugins/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![GitHub issues](https://img.shields.io/github/issues/sademmhamdi/WP-Booking-Pluging.svg)](https://github.com/sademmhamdi/WP-Booking-Pluging/issues)
[![GitHub stars](https://img.shields.io/github/stars/sademmhamdi/WP-Booking-Pluging.svg)](https://github.com/sademmhamdi/WP-Booking-Pluging/stargazers)

> A modern, professional WordPress plugin for date booking with admin approval system, featuring a beautiful dashboard, AJAX-powered forms, and comprehensive management tools.

## ✨ Features

### 🎨 **Modern User Interface**
- **Responsive Design**: Beautiful gradient-based UI that works on all devices
- **Interactive Dashboard**: Clickable statistics widgets with hover animations
- **Professional Styling**: Modern card-based layouts with smooth transitions

### 📊 **Admin Management**
- **Dashboard Overview**: Real-time statistics with clickable filtering
- **List View**: Comprehensive table with sorting, filtering, and bulk actions
- **Kanban Board**: Visual drag-and-drop style booking management
- **Modal Details**: Quick view booking details without page refresh

### 🔧 **Technical Features**
- **AJAX Form Submission**: No page reloads for better user experience
- **REST API Integration**: Modern API endpoints for extensibility
- **Security First**: Nonces, input sanitization, and permission checks
- **Email Notifications**: Automated emails for booking confirmations
- **Database Optimization**: Efficient queries with proper indexing

### 🚀 **Developer Friendly**
- **Hook System**: Extensive WordPress hooks for customization
- **Modular Architecture**: Clean, maintainable code structure
- **Comprehensive Documentation**: Well-documented code and API
- **Open Source**: GPL v2 license for community contributions

## 📋 Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Configuration](#configuration)
- [API Reference](#api-reference)
- [Contributing](#contributing)
- [Support](#support)
- [License](#license)

## 🚀 Installation

### Method 1: WordPress Admin (Recommended)
1. Download the plugin ZIP file from [GitHub Releases](https://github.com/sademmhamdi/WP-Booking-Pluging/releases)
2. Go to **WordPress Admin** → **Plugins** → **Add New**
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Download and unzip the plugin files
2. Upload the `simple-date-booking` folder to `/wp-content/plugins/`
3. Activate the plugin through **Plugins** menu in WordPress
4. The plugin will automatically create the necessary database table

### Requirements
- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher

## ⚡ Quick Start

1. **Activate the Plugin**
2. **Create a Booking Page**
   - Go to **Pages** → **Add New**
   - Title: "Book Your Date"
   - Content: `[simple_booking]`
   - Publish the page

3. **Configure Settings** (Optional)
   - Go to **Bookings** → **Settings**
   - Set admin email and success messages

4. **Start Managing Bookings**
   - Visit **Bookings** → **Dashboard** to see overview
   - Use **List View** or **Kanban View** for detailed management

## 📖 Usage

### Frontend Booking Form

Add the shortcode to any page or post:

```php
[simple_booking]
```

**Available Shortcode Parameters:**
- `style`: Custom CSS class for the form
- `redirect`: URL to redirect after successful booking

### Admin Dashboard

Navigate to **Bookings** in your WordPress admin:

#### 📊 Dashboard
- View booking statistics at a glance
- Click on any stat card to filter the list view
- Real-time counts for total, pending, approved, and declined bookings

#### 📝 List View
- Comprehensive table of all bookings
- Sort by date, name, status, or creation date
- Filter by status using the dropdown
- Bulk actions: approve, decline, or delete multiple bookings
- Individual actions for each booking

#### 📋 Kanban View
- Visual board showing bookings by status
- Drag-and-drop functionality (coming in future updates)
- Click any card to view detailed information
- Quick approve/decline actions

### Email Notifications

The plugin automatically sends emails for:
- **Booking Submission**: Confirmation to the user
- **Status Changes**: Approval/decline notifications
- **Admin Alerts**: New booking notifications

## 📸 Screenshots

### Frontend Booking Form
![Booking Form](https://via.placeholder.com/600x400/007cba/white?text=Booking+Form+Screenshot)

### Admin Dashboard
![Dashboard](https://via.placeholder.com/600x400/28a745/white?text=Dashboard+Screenshot)

### List View
![List View](https://via.placeholder.com/600x400/dc3545/white?text=List+View+Screenshot)

### Kanban View
![Kanban View](https://via.placeholder.com/600x400/ffc107/black?text=Kanban+View+Screenshot)

*Screenshots will be added to the repository soon*

## ⚙️ Configuration

### Plugin Settings

Access via **Bookings** → **Settings**:

- **Admin Email**: Email address for notifications
- **Success Message**: Custom message after booking submission
- **Date Restrictions**: Configure booking date limitations
- **Email Templates**: Customize notification emails

### Advanced Configuration

#### Custom CSS
Add custom styles to your theme's CSS file:

```css
.simple-booking-form {
    /* Your custom styles */
}

.booking-dashboard {
    /* Dashboard customizations */
}
```

#### Hooks and Filters

```php
// Filter before booking insertion
add_filter('simple_booking_before_insert', function($booking_data) {
    // Modify booking data before saving
    return $booking_data;
});

// Action after booking insertion
add_action('simple_booking_after_insert', function($booking_id) {
    // Custom logic after booking is saved
});

// Filter email subject
add_filter('simple_booking_email_subject', function($subject, $action) {
    return "Custom Subject for {$action}";
});
```

## 🔌 API Reference

### REST API Endpoints

#### Submit Booking
```
POST /wp-json/simple-booking/v1/submit
```

**Parameters:**
- `booking_date` (string): Date in YYYY-MM-DD format
- `name` (string): Full name of the person booking
- `email` (string): Valid email address
- `notes` (string): Optional additional notes

**Response:**
```json
{
    "success": true,
    "message": "Your booking request has been submitted successfully."
}
```

### AJAX Endpoints

#### Get Booking Details
```
GET /wp-admin/admin-ajax.php?action=get_booking_details&id={booking_id}
```

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### Ways to Contribute
- 🐛 **Report Bugs**: Use [GitHub Issues](https://github.com/sademmhamdi/WP-Booking-Pluging/issues)
- 💡 **Suggest Features**: Share your ideas for new features
- 🔧 **Code Contributions**: Submit pull requests with improvements
- 📖 **Documentation**: Help improve documentation and tutorials
- 🧪 **Testing**: Test the plugin and report issues

### Development Setup

1. **Fork the Repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/WP-Booking-Pluging.git
   cd WP-Booking-Pluging
   ```

2. **Install Dependencies**
   ```bash
   composer install  # If using Composer
   npm install       # If using Node.js for assets
   ```

3. **Development Workflow**
   ```bash
   git checkout -b feature/your-feature-name
   # Make your changes
   git commit -m "Add your feature description"
   git push origin feature/your-feature-name
   ```

4. **Submit Pull Request**
   - Ensure your code follows WordPress coding standards
   - Add tests for new features
   - Update documentation if needed

### Coding Standards
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use meaningful commit messages
- Add PHPDoc comments for functions and classes
- Test your changes thoroughly

## 🆘 Support

### Getting Help

1. **Documentation**: Check this README and inline code documentation
2. **GitHub Issues**: Search existing issues or create a new one
3. **Community**: Join discussions in GitHub Discussions

### Common Issues

**Plugin not activating?**
- Ensure PHP 7.4+ and WordPress 5.0+
- Check file permissions on the plugin directory
- Review WordPress error logs

**Styles not loading?**
- Clear browser cache and WordPress caches
- Check for plugin/theme conflicts
- The plugin uses inline CSS for maximum compatibility

**Emails not sending?**
- Verify WordPress email configuration
- Check spam folders
- Test with a different email service

## 📄 License

This project is licensed under the **GNU General Public License v2.0** - see the [LICENSE](LICENSE) file for details.

```
Copyright (C) 2025 Sadem Mhamdi

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🙏 Acknowledgments

- WordPress Community for the amazing platform
- Contributors who help improve this plugin
- Open source community for inspiration and tools

## 📞 Contact

**Sadem Mhamdi**
- GitHub: [@sademmhamdi](https://github.com/sademmhamdi)
- Project Repository: [WP-Booking-Pluging](https://github.com/sademmhamdi/WP-Booking-Pluging)

---

⭐ **If you find this plugin helpful, please give it a star on GitHub!**

*Made with ❤️ for the WordPress community*
