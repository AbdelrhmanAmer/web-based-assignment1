# Registration Web Page

This repository contains the implementation of a registration web page that inserts user data into a MySQL database. The project includes both client-side and server-side validations, as well as integration with a third-party API.

## Assignment Tasks

### Task 1: Form Design and Client-Side Validations
- **Objective:** Create a registration form with the following fields: `full_name`, `user_name`, `birthdate`, `phone`, `address`, `password`, `confirm_password`, `user_image`, and `email`.
- **Client-Side Validations:**
  - All fields are mandatory.
  - `email`, `birthdate`, and `full_name` must be of correct types.
  - `password` must match `confirm_password` and be at least 8 characters long, including at least 1 number and 1 special character.

### Task 2: Header and Footer
- **Objective:** Include custom-designed header and footer in the registration webpage.

### Task 3: Server-Side Validations
- **Objective:** Maintain a `User` table in the database to ensure the username is not already registered.
- **Steps:**
  1. Check if the username exists in the database.
  2. Alert the user to choose another username if it is already registered.

### Task 4: Image Upload
- **Objective:** Upload the user's image and store it on the server.
- **Steps:**
  1. Store the image name in the database.
  2. Ensure the image is correctly uploaded to the server.

### Task 5: API Integration for Birthdate Check
- **Objective:** Add a button beside the `birthdate` field to check actors born on the same day using the IMDb API.
- **API Details:**
  - **Endpoint 1:** `actors/list-born-today` - Retrieves a list of actors born on the same input day.
  - **Endpoint 2:** `actors/get-bio` - Retrieves actor details by passing an actor ID.
- **Steps:**
  1. Use the `actors/list-born-today` endpoint to get a list of actors.
  2. Display the list of actor names born on the same day.

## Repository Structure
- `/client`: Contains the client-side code including HTML, CSS, and JavaScript files.
- `/server`: Contains the server-side code including PHP and SQL files.
- `/assets`: Contains images and other static assets.

## Getting Started
1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/registration-webpage-assignment.git
    ```
2. Set up the database:
    - Import the `database.sql` file to create the `User` table.
3. Configure the server:
    - Ensure your server environment is set up to run PHP and MySQL.
    - Update the database connection settings in the server-side scripts.
4. Install and configure XAMPP:
    - Download and install [XAMPP](https://www.apachefriends.org/index.html).
    - Start Apache and MySQL from the XAMPP control panel.
5. Open the `index.html` file in your browser to view the registration form.

## Requirements
- XAMPP
- PHP
- MySQL
- HTML, CSS, JavaScript
