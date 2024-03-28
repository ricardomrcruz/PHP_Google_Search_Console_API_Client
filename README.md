
# PHP Google Search Console API Client

Effortlessly integrate Google services with your PHP-based web environment using this script. Tailored for e-commerce and CMS platforms like PrestaShop and WordPress, it's flexible enough for any PHP setting. Seamlessly connect to Google APIs for enhanced functionality and data exchange.

## Getting Started

Follow these steps to set up the script in your PHP environment, including cloning the repository and installing dependencies.

### Prerequisites

Ensure PHP and Composer are installed on your server. These are vital for dependency management and script execution.

- PHP: [Installation Guide](https://www.php.net/manual/en/install.php)
- Composer: [Getting Started Guide](https://getcomposer.org/doc/00-intro.md)

### Cloning the Repository

Clone the repository to your machine or server:

```bash
git clone https://your-repository-url-here
cd your-repository-directory
```

### Installing Dependencies

After cloning, install PHP dependencies with:

```bash
composer require google/apiclient:^2.0
```

For specific dependency versions, use the project's `composer.lock`:

```bash
composer install
```

To update dependencies:

```bash
composer update
```

**Note:** Commit your `composer.lock` to version control to synchronize dependency versions across environments.

### Configuring Google Client

Set up your Google Client:

1. Visit the [Google Developers Console](https://console.developers.google.com/).
2. Create or select a project.
3. Under "Credentials", create your project's credentials (e.g., OAuth 2.0 Client IDs).
4. Enable necessary APIs via the "Library" page. Specifically, add the Google Search Console API.
5. Configure your OAuth 2.0 Client ID credentials with appropriate domains and redirect URLs.

### API Scopes

Include the correct scopes in your authentication flow as per the [Google Identity Platform documentation](https://developers.google.com/identity/protocols/oauth2/scopes).

### Compatibility

Though developed for a PrestaShop server, this script supports various PHP environments, including WordPress and other CMS platforms.

### Additional Information

For troubleshooting, contribution guidelines, or license information, feel free to add relevant sections.

---

#### Courtesy of [Strasbourg Web Solutions](https://strasbourgwebsolutions.fr) | Open Source 4 Life


