# PT-Manager: Rental Property Management System

PT-Manager is a lightweight, full-stack web application designed to streamline rental property operations. It provides a landlord management portal for tracking vacant rooms, creating contracts, generating utility invoices, and managing maintenance requests, alongside a tenant portal for browsing rooms, reserving properties, and submitting maintenance tickets.

---

## 💡 Why I Built This

This project was built for my Web Development course. Instead of using pre-built PHP frameworks like Laravel or CodeIgniter, I chose to implement the system using **Core PHP** and a custom **MVC structure** built from scratch.

This approach helped me understand:
- How modern web frameworks handle routing, autoloading, and request dispatching under the hood.
- How to structure clean, modular code separating business logic, data access, and views.
- How to implement relational database constraints, transaction management, and automated database triggers in MySQL.

---

## 🛠️ Tech Stack

- **Backend:** Core PHP (>= 8.0)
- **Database:** MySQL (using PDO for secure data access)
- **Frontend:** Vanilla HTML5, CSS3 (Glassmorphism layout), Vanilla JavaScript (AJAX for asynchronous requests)
- **Architecture:** Custom Model-View-Controller (MVC) Framework

---

## 📐 How the System Works

The application uses a Front Controller design pattern where all incoming HTTP requests are routed through a single entry point:

```
[HTTP Request] ---> index.php (Front Controller)
                       |
                       v
                 Router.php (Parses ?controller=... &action=...)
                       |
                       v
                Target Controller ---> Calls Model (PDO query)
                       |
                       v
                Compiles View (HTML) ---> Returns Response to Client
```

1. **Front Controller (`index.php`):** Boots the application, initializes session filters, and triggers the class autoloader.
2. **Autoloading:** Automatically requires classes when they are instantiated, avoiding manual import statements.
3. **Router (`Router.php`):** Inspects the request parameters (e.g., `index.php?controller=user&action=rooms`), resolves the target controller and action, and dispatches the execution.
4. **Data Management (Models):** The controller calls the appropriate model, which executes parameterized SQL queries against MySQL using PDO.
5. **View Rendering (`Controller::render`):** The controller binds the returned data to the requested view template and renders the final HTML page back to the browser.

---

## 🛡️ Key Features

### Landlord / Admin Portal
- **Management Dashboard:** Real-time statistics displaying total rooms, active tenants, unpaid invoices, estimated monthly revenue, and recent contracts.
- **Room Management (CRUD):** Add rooms (with multi-file image uploads), update rates, manage status, and delete records.
- **Tenant Directory:** View active tenant profiles, contact details, and lease histories.
- **Lease Contracts:** Create new lease contracts. The system automatically updates the target room status using database triggers.
- **Utility Invoicing:** Generate monthly invoices for electricity, water, and internet usage. Supports manual status updates (paid/unpaid).
- **Maintenance log:** Track repairs, maintenance orders, and operational expenses per room.
- **Tenant Feedback:** View repair requests submitted by tenants and convert them into maintenance tickets.

### Tenant Portal
- **Room Catalog:** Browse available rooms with filtration options.
- **Reservation System:** Add rooms to a booking cart and submit rental reservation requests.
- **User Authentication:** Sign up and log in using phone numbers and ID card credentials.
- **Personal Profile:** Update contact details, upload identity card images for verification, and review active lease contracts.
- **Maintenance Tickets:** Submit maintenance requests (e.g., broken appliances) with descriptions directly to the landlord.
- **Contract Renewal:** Submit lease extension requests for administrator review.

---

## ⚡ Challenges & Lessons Learned

- **Writing a Custom Router:** Implementing a clean MVC router in PHP from scratch was the most challenging part. I had to learn how PHP resolves class names dynamically and how to structure a Front Controller pattern correctly.
- **Data Integrity with Transactions:** Creating a lease contract is a multi-table database operation (modifying the room status, saving tenant details, and creating the lease record). I used **MySQL transactions (PDO)** to ensure that if any query failed, all database changes were rolled back, preventing orphaned data records.
- **Decoupling business logic from Database:** I learned to move database calculations and checks to MySQL using **Triggers** and **Stored Procedures**. For example, when a contract is saved, a database trigger automatically marks the room as "occupied", keeping my PHP controller code simple and focused.

---

## 🚧 Current Limitations

- **Plaintext Credentials:** Passwords are currently stored as plaintext national ID numbers in the database. In a production app, these must be hashed using PHP’s `password_hash()` (Bcrypt).
- **Manual Payment Verification:** There is no integration with online payment APIs (like Momo or VNPAY). Landlords must manually verify payments and click "Paid" on the dashboard.
- **Query String Routing:** The router relies on query strings (`?controller=user&action=rooms`) instead of clean, search-engine-friendly URLs (like `/rooms/1`).

---

## 🔮 Future Improvements

- [ ] Implement secure password hashing using Bcrypt (`password_hash`).
- [ ] Integrate VNPAY or Momo payment gateway sandbox APIs for simulated online billing.
- [ ] Add regular expressions (Regex) support to `Router.php` to enable clean URL routing (e.g. `/room/12` instead of `/index.php?controller=room&id=12`).

---

## 🚀 Setup & Installation

### Prerequisites
- **XAMPP** (includes Apache and MySQL) with PHP version 8.0 or newer.

### Installation Steps

1. **Start XAMPP Services:**
   Open the **XAMPP Control Panel** and start both **Apache** and **MySQL**.

2. **Configure the Database:**
   - Open your browser and navigate to `http://localhost/phpmyadmin/`.
   - Create a new database named exactly: `quan_ly_phong_tro`.
   - Select the newly created database, go to the **Import** tab, choose the file `KTX_mysql.sql` from this repository, and click **Import** to load tables, data, triggers, and procedures.

3. **Deploy Source Code:**
   - Copy the project folder `Rental-Property-Management-Pj` and paste it into the Apache server folder, typically:
     `C:\xampp\htdocs\Rental-Property-Management-Pj`

4. **Verify Database Configurations:**
   - Open `core/Database.php` to verify database connection details. The default credentials are:
     ```php
     host=localhost
     dbname=quan_ly_phong_tro
     username=root
     password=""  // empty password
     ```


5. **Run the Application:**
   Open your browser and navigate to:
   `http://localhost/Rental-Property-Management-Pj/index.php`

---

## 🔑 Test Credentials

You can use the following default accounts to log in:

- **Landlord / Admin Account:**
  - Phone Number: `admin`
  - Password: `admin123`

- **Tenant Account:**
  - Phone Number: `0901234567`
  - Password: `079123456789` (National ID Card Number)
