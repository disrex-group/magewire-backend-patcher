# Magewire Backend Patcher for Magento 2

**Composer plugin that seamlessly applies patches to enable Magewire functionality in Magento 2's backend area. Fully automated patching with no manual intervention required.**

> ⚠️ This plugin is specifically designed for the `magewirephp/magewire` module. Ensure it's installed before using this patcher.

---

## ✅ Installation

Add the Magewire Backend Patcher to your project via Composer:

```bash
composer require disrex/magewire-backend-patcher
```

> Note: The patch will be automatically applied during installation or updates if the `magewirephp/magewire` module is present in the project.

---

## 🔧 How It Works

The plugin automatically performs these steps during Composer operations:

- **Automatic Detection:** Searches for the Magewire module at `vendor/magewirephp/magewire`
- **Patch Application:** Applies the backend compatibility patch during `post-install` and `post-update` phases
- **Validation:** Checks if patches are already applied and prevents conflicts
- **Safety:** Includes safeguards against re-application with user warnings

---

## 🚀 Features

- ✅ Fully automated - no manual patching required
- ✅ Enables Magewire in both frontend and backend contexts
- ✅ Includes validation checks and conflict prevention
- ✅ Compatible with standard Composer workflows

---

## 📋 Requirements

- **PHP:** ^8.0
- **Composer:** ^2.0
- **Magento:** Compatible with Magento 2 environments
- **Magewire module:** `magewirephp/magewire` must be installed

---

## 🛠️ Manual Patch Application

If needed, manually trigger the patch process:

```bash
composer run-script post-install-cmd
```

This will reapply the patch and display the operation status.

---

## 📝 Patch Details

This plugin applies changes that:

- Refactor configuration to support both frontend and backend contexts
- Add necessary dependencies, events, and layouts for backend compatibility  
- Restructure frontend-specific code for improved shared base usage

**Related Resources:**
- [Original Pull Request](https://github.com/magewirephp/magewire/pull/139)
- [Direct Patch File](https://patch-diff.githubusercontent.com/raw/magewirephp/magewire/pull/139.patch)

---

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](https://www.disrex.nl/LICENSE.txt) file for details.

---

## 👨‍💻 Support

Have questions or want to collaborate? Open a discussion
on [GitHub Discussions](https://github.com/disrex/magewire-backend/discussions).

<img src="https://files.disrex.nl/disrex-character.gif?t=572693425" alt="Disrex T-Rex Mascot Waving" width="150">

---

## Sponsored by

<picture>
  <source srcset="https://files.disrex.nl/logos/logo-w.png" media="(prefers-color-scheme: dark)">
  <img src="https://files.disrex.nl/logos/logo-b.png" alt="Disrex Logo" width="200">
</picture>