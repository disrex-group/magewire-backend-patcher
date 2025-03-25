# Magewire Backend Patcher

The **Magewire Backend Patcher** is a Composer Plugin designed to seamlessly apply a patch to the Magewire module,
enabling full support for using Magewire in Magento 2's backend. This plugin automatically patches the Magewire module
on installation or update, ensuring that it implements backend-compatible functionality.

## Features

- Applies a patch to the `magewirephp/magewire` module during the `post-install` and `post-update` phases in Composer.
- Supports Magewire functionality in both Magento frontend and backend contexts.
- Fully automated: no manual patching is required.
- Safeguards against re-application of patches, warning users if the patch might already be applied.

## Requirements

- PHP: ^8.0
- Composer: ^2.0
- Magento: Compatible with Magento 2 environments.
- Magewire module: Required as the target for this patch (`magewirephp/magewire`).

## Installation

Add the Magewire Backend Patcher to your project via Composer:

```bash
composer require disrex/magewire-backend-patcher
```

The patch will be automatically applied during installation or update if the `magewirephp/magewire` module is present in
the project.

## How It Works

1. **Automatic Patch Application**  
   On running Composer commands like `composer install` or `composer update`, this plugin automatically looks for the
   `Magewire` module at `vendor/magewirephp/magewire`, and applies a pre-defined backend patch file to enable backend
   support.

2. **Patch File Location**  
   The patch file is located in the `vendor/disrex/magewire-backend-patcher/patches` directory, named
   `magewire-backend.patch`.

3. **Validation**  
   Before applying the patch, the plugin checks:
    - If the target directory (`vendor/magewirephp/magewire`) exists.
    - If the patch file is available.
    - Whether the patch is already potentially applied, notifying the user accordingly.

## Usage

After installation, the patching process is entirely automated. No further configuration is required.

If you need to check the status of the patch, you can compare the contents of the `magewirephp/magewire` module with the
patch file. Alternatively, reinstalling or updating the module with Composer will trigger the patch process again.

### Manual Trigger (Optional)

If for any reason you need to manually apply the patch after installation, you can do so by running the following
Composer script:

```bash
composer run-script post-install-cmd
```

This will reapply the patch and display whether it was successful.

## Limitations

- This plugin is specifically designed to patch the `magewirephp/magewire` module. Ensure the module is installed in
  your Magento project before using this plugin.
- If the patch cannot be applied (e.g., due to a conflicting version of Magewire), the plugin will notify you. In such
  cases, manual intervention might be required.

## Patch Details

This plugin applies changes included in the patch that:

- Refactor configuration to support both frontend and backend contexts for Magewire.
- Adds necessary dependencies, events, and layouts for backend compatibility.
- Restructures certain frontend-specific code for better shared base usage.

### Related Links

- Original Pull Request: [magewirephp/magewire#139](https://github.com/magewirephp/magewire/pull/139)
- Direct Patch File: [Patch 139](https://patch-diff.githubusercontent.com/raw/magewirephp/magewire/pull/139.patch)

For the complete list of changes applied by the patch, you can inspect the patch file at
`vendor/disrex/magewire-backend-patcher/patches/magewire-backend.patch`.

## Contributing

Contributions are welcome! If you find an issue or have improvements to suggest, feel free to open a pull request
on [GitHub](https://github.com/magewirephp/magewire/pull/139).

## License

This project is licensed under the MIT License. See the [LICENSE](https://www.disrex.nl/LICENSE.txt) file for more
details.

---

### Support

For any issues or questions, please contact the Disrex team at [support@disrex.nl](mailto:support@disrex.nl).
