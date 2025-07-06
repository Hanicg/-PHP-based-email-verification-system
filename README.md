# 📬 XKCD Comic Email Subscription System (PHP + CRON)

This is a **PHP-based email verification and comic subscription system** where users can:
- Register via email.
- Receive daily **XKCD comics** via email.
- Unsubscribe with verification.
- Everything is automated using a **CRON job**.

---

## ✅ Features Implemented

### 1. 📧 Email Verification
- User enters email on `index.php`.
- A 6-digit numeric verification code is sent to their inbox.
- Upon verification, their email is stored in `registered_emails.txt`.

### 2. 🕐 XKCD Comic Daily Delivery (via CRON job)
- Every 24 hours, a **CRON job** runs `cron.php`.
- It fetches a random XKCD comic using the XKCD API.
- Sends the comic in HTML format to all registered users.
- Each email includes an **unsubscribe** link.

### 3. 🚫 Unsubscribe System
- Clicking "Unsubscribe" takes the user to `unsubscribe.php`.
- The user re-verifies their email using a code.
- On successful verification, the email is removed from the list.

---

## 📁 Project Structure

![image](https://github.com/user-attachments/assets/a3c6d739-748c-48b0-9215-579bc049f827)

---

## 🛠️ Technologies Used

| Technology              | Description |
|-------------------------|-------------|
| **PHP (8.3)**           | Core backend scripting used to implement logic, email, and verification. |
| **CRON (Linux Scheduler)** | Schedules the `cron.php` script to run every 24 hours. |
| **XKCD API**            | Fetches random comic data from `https://xkcd.com/[comicID]/info.0.json`. |
| **Plain Text Storage**  | No database used — all data is saved in `.txt` or `.json` files. |
| **Shell Script (Bash)** | Automates CRON job creation via `setup_cron.sh`. |
| **HTML Emails**         | Emails are formatted and sent using PHP's `mail()` function with HTML content. |

<img width="966" height="662" alt="Image" src="https://github.com/user-attachments/assets/0852497a-a490-4a85-9e82-d81837e3d6c7" />

---

## ⚙️ CRON Job Setup

To set up the CRON job:

```bash
cd src/
bash setup_cron.sh
```

📬 Email Format
✅ Verification Email
Subject: Your Verification Code
Sender: no-reply@example.com

 ✅ XKCD Comic Email
Subject: Your XKCD Comic

✅ Unsubscribe Confirmation Email
Subject: Confirm Un-subscription

## 📝 Form Input Guidelines

| Purpose             | `name` Attribute      | `id` Attribute         |
|---------------------|------------------------|-------------------------|
| Email Submission    | `email`                | `submit-email`         |
| Verification Code   | `verification_code`    | `submit-verification`  |
| Unsubscribe Email   | `unsubscribe_email`    | `submit-unsubscribe`   |
| Unsubscribe Code    | `verification_code`    | `submit-verification`  |

🧪 Testing Steps
Open index.php in a browser and enter your email.

Check your inbox for the verification code and verify.

Wait for the daily XKCD email or run cron.php manually.

Click the "Unsubscribe" link in the email.

Complete the verification to remove your email from the list.

🙋‍♂️ Author
Hanith CG
📧 hanithcg@gmail.com

