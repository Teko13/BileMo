## Description

BileMo is a high-end mobile phone company embarking on a project to develop an API-driven showcase for its products, catering exclusively to a B2B audience. The project's goal is not direct sales but to enable platform partners to access BileMo's catalog through APIs. This initiative kicks off with the needs of their first client, necessitating features like viewing product lists and details, managing user information, and ensuring secure access through OAuth or JWT authentication. Data presentation must comply with the Richardson Maturity Model and prioritize JSON format with caching for performance. The development process involves planning, GitHub management, code reviews, and demonstrations, focusing on quality, performance, and adherence to best practices.

## Goal

The goal of the BileMo project is to develop a B2B API that enables partner platforms to access and manage a catalog of high-end mobile phones, including product details and user management, with secure access through OAuth or JWT authentication, following best data presentation practices for optimal performance.

## Objectives

Key functionalities of the API:

- Product Catalog Access: Enables viewing of BileMo's entire selection of mobile phones.
- Product Details Viewing: Allows detailed information on each mobile phone to be accessed.
- User Management: Supports viewing and managing the list of users registered by a client on the web platform.
- User Details Access: Enables access to detailed information about a registered user linked to a client.
- Add New User: Allows adding a new user linked to a specific client.
- Delete User: Permits the deletion of a user added by a client.
- Secure API Access: Restricts API access to referenced clients only, requiring authentication through OAuth or JWT.

## Installation

1. Clone the GitHub Repository: run

   ```
   git clone https://github.com/Teko13/BileMo.git
   ```

2. Install Dependencies: run

   ```
   composer install
   ```

3. Configure environment variable: Add the database connection URL to your .env or .env.local file, or uncomment an existing line and replace the URL information with your own.

4. Create Database: run

   ```
   php bin/console doctrine:database:create
   ```

5. Create Schema: run

   ```
   php bin/console doctrine:schema:update --force
   ```

6. Load Data: run

   ```
   php bin/console d:f:l
   ```

7. Start server: at the project root folder run local server

## Documentation

- /api/doc
