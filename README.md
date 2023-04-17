
# E-commerce Api Project

## Introduction
* This e-commerce project is designed to provide an online platform for customers to browse and purchase products from various categories. The project includes functionality such as customer registration and authentication, product listing and search, shopping cart management, order processing, and payment processing.

## Features
* User registration and authentication: Customers can create an account, log in, and change their password.
* Product listing and search: Customers can browse products by category and search for products using keywords.
* Shopping cart management: Customers can add products to their cart, view their cart, and update or remove items from their cart.
* Order processing: Customers can place orders, view their order history, and track the status of their orders.
* Payment processing: cash on delivariy

## Technology Stack
* Laravel: A PHP web application framework used for backend development.
* MySQL: A popular open-source relational database management system used to store data.

## Installation
* To run the e-commerce project, follow these steps:
* Clone the repository to your local environment
* Run composer install to install the necessary dependencies
* Create a copy of the .env.example file and rename it to .env
* Configure your database settings in the .env file
* Run php artisan migrate to migrate the database tables
* Run php artisan db:seed to seed the database with initial data
* Run php artisan serve to start the development server

## API Endpoints
### User
* POST {{url}}/auth/create: Register a new customer with name, email, and password.
* POST {{url}}/auth/login: Authenticate a customer with email and password and generate an API token.
* POST {{url}}/user/logout: Log out the current customer by invalidating the API token.
* PUT {{url}}/user/changepassword: Update the current customer's password.
* POST {{url}}/auth/forgotPasswordLink: Send a password reset email to the customer's email address.
* POST {{url}}/auth/forgotPassword: Reset the customer's password with a token sent in the password reset email.
  
### Admin
* Category and sub-category management: The admin can create, update, and delete categories and sub-categories to organize the products.
* Product management: The admin can add, update, and delete products, including their name, description, price, and quantity.
* Customer management: The admin can view a list of all registered customers and their details.
* Order management: The admin can view a list of all orders placed.
* User roles and permissions: The admin can create and assign user roles and permissions to control access to certain features or functionalities.

## Category Module:

* The category module is responsible for managing the product categories in the e-commerce platform. With this module, the admin can create, update, and delete categories to organize the products and make them easier to browse for customers. Additionally, the customers can view the list of available categories and filter products by category.

### API Endpoints:

* post {{url}}/category/list: List all available categories with pagination and search functionality.
* POST {{url}}/category/create: Add a new category.
* GET  {{url}}/category/view/{id}: Get the details of a specific category.
* PUT  {{url}}/category/update/{id}: Update an existing category.
* DELETE {{url}}/category/delete/{id}: Delete an existing category.

### Sub-Category Module:

The sub-category module is responsible for managing the sub-categories for products. With this module, admin can create, read, update, and delete sub-categories for products. Customers can browse products by sub-category and search for products using keywords.

API Endpoints:

post {{url}}/subcategory/list: Retrieve a list of sub-categories.
GET {{url}}/subcategory/lview: Retrieve a specific sub-category by ID.
POST {{url}}/subcategory/create: Create a new sub-category.
PUT {{url}}/subcategory/update//{id}: Update an existing sub-category.
DELETE {{url}}/subcategory/delete/{id}: Delete a specific sub-category by ID.

### Products Module:

The products module is responsible for managing the products in the e-commerce system. With this module, admin can create, read, update, and delete products. Customers can browse products by category and sub-category, search for products using keywords, view product details, add products to their cart, and place orders.

API Endpoints:

post /{{url}}/products/list: Retrieve a list of products.
POST {{url}}/products/list: Create a new product.
PUT /api/products/{id}: Update an existing product.
DELETE {{url}}/products/delete/{id}: Delete a specific product by ID.

### cart Module:
*user is create to cart and insert to data 

API Endpoints:

post /{{url}}/cart/list: Retrieve a list of cart login role of user types.
POST {{url}}/cart/list: Create a new cart.
PUT /api/cart/{id}: Update an existing cart.
DELETE {{url}}/cart/delete/{id}: Delete a specific cart by ID.

### order Module: 
* user is create to order and insert to data 

API Endpoints:

* post /{{url}}/order/list: Retrieve a list of login role data display.
* POST {{url}}/order/list: Create a new order.
* PUT /api/order/{id}: Update an existing order.
* DELETE {{url}}/order/delete/{id}: Delete a specific order by ID.



