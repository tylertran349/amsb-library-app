# Project: Library Book Manager

## Introduction

This project is a web-based application designed for managing a library's book collection. It was developed using the PHP language and the CodeIgniter 4 framework. The primary goal of this application is to provide a user-friendly interface for a librarian to create, view, update, and delete book records.

### Key Features

*   **Book Creation:** Users can add new books to the library system by providing details such as title, author, genre, and publication year. An option to upload a cover image is also available.
*   **Book Listing:** All books in the database are displayed in an organized table, which includes the cover image for easy identification.
*   **Record Updates:** Existing book records can be easily edited through a dedicated form, which comes pre-filled with the book's current information.
*   **Record Deletion:** Books can be removed from the system. This action also deletes the associated cover image to maintain a clean file system.
*   **Input Validation:** The system checks all user-submitted data on the server. This ensures that essential information is not missing and that all data, such as the publication year, is in a valid format before being saved.

### Technologies Used

*   **Backend Framework:** CodeIgniter 4
*   **Programming Language:** PHP (Version 8.1+)
*   **Database System:** MySQL / MariaDB
*   **Frontend Structure:** HTML5
*   **Styling:** CSS3
*   **Package Management:** Composer

---

## Setup Instructions

To run this project on a local computer, please follow the steps below. This guide assumes you are using **XAMPP** for your local development environment.

### Prerequisites

*   **Git:** For cloning the repository.
*   **XAMPP:** It is recommended to use XAMPP as it provides Apache, PHP, a MariaDB (MySQL) database server, and the phpMyAdmin tool in a single, easy-to-use installation.
*   **Composer:** The dependency manager for PHP.

### Step 1: Obtain the Project Files

Use Git to clone the project repository from GitHub to your machine. It's recommended to clone it into the `htdocs` folder inside your XAMPP installation directory (e.g., `C:/xampp/htdocs/`).

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### Step 2: Install Project Dependencies

This project uses Composer to manage its core libraries. Open your terminal/command-prompt inside the project folder and run the following command:

```bash
composer install
```

### Step 3: Configure the Local Environment

1.  In the project's root folder, create a file named `.env`.
2.  Open the `.env` file you just created with a text editor.
3.  Copy and paste the following contents into the .env file:
    ```ini
    CI_ENVIRONMENT = production
    app.indexPage = ''
    database.default.hostname = localhost
    database.default.database = amsb-library-app
    database.default.username = root
    database.default.password =
    database.default.DBDriver = MySQLi
    database.default.DBPrefix =
    ```

### Step 4: Prepare the Database with XAMPP

1.  Open the **XAMPP Control Panel** and click the **"Start"** button for both the **Apache** and **MySQL** modules. They should both turn green.
2.  Open your web browser and navigate to `http://localhost/phpmyadmin`.
3.  Click on the **"Databases"** tab at the top.
4.  Under "Create database", enter the database name: **`amsb-library-app`**.
5.  Set the collation to `utf8mb4_general_ci` and click **"Create"**.

### Step 5: Construct the Database Table

The project includes a "migration" file that acts as a blueprint for the database table. In your terminal (still inside the project folder), run the command below to build the `books` table in the database you just created.

```bash
php spark migrate
```

### Step 6: Start the Application Server

While XAMPP provides the Apache web server, the simplest and most direct way to run a CodeIgniter 4 application is with PHP's built-in server. This command correctly sets the `public` directory as the web root, which is critical for security and functionality.

In your terminal, run:

```bash
php spark serve
```

By default, this will start the server on **http://localhost:8080**. You can specify a different port if needed (e.g. php spark serve --port 8081).

### Step 7: View the Application

The setup is complete. You can now access the application by navigating to the address provided by the spark serve command, typically:

**http://localhost:8080**

---

## Project Design and Rationale

During the development process, I made several strategic decisions regarding the application's architecture and features.

### 1. URL Routing Structure

For handling web addresses (URLs), I chose CodeIgniter's "Presenter Routing" system. This system is designed specifically for building applications that display web pages. It provides clear and logical URLs like `/books/new` and `/books/edit/1`, which aligns well with a user-facing interface, as opposed to a system designed for pure data APIs.

### 2. Database Design

The structure of the database was defined using CodeIgniter's migration feature. This approach keeps the database design within a code file that is part of the project's version control. It ensures that anyone setting up the project can create an identical database structure easily and reliably. For the `publication_year` field, I selected the `SMALLINT` data type instead of `YEAR`, as it offers greater flexibility for storing historical dates (e.g., years before 1901).

### 3. Data Validation Strategy

To ensure data quality and security, all information submitted through forms is validated before it is saved. Users are only allowed to specify publication years between (and including) 0 and the current year. For each book, users must specify a title, author, and publication year. Any non-image uploaded as the cover image for a book will be rejected. Instead of using the generic default error messages from the framework, I implemented custom messages like "The publication year cannot be in the future." This makes the application more helpful and user-friendly by providing clear feedback.

### 4. Image File Management

For the image upload feature, I considered both storage efficiency and security.
*   **Storage Location:** Images are stored in the `public/uploads` folder. This is a standard practice that allows the web server to deliver image files directly and efficiently, without needing to execute any PHP code.
*   **Security Measures:** To prevent users from uploading harmful files, the system uses strict validation rules to confirm that every uploaded file is a valid image with a safe file type (like JPEG or PNG).
*   **File Cleanup:** The application is designed to be self-maintaining. When a book is updated with a new image, the old one is deleted. Similarly, when a book record is removed entirely, its corresponding image file is also deleted, preventing unused files from accumulating on the server.

### 5. Secure Data Handling

When handling form submissions, particularly for updating records, I chose to build the data array for the database manually. This is a deliberate security practice. It creates a "whitelist" of allowed fields, ensuring that only the data I explicitly permit (like 'title' and 'author') can be saved. This prevents a user from maliciously trying to submit extra data that is not part of the form, which could otherwise corrupt the database.