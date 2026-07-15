# Repository Guidelines

## Project Structure & Module Organization

This is a WordPress/WooCommerce project for the custom Versao Ltda theme. Most project work belongs in `wp-content/themes/versao-ltda-theme/`; treat `wp-admin/`, `wp-includes/`, bundled default themes, and third-party plugins as vendor code unless an update explicitly requires changes there.

- `wp-content/themes/versao-ltda-theme/`: custom theme templates, PHP includes, CSS, JavaScript, fonts, icons, and images.
- `wp-content/themes/versao-ltda-theme/inc/`: theme setup, enqueue logic, menus, widgets, helpers, and WooCommerce integration.
- `wp-content/themes/versao-ltda-theme/template-parts/`: reusable template fragments, including home page sections.
- `wp-content/themes/versao-ltda-theme/assets/css/`: modular CSS files; keep `layout.css` organized by its existing section comments.
- `design/`: reference layouts from the designer.
- `database/versao-ltda-dev.sql`: development database export.

## Build, Test, and Development Commands

There is no package manager or build step in this repo. Run the site through a local WordPress stack using PHP 8.2 and MySQL.

- Create a local database named `versaoltda`.
- Import the development dump: `mysql -u <user> -p versaoltda < database/versao-ltda-dev.sql`.
- Configure `wp-config.php` for local credentials.
- Serve with a WordPress-compatible local stack such as Apache/Nginx, LocalWP, XAMPP, or WP-CLI `wp server` if available.

## Coding Style & Naming Conventions

Follow the existing WordPress PHP style: tabs for indentation, spaced control structures, early `ABSPATH` guards, and snake_case variables/functions such as `$versao_ltda_file`. Keep PHP responsibilities split into `inc/*.php` files and load them from `functions.php`.

For CSS, use modular files and BEM-like class names already present, such as `.site-header__inner` and `.container--narrow`. Do not edit unrelated sections of `layout.css`; preserve the section headers and comments. JavaScript should remain vanilla ES6 unless a dependency is already required.

## Testing Guidelines

No automated test suite is currently configured. Before opening a PR, manually verify affected pages in WordPress, especially the priority flows: Header, Home, Product, Shop, Cart, Checkout, and My Account. Check responsive layouts against the files in `design/` and confirm WooCommerce pages still render without PHP warnings.

## Commit & Pull Request Guidelines

Recent commits use short, descriptive messages in Portuguese or English, for example `Ajuste header` or `Refactor layout.css structure and organize sections`. Keep commits focused on one change.

Create work from `develop` using `feature/nome-da-feature`. Pull requests should describe the change, list manual verification performed, link any related issue/task, and include screenshots for visual changes.

## Security & Configuration Tips

Do not commit production credentials, database passwords, or customer/order data. Keep local-only changes in `wp-config.php` out of feature work unless the configuration change is intentional and documented.
