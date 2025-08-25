# S & K SmartFix — PHP + MySQL

A functional repair-shop web app with:
- Login for CEO & Engineers
- Engineer complaint area
- Naira (₦) currency everywhere
- CEO-only statistical report (CEO: `ceo` / `Abubakar@00`)
- Invoice/receipt with logo & CEO name
- Inventory & Repairs management
- Logo slot on website and invoices (`uploads/logo.png`)

## Quick Start

1. **Create DB**
   - Import `database.sql` into your MySQL/MariaDB server.
2. **Configure DB Credentials**
   - Open `db.php` and update `$user` and `$pass` to match your MySQL credentials.
3. **Deploy Files**
   - Copy all files to your PHP server folder (e.g., XAMPP `htdocs/smartfix-php` or a cPanel site).
4. **Access the App**
   - Open `http://localhost/smartfix-php/login.php`
   - CEO login: `ceo` / `Abubakar@00`
5. **Add Engineers**
   - Insert into DB:
     ```sql
     INSERT INTO users (username,password,role) VALUES ('engineer1','pass123','engineer');
     ```

## Notes
- Replace `uploads/logo.png` with your own logo to customize receipts and the site header.
- Only the CEO can access `report.php` (statistical report & complaints list).
- Generate invoices from the **Repair Management** tab by clicking **Invoice** on any job.

## Security
- For production, replace plain-text passwords with password hashing (e.g., `password_hash`) and use prepared statements everywhere (already used).
