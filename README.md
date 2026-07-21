# 🅿️ AI Parking System

A smart parking management system that lets users scan a QR code, view real-time parking availability, park/unpark vehicles, and generate receipts — all through a simple web interface. Built to reduce manual tracking and make parking lot management faster and more organized.

🔗 **Live Demo:** [http://yourparking.infinityfreeapp.com/index.php](http://yourparking.infinityfreeapp.com/index.php)
👤 **GitHub:** [github.com/shrutika1973](https://github.com/shrutika1973)

---

## ✨ Features

- QR code based access — scan to open the parking system instantly
- Live map view of parking slots
- Park and unpark vehicle tracking
- Auto-generated receipts for each parking session
- MySQL-backed data storage for persistent records

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Hosting:** InfinityFree

## 📁 Project Structure

```
parking-system/
├── index.php        # Landing / entry page
├── map.php          # Parking slot map view
├── park.php         # Handles vehicle parking
├── unpark.php       # Handles vehicle exit
├── view.php         # View parking records
├── receipt.php      # Generates parking receipt
├── scan.php         # QR scan handling
├── chat.php         # Chat/support feature
├── db.php           # Database connection config
├── qr.png / qr_1.png # QR code images
```

## ⚙️ Setup Instructions

1. Clone this repository:
   ```
   git clone https://github.com/shrutika1973/ai-parking-system.git
   ```
2. Move the folder into your local server's directory (e.g. `htdocs` for XAMPP).
3. Create a MySQL database and import the project's schema (if included).
4. Open `db.php` and replace the placeholder with your own database credentials:
   ```php
   $conn = new mysqli("YOUR_DB_HOST", "YOUR_DB_USERNAME", "YOUR_DB_PASSWORD_HERE", "YOUR_DB_NAME");
   ```
5. Start your local server (e.g. XAMPP) and open the project in your browser:
   ```
   http://localhost/parking-system/index.php
   ```

## 📸 QR Code Access

Scan the QR code below (or the one included in this repo) to open the live system directly on your phone:

![QR Code](qr.png)

## 📌 Notes

- Replace `YOUR_DB_PASSWORD_HERE` in `db.php` with your actual database password before running — never commit real credentials.
- This project is deployed on free hosting, so occasional downtime or slow loading may occur.

## 📬 Contact

Have questions or suggestions? Feel free to reach out via [GitHub](https://github.com/shrutika1973) or [LinkedIn](https://linkedin.com/in/shrutika-madas).
