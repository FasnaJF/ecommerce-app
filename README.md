# 🛒 Ecommerce App

A modern ecommerce application built with Laravel and Vue.js that allows users to browse products, manage shopping carts, and place orders with a seamless user experience.

## ✨ Features

- **User Authentication**: Secure login and registration using Laravel Fortify
- **Product Browsing**: View all available products with details
- **Shopping Cart**: Add, update, and remove items from your cart
- **Order Management**: Place orders and track your order history
- **User Account**: View and manage your profile
- **Responsive Design**: Beautiful UI built with Tailwind CSS
- **Dark Mode Support**: Full dark mode compatibility

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 12
- **Database**: SQLite (configured, easily switchable to MySQL/PostgreSQL)
- **Authentication**: Laravel Fortify
- **Mail**: Log-based (configurable)

### Frontend
- **Framework**: Vue 3
- **Build Tool**: Vite
- **UI Framework**: Tailwind CSS
- **Routing**: Inertia.js

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm or yarn

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd ecommerce-app
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install JavaScript Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Create Database
```bash
# SQLite (default)
touch database/database.sqlite

# Or run migrations (creates database)
php artisan migrate
```

### 6. Seed Sample Data
```bash
php artisan db:seed
```

## 🏃 Running the Application

### Development Mode

**Terminal 1 - Start PHP Server:**
```bash
php artisan serve
```

**Terminal 2 - Start Vite Dev Server:**
```bash
npm run dev
```

The application will be available at `http://localhost:8000`

### Production Build
```bash
npm run build
php artisan optimize
```

## 📖 Usage

### Landing Page
- Unauthenticated users see the welcome page with options to log in or register
- Authenticated users are redirected to the products page

### User Registration & Login
- Click **Register** to create a new account
- Click **Log in** to access your account

### Shopping
1. Browse available products on the home page
2. Click **Add to Cart** to add items to your shopping cart
3. Navigate to **Cart** to review items
4. Update quantities or remove items as needed
5. Click **Checkout** to place your order

### Orders
- View your order history in the **Orders** section
- Track all your previous purchases

### Account
- Click on your name in the navbar to access account settings
- Click **Logout** to exit your account

## 📁 Project Structure

```
ecommerce-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   └── OrderController.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Mail/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite
├── resources/
│   ├── js/
│   │   ├── pages/
│   │   ├── layouts/
│   │   ├── components/
│   │   └── routes/
│   └── css/
├── routes/
│   ├── web.php
│   └── api.php
└── config/
    └── fortify.php
```

## 🔐 Authentication

The application uses **Laravel Fortify** for authentication, providing:
- User registration
- Email verification
- Two-factor authentication support
- Password reset functionality

### Routes
- `GET /` - Home/Products page
- `GET /login` - Login page
- `GET /register` - Registration page
- `POST /logout` - Logout action
- `GET /cart` - Shopping cart (protected)
- `GET /orders` - Order history (protected)

## 📦 Database Models

### User
- id, name, email, password, etc.

### Product
- id, name, description, price, stock

### Order
- id, user_id, total_amount, status

### OrderItem
- id, order_id, product_id, quantity, price

## 🧪 Testing

Run tests with:
```bash
php artisan test
```

## 🐛 Troubleshooting

### Database Issues
```bash
# Reset database
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
```

### Build Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install
npm run dev
```

## 📝 Environment Variables

Key configuration in `.env`:
- `APP_NAME`: Application name
- `APP_URL`: Application URL
- `DB_CONNECTION`: Database type (sqlite, mysql, pgsql)
- `SESSION_DRIVER`: Session storage (database, file, cookie)
- `MAIL_MAILER`: Mail driver (log, smtp, mailgun, etc.)

## 🚀 Deployment

### Production Checklist
1. Set `APP_DEBUG=false` in `.env`
2. Set `APP_ENV=production`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Build frontend: `npm run build`
6. Set proper database (MySQL/PostgreSQL)
7. Configure mail service
8. Set up proper logging

## 📞 Support

For issues or questions, please create an issue in the repository.

## 📄 License

This project is open source and available under the MIT license.

---

**Happy Shopping! 🎉**
