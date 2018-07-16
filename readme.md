# Installation Instruction

## Requirement
  1. Latest Laravel and its Dependencies, [Click Here](https://laravel.com/docs/5.6/installation).
  2. XAMPP Server Version 7.2.3
  3. Browser

## Tools
  1. Testing tools: [Postman](https://www.getpostman.com/docs/v6/postman/sending_api_requests/requests)

## Installation
  1. Install all the necessary dependencies to run this application
  2. Download or clone this repository to /XAMPP/htdocs/
  3. Through the terminal, go to the ticketApp/ directory
  4. Run `php artisan key:generate`
  5. Run `php artisan update` to install and update all the library included in this application
  6. Start the XAMPP server to run the server
  7. Change the DB_USER and DB_PASSWORD on `/ticketApp/.env` to your SQL user id and password
  7. Go to `localhost/phpmyadmin` on the browser and create database called `ticketApp`
  8. Once the database is created run `php artisan migrate` to create the tables. Once the database and its tables is imported, the application is ready to serve

## Testing
  1. Open Postman
  2. Create new Collection called `ticketApp`
  3. Create new request called createEvent
  4. Change the method to POST
  5. Put `http://localhost/tiketApp/public/event/create` on the URL input box
  6. Add the following parameters to body:
    - event_name:coba
    - event_age_limit:0
    - location_id:1
    - event_date_start:19940917
  7. Click Send to send the request
  8. Do this to all the functions listed on `assumption.md`
