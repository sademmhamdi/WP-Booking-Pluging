# 🤝 Contributing to Simple Date Booking Plugin

Welcome! We're thrilled that you're interested in contributing to the Simple Date Booking WordPress plugin. This document provides guidelines and information for contributors.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [How to Contribute](#how-to-contribute)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Reporting Issues](#reporting-issues)
- [Documentation](#documentation)

## 🤝 Code of Conduct

This project follows a code of conduct to ensure a welcoming environment for all contributors. By participating, you agree to:

- Be respectful and inclusive
- Focus on constructive feedback
- Accept responsibility for mistakes
- Show empathy towards other contributors
- Help create a positive community

## 🚀 Getting Started

### Prerequisites

Before you begin, ensure you have:

- **WordPress**: Version 5.0 or higher
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.6 or higher
- **Git**: Version control system
- **Composer**: PHP dependency manager (optional)
- **Node.js**: For asset compilation (optional)

### Quick Setup

1. **Fork the Repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/WP-Booking-Pluging.git
   cd WP-Booking-Pluging
   ```

2. **Set Up Development Environment**
   ```bash
   # If using Composer
   composer install

   # If using Node.js for assets
   npm install
   npm run build
   ```

3. **Install WordPress**
   - Set up a local WordPress development environment
   - Install and activate the plugin
   - Create test data for development

## 🛠️ Development Setup

### Local Development Environment

We recommend using one of these setups:

- **Local by Flywheel**: User-friendly WordPress development
- **XAMPP/MAMP**: Traditional local server setup
- **Docker**: Containerized development environment
- **WP-CLI**: Command-line WordPress management

### Plugin Structure

```
simple-date-booking/
├── assets/                 # CSS, JS, and other assets
│   ├── css/
│   ├── js/
│   └── images/
├── includes/              # PHP classes and core functionality
│   ├── Admin/            # Admin-related classes
│   ├── Api/              # REST API endpoints
│   └── Frontend/         # Frontend functionality
├── languages/            # Translation files
├── templates/            # Template files (if any)
├── simple-date-booking.php  # Main plugin file
├── README.md             # Documentation
├── CONTRIBUTING.md       # This file
└── LICENSE              # License information
```

## 💡 How to Contribute

### Types of Contributions

We welcome various types of contributions:

- 🐛 **Bug Fixes**: Fix existing issues
- ✨ **Features**: Add new functionality
- 📚 **Documentation**: Improve docs and tutorials
- 🎨 **UI/UX**: Enhance user interface and experience
- 🧪 **Testing**: Write and improve tests
- 🌐 **Translations**: Add language support
- 📦 **Maintenance**: Code refactoring and cleanup

### Finding Issues to Work On

1. **Check GitHub Issues**: Look for open issues labeled `good first issue` or `help wanted`
2. **Bug Reports**: Issues labeled `bug` are great for beginners
3. **Feature Requests**: Check for `enhancement` labeled issues
4. **Documentation**: Look for `documentation` labeled issues

## 🔄 Development Workflow

### 1. Choose an Issue

- Find an issue you'd like to work on
- Comment on the issue to indicate you're working on it
- Wait for maintainer approval if it's a complex change

### 2. Create a Branch

```bash
# Create and switch to a new branch
git checkout -b feature/your-feature-name
# or
git checkout -b fix/issue-number-description
```

### 3. Make Changes

- Write clean, well-documented code
- Follow WordPress coding standards
- Test your changes thoroughly
- Update documentation if needed

### 4. Test Your Changes

```bash
# Run PHP syntax check
php -l includes/your-file.php

# Test in WordPress environment
# - Activate plugin
# - Test functionality
# - Check for errors in debug.log
```

### 5. Commit Your Changes

```bash
# Stage your changes
git add .

# Commit with descriptive message
git commit -m "feat: add new feature description

- What was changed
- Why it was changed
- Any breaking changes"
```

### 6. Push and Create Pull Request

```bash
# Push your branch
git push origin feature/your-feature-name

# Create pull request on GitHub
```

## 📝 Coding Standards

### PHP Standards

Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/):

```php
// ✅ Good: Proper indentation and spacing
function my_function( $param1, $param2 = null ) {
    if ( $param1 && $param2 ) {
        return $param1 + $param2;
    }
    return false;
}

// ❌ Bad: Inconsistent spacing
function my_function($param1,$param2=null){
if($param1&&$param2){
return $param1+$param2;
}
return false;
}
```

### JavaScript Standards

```javascript
// ✅ Good: Consistent formatting
function handleSubmit( event ) {
    event.preventDefault();

    const formData = new FormData( this );
    // ... rest of function
}

// ❌ Bad: Inconsistent formatting
function handleSubmit(event){
event.preventDefault()
const formData=new FormData(this)
// ... rest of function
}
```

### CSS Standards

```css
/* ✅ Good: Organized and commented */
.booking-form {
    max-width: 500px;
    margin: 0 auto;
}

.booking-form .form-group {
    margin-bottom: 20px;
}

/* ❌ Bad: Unorganized */
.booking-form{max-width:500px;margin:0 auto}.booking-form .form-group{margin-bottom:20px}
```

### Commit Message Standards

Follow conventional commit format:

```bash
# Feature commits
feat: add new booking validation

# Bug fixes
fix: resolve date picker issue in mobile browsers

# Documentation
docs: update installation instructions

# Style changes
style: format code according to WordPress standards

# Refactoring
refactor: simplify booking approval logic

# Performance improvements
perf: optimize database queries for large datasets

# Tests
test: add unit tests for booking API
```

## 🧪 Testing

### Manual Testing Checklist

Before submitting your changes, test:

- [ ] Plugin activates without errors
- [ ] Frontend form displays correctly
- [ ] Form submission works (both AJAX and fallback)
- [ ] Admin dashboard loads properly
- [ ] List view displays bookings
- [ ] Kanban view functions correctly
- [ ] Approve/decline actions work
- [ ] Email notifications are sent
- [ ] Responsive design works on mobile
- [ ] No JavaScript errors in console
- [ ] No PHP errors in debug.log

### Automated Testing

```bash
# Run PHP unit tests (if available)
composer test

# Run JavaScript tests (if available)
npm test
```

### Cross-Browser Testing

Test in these browsers:
- Chrome/Chromium
- Firefox
- Safari
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📤 Submitting Changes

### Pull Request Process

1. **Create Pull Request**
   - Use a clear, descriptive title
   - Reference the issue number (e.g., "Fixes #123")
   - Provide a detailed description

2. **Pull Request Template**
   ```markdown
   ## Description
   Brief description of the changes

   ## Type of Change
   - [ ] Bug fix
   - [ ] New feature
   - [ ] Breaking change
   - [ ] Documentation update

   ## Testing
   - [ ] Tested in WordPress 5.0+
   - [ ] Tested in PHP 7.4+
   - [ ] Manual testing completed
   - [ ] No breaking changes

   ## Screenshots (if applicable)
   Add screenshots of UI changes

   ## Checklist
   - [ ] Code follows WordPress standards
   - [ ] Documentation updated
   - [ ] Tests added/updated
   - [ ] No linting errors
   ```

3. **Review Process**
   - Maintainers will review your PR
   - Address any feedback or requested changes
   - Once approved, your PR will be merged

### What Happens Next

- Your contribution will be reviewed by maintainers
- Feedback will be provided within a few days
- Approved changes will be merged
- You'll be credited as a contributor

## 🐛 Reporting Issues

### Bug Reports

When reporting bugs, please include:

1. **Clear Title**: Summarize the issue
2. **Description**: Detailed explanation of the problem
3. **Steps to Reproduce**:
   ```markdown
   1. Go to '...'
   2. Click on '...'
   3. Scroll down to '...'
   4. See error
   ```
4. **Expected Behavior**: What should happen
5. **Actual Behavior**: What actually happens
6. **Environment**:
   - WordPress version
   - PHP version
   - Browser and version
   - Plugin version
   - Other relevant plugins/themes

### Feature Requests

For new features, please include:

- **Feature Description**: What the feature should do
- **Use Case**: Why this feature would be useful
- **Implementation Ideas**: How you think it could be implemented
- **Mockups/Screenshots**: Visual representation (if applicable)

## 📚 Documentation

### Updating Documentation

- Keep README.md up to date
- Add inline code comments
- Update this contributing guide as needed
- Document new features and API changes

### Documentation Standards

- Use clear, concise language
- Include code examples where helpful
- Provide step-by-step instructions
- Test all documentation for accuracy

## 🎉 Recognition

Contributors will be:
- Listed in the repository contributors
- Credited in release notes
- Recognized in the project's hall of fame (future feature)

## 📞 Getting Help

If you need help:

1. **Check Existing Issues**: Search for similar questions
2. **Read Documentation**: Review README and this guide
3. **Ask Questions**: Create a new issue with `question` label
4. **Community**: Join GitHub Discussions

## 📋 Additional Resources

- [WordPress Developer Resources](https://developer.wordpress.org/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [PHP Standards](https://www.php-fig.org/psr/)
- [GitHub Flow](https://guides.github.com/introduction/flow/)

---

Thank you for contributing to Simple Date Booking! Your efforts help make this plugin better for everyone in the WordPress community. 🚀