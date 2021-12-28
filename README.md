# Advanced PHP Test for the NRS Group

## Context

I was provided with instructions to build a theater tickets management app
with the following conditions:

 - Laravel has to be used as an MVC framework
 - GIT has to be used as a code repository
 - Bootstrap has to be used as a styling library
 - The data has to be stored in a MySQL database
 - Every time a reservation is made a log entry has to be made
 - The users and reservations data has to be stored as a non-volatile info
 - Each reservation can have one or more seats
 - Each seat has to have a row and a column
 - The user cannot pick an already reserved seat

Improvements to be taken into consideration besides these conditions will be:
 - Complete CRUD for reservations and users
 - Code organization
 - Error check

## Project behaviour

### Users

The users can register, login and logout using the links provided in the toolbar.
Guest users have to fill extra fields when making a reservation to register automatically
in the app. When a user is logged in, he can access a profile page which allows him to
edit his personal data, password or even delete his account.

User creation don't send verification emails for simplicity, but it checks for email uniqueness.

At the very beginning, the app has 7 users already registered. One admin and 6 normal users.
The credentials for the two types of users are the following:

| Username        | Password |
|-----------------|----------|
| admin@gmail.com | password |
| user@gmail.com  | password |

The admin user can access a special section under the admin menu dropdown to modify other user info.

When a user registers himself to the app he is automatically logged in.

### Reservations

The reservations are made in two steps. In the first step, the user selects the session that
he wants to book (if it's a guest he has to fill in the fields to register)
and then, the user can select as many seats as he wants.

When a user has made a reservation, a menu link appears to manage his own reservations.
Through that menu, he can access a CRUD that allows him to modify or delete his reservations.
The admin user, through the corresponding menu link, can manage all other user reservations.

The guest user is automatically logged in when he makes a reservation filling in all the fields.

### Sessions

In the beginning there are 6 sessions already created in the app, but the admin user can create, 
modify or delete as he pleases using the CRUD in the manage sessions page accessible through
the admin menu.

## About the project 

### DB

The DB schema consists of three tables. One with users info, another with
sessions and the last one with the reservations.

The users table stores the users info such as name, surname, email, password and
if the user is an admin. The sessions table stores info about the sessions that will take place
in the theater with the fields name, date and price. Finally, the reservations table
stores information about every reservation with the user that made it, the session
for which the reservation is made, and the row and the column of the reserved seat.

The DB configuration is made using migrations and, initially, is filled
with some information provided by the seeders and the factories.

### Routes

Routes are basically three resources, one for each entity in the project (session, user and reservation)
and some complementary routes. Every route has its middleware applied to avoid forbidden access.
The middlewares that have been used are auth, guest and a new created one called admin that checks if a user is an admin.

### Models

There are three models, again, one for each entity. The relations between them are specified in each model
and in the user model there is also a mutator for the password.

## Controllers

As expected there are three controllers. The controllers use policies and gates to 
check that the users can perform the actions. It is best practice to add middleware in the
routes file. However, in the user and the reservation controllers, there are middlewares applied in 
the constructor, because different methods of the resource have to have different middlewares.

The validation of the fields is always made using custom requests, as it is expected from a well-done project.

## Run Project (local environment)

1. Download or clone the code in this repository
2. Execute the ``composer install`` command to install laravel dependencies
3. Execute ``npm install`` to install npm dependencies (Bootstrap)
4. Execute ``npm run dev`` to compile the npm dependencies
5. Copy env.example file to .env in the root directory
6. Run the command ``php artisan key:generate`` to regenerate the app key
7. Run the command ``php artisan storage:link`` to allow access to the images from public directory
8. Create a database and fill the .env file with the correct info (DB connection)
9. Optionally if you want to run tests: Create a second database and fill the DB_TEST_DATABASE field
 in the .env file.
10. Seed the DB running the proper command. For example: ``php artisan migrate:refresh --seed``
11. Execute the serve command ``php artisan serve`` or open your LAMP directory and access to the project folder
12. Access [localhost:8000](http://localhost:8000) (typically) and see the project

## Tests

To run the tests you have to execute the following command ``php artisan test``.
Due to the lack of time, only the user tests have been made.

## TO DO

 - Extract some controller functionality to a service
 - Reservation and session tests
 - Find a more meaningful word for "session" (maybe show/event) and refactor all the code with the better found word

## Author

Roger Medico Piqué - [roger.medico@gmail.com](mailto:roger.medico@gmail.com)
