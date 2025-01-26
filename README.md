<div align="center">
  <img src="public/logo.svg" alt="App Logo">
  <h2>App Starter Kit</h2>
</div>

> [!TIP]
> Clone this repository and run `bash install.sh` to set up your application effortlessly.

## Overview

The App Starter Kit is designed as a robust foundation for building web applications. It includes essential features such as authentication and a pre-configured dashboard to help you start coding quickly.

## Features

- **Authentication**: Secure and reliable user authentication.
- **Dashboard Layout**: A pre-built, customizable dashboard.
- **[Preline](https://preline.co)**: A modern UI component library.
- **Tailwind CSS**: A utility-first CSS framework.
- **Inertia.js + Vue 3 + TypeScript**: Seamless front-end integration with modern tools.
- **Multi-Language Support**: Supports English and Arabic out of the box.

## Installation

This project comes with a custom installation script to simplify the setup process. The `install.sh` script automates the following steps:

1. Install required Composer packages.
2. Execute the custom `app:install` Artisan command.
3. Remove any existing Git repository.
4. Initialize a new Git repository.
5. Install Node.js dependencies.
6. Configure the `.env` file.
7. Reload environment variables.
8. Generate the application key.
9. Run database migrations.
10. Seed the database.
11. Create admin credentials.
12. Update project settings (name and URL).
13. Update specific environment variables.
14. Clean up installation-related files.
15. Run post-setup tasks (e.g., linking storage, clearing cache).

### Steps to Install

1. Clone the repository:
   
```bash
git clone https://github.com/michaelnabil230/laravel-starter-kit.git
```

2. Navigate to the project directory:

```bash
cd laravel-starter-kit
```

3. Run the installation script:

```bash
bash install.sh
```

## Testing

```bash
composer test
```

## Support

[![Image for sponsor](https://raw.githubusercontent.com/michaelnabil230/michaelnabil230/main/.assets/sponsors.png)](https://github.com/sponsors/michaelnabil230)

Or

[![Ko-fi](https://raw.githubusercontent.com/michaelnabil230/michaelnabil230/main/.assets/ko-fi.png)](https://ko-fi.com/michaelnabil230)
[![Buymeacoffee](https://raw.githubusercontent.com/michaelnabil230/michaelnabil230/main/.assets/buymeacoffee.png)](https://www.buymeacoffee.com/michaelnabil230)
[![Paypal](https://raw.githubusercontent.com/michaelnabil230/michaelnabil230/main/.assets/paypal.png)](https://www.paypal.com/paypalme/MichaelNabil23)

## Credits

- [Michael Nabil](https://github.com/michaelnabil230)
- [All Contributors](../../contributors)

## License

The App starter kit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).