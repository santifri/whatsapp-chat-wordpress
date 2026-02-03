# Security Policy

## Supported Versions

Only the latest version is supported with security updates.

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Security Measures Implemented

This plugin implements WordPress security best practices:

### Input Validation & Sanitization
- All user inputs are validated and sanitized using WordPress core functions
- Phone numbers are validated with regex patterns
- All POST data is properly unslashed and sanitized before processing
- Account data undergoes multi-level validation

### Output Escaping
- All output in templates uses proper escaping functions (`esc_html`, `esc_attr`, `esc_url`)
- HTML content is sanitized with `wp_kses_post`

### CSRF Protection
- All forms include nonce verification
- AJAX requests require valid nonces
- All nonces are verified before processing data

### Access Control
- Admin pages require `manage_options` capability
- AJAX endpoints validate account IDs against saved accounts
- Attachment verification ensures images belong to current site

### Data Validation
- Breakpoints are validated to ensure proper hierarchy (tablet > mobile)
- Time formats are validated with regex
- Schedules are validated for logical consistency
- Attachment IDs are verified as valid images

## Reporting a Vulnerability

Please report security issues privately to: santiago.frias@santifrias.com

Do not open public issues for security vulnerabilities.

### What to Include

- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

### Response Time

We aim to respond to security reports within 48 hours and provide fixes within 7 days for critical vulnerabilities.
