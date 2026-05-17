# Ratals

## Overview

Ratals is an open-source PHP CMS, eCommerce platform, and ERP system built for self-hosted businesses and developers who want full control over their website, accounting, inventory, data, infrastructure, and workflows - without relying on fragmented SaaS tools.

Instead of stitching together multiple platforms, Ratals allows you to manage your website, sales, inventory, and accounting in one system.

---

## Core Features

### CMS (Content Management System)
- Dynamic page builder and content management
- Media library and asset management
- SEO-friendly structure and routing
- Full control over templates and rendering

### eCommerce
- Product and category management
- Order processing and customer accounts
- Payment gateway integrations
- Inventory-aware checkout system

### ERP (Enterprise Resource Planning)
- General ledger and journal entries
- Financial reports (Balance Sheet, Income Statement, Cash Flow)
- Inventory tracking across warehouses
- Purchase orders and receiving workflows

### Platform & Performance
- Self-hosted with One-Click internal updates
- Optimized for high performance and low latency
- Works with Nginx, Apache, or LiteSpeed
- Designed for scalability

### Developer-Focused
- Built in PHP with a modular architecture
- Hook system for safe customization
- Clean codebase and file structure
- Full control over database and logic

---

## Effortless Maintenance

Ratals includes a native, smart update system built directly into the core.
- **Smart License Updates** - The system identifies your active licenses to deliver the appropriate updates. The CMS core remains up-to-date for everyone, while Business modules (Commerce, ERP, AI) are updated for active license holders.
- **Real-time Notifications** - Receive update alerts and security messages directly in your admin dashboard.
- **Environment Safety Checks** - To ensure total compatibility, the system automatically verifies your PHP and MySQL versions before the update button is even displayed.
- **Data Integrity** - Updates are designed to swap system files and intelligently manage database schema changes - adding or updating core columns while **preserving your custom columns**, configurations, and media.

---

## Who Ratals Is For

- Developers who want full control over their stack
- Businesses that prefer self-hosting over SaaS platforms
- Teams looking to unify CMS, eCommerce, and ERP systems
- Users moving away from platforms like WordPress, Magento, or Shopify

---

## Getting Started

### Requirements
- PHP 8.1+
- MySQL 8.0+ or MariaDB
- Web server (Nginx, Apache, or LiteSpeed)


### Installation

Ratals can be installed in two ways depending on your experience level.

### Option 1: Recommended (Most Users - ZIP Install)

1. Download the latest release ZIP from GitHub

2. Extract the files on your computer

3. Upload the files to your web server (public_html or equivalent)

4. Configure your domain to point to the project directory

5. Open your browser and visit your domain

6. Server Configuration (Nginx Users Only):
   - **Nginx Users:** You must manually configure your server block for routing to work. [Get the Nginx Configuration Guide here](https://www.ratals.com/tutorials/installation/setting-up-ratals-on-nginx/).

7. The installer will automatically load and guide you through:
   - Database connection setup
   - Initial system configuration
   - Admin account creation

8. Once completed, you will be redirected to your dashboard

> If the installer does not appear, verify your server configuration and file upload.

---

### Option 2: Developers (Git Install)

1. Clone the repository from GitHub: `git clone https://github.com/ratals-inc/ratals-cms.git`

2. Navigate into the project directory to see the downloaded files: `cd Ratals`

3. Follow the same installation steps as above

---

Full documentation: https://www.ratals.com/tutorials/

---

## Screenshots

_Add screenshots here:_
- Dashboard
- CMS editor
- ERP / accounting interface

---

## Philosophy

Ratals is built on a few core principles:

- **Data & Platform Sovereignty** - You own your server and your data. Unlike SaaS, your website stays online even if you choose not to renew a business license. Our internal update system ensures your platform stays modern and secure without ever losing control of your infrastructure.
- **Developer Freedom** – No restrictions on customization or deployment.
- **All-in-One System** – CMS, commerce, and operations in one platform.
- **Performance Matters** – Fast load times and efficient backend processing.

---

## Licensing & Ownership

Ratals uses a dual-license model:

### Open Source Core (Apache 2.0 License)
- Ratals core CMS framework is released under the Apache 2.0 License
- You are free to use, modify, distribute, and deploy it based on Apache 2.0 License

### Business Modules (Commercial License)
Advanced features such as Commerce, ERP, and AI modules are provided under the Ratals Business commercial license.

Under the Ratals Business License:
- You are granted a usage license for the software
- You do not obtain ownership of the proprietary business modules
- The source code remains the intellectual property of Ratals Inc.
- Redistribution, resale, or re-licensing is not permitted.
- Safe Harbor: You may deploy these modules to a single contracted client, provided that a valid Ratals Business License has been purchased by the client for their instance.

### What This Means Practically
- You fully own and control your installation and data
- You can modify the open-source core freely
- Business modules are licensed for use within your organization or deployed for a specific client project.

**In short:** You own your system and data. You are licensed to use the advanced Ratals Business features.

View full license details:
- Apache 2.0 License: https://www.ratals.com/license/
- Ratals Business License: https://www.ratals.com/business-license/

---

## Links

- Website: https://www.ratals.com/
- Documentation: https://www.ratals.com/tutorials/
- License Keys: https://www.ratals.com/account/license-keys/
- Notices: https://www.ratals.com/notices/
- Credits & Dependencies: https://www.ratals.com/credits/
- Pricing: https://www.ratals.com/pricing/

---

## Contributing

We welcome contributions to the Ratals Core. Please see our Contributing Guide for more details. For security vulnerabilities, please email support@ratals.com instead of opening a public issue.

---

## Support

For support, documentation, and updates, visit:
https://www.ratals.com/
