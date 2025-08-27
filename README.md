# Journal Management System

A manuscript management system for a journal outlet.

## Installation on a Local Server (WAMP)

These instructions will guide you on how to set up the application on a local server using WAMP.

### 1. Install WAMP Server

Download and install WAMP Server from the [official website](https://www.wampserver.com/en/). Make sure to install all the required Visual C++ packages that are mentioned during the installation.

### 2. Start WAMP Server

Launch WAMP Server and make sure that all services (Apache, PHP, and MySQL) are running. The WAMP icon in the system tray should be green.

### 3. Copy Application Files

Copy all the application files and folders into the `www` directory of your WAMP installation. For example, if WAMP is installed in `c:\wamp64`, you would copy the files to `c:\wamp64\www\journal_management\`.

### 4. Create the Database

a. Open your web browser and navigate to `http://localhost/phpmyadmin`.
b. Click on the "Databases" tab.
c. In the "Create database" field, enter `journal_management` and click "Create".

### 5. Configure the Database Connection

a. Open the `php/db_connect.php` file in a text editor.
b. By default, the application is configured to use the following credentials:
   - **Username:** `root`
   - **Password:** (empty)
   - **Database Name:** `journal_management`
c. If your MySQL setup uses different credentials, update the `DB_USERNAME` and `DB_PASSWORD` constants in this file accordingly.

### 6. Set Up Database Tables and Test Users

The application includes a script to create all the necessary database tables and populate the database with some initial test users for each role.

a. Open a command prompt or terminal.
b. Navigate to the application's root directory (e.g., `cd c:\wamp64\www\journal_management`).
c. Run the following command: `php seed.php`
d. You should see messages indicating that the tables and users were created successfully.

**Test Users:**
The following test users will be created with the password `password`:
- `admin` (Admin)
- `eic` (Editor in Chief)
- `author` (Author)
- `section_editor` (Section Editor)
- `reviewer` (Reviewer)

### 7. Run the Application

You are now ready to run the application. Open your web browser and navigate to:
`http://localhost/journal_management/`

You can log in with any of the test users created in the previous step.
