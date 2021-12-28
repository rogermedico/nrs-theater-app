# Advanced PHP Test for NRS Group

## Context

I was provided with an instructions to build a theater tickets management app
with the following conditions:

 - Laravel has to be used as an MVC framework
 - GIT has to be used as a code repository
 - Bootstrap has to be used as a styling library
 - The data has to be stored in a MySQL database
 - Every time a reservation is done a log entry has to be made
 - The users and reservations data has to be stored as a non volatile info
 - Each reservation can have one or more seats
 - Each seat has to have a row and a column
 - User can not pick an already reserved seat

Valorable improvements besides that conditions will be:
 - Complete CRUD for reservations and users
 - Code organization
 - Error check

## Project behaviour

### Users

The users can register, login and logout using the links provided in the toolbar.
Guests users have to fill extra fields when making a reservation to register automatically
in the app. When a user is logged in he can access a profile page which allows himself to
edit his personal data, password or even delete his account.

User creation don't send verification emails for simplicity but checks for email uniqueness.

At the very beginning the app has 7 users already registered. One admin and 6 normal users.
The credentials for the two types of users are the following:

| Username        | password |
|-----------------|----------|
| admin@gmail.com | password |
| user@gmail.com  | password |

The admin user can access a special section under the admin menu dropdown
to be able to modify other users info.

When a user registers himself to the app he is automatically logged in.

### Reservations

The reservations are made in two steps. In first step the user selects the session
that he wants a seat on (if it's a guest he has to fill in the fields to register)
and then the user can select as many seats as he wants.

When a user has made a reservation a menu link appears to manage his own reservations.
Throught that menu he can access a CRUD that allows to modify or delete his reservations.
The admin user throught the corresponding menu link can manage all other user reservations.

When a guest user makes a reservation filling in all the fields he is automatically logged in.

### Sessions

In the beginning there are 6 sessions already created in the app but the admin user 
using the CRUD in the manage sessions page accessible through the admin menu
can create, modify or delete as he pleases.

## About the project 

### DB

The DB schema consists of three tables. One with users info, another with
sessions and the last one with the reservations.

The users table stores the users info such as name, surname, email, password and
if the user is admin. The Sessions table stores info about the sessions that will play
in the theater sixth the fields name, date and price. Finally, the reservations table
stores information about every reservation done with the user that made it, the session
for wich is the reservation and the row and the column of the seat reserved.

The DB configuration is made using migrations and initially the is filled
with some information provided by the seeders and the factories.

### Routes

Routes are basically three resources one for each entity in the project (session, user and reeservation)
and some complementary routes. Every route has his middleware applied to avoid forbidden access.
The middlewares that have been used are auth, guest and a created one called admin that checks if user is admin.

### Models

There are three models, again, one for each entity. In the models there are the relations between them specified as a relations
and in the user model there are even a mutator for the password.

## Controllers

As expected there are three controllers. The controllers use policies and gates to 
check that the users can perform the actions. It is best practice to add middleware in the
routes file but in user and reservation controllers there are middlewares applied in 
the constructor because different methods of the resource has to had different middlewares.

The validation of the fields always is made using custom requests as is expected from a well made project.

## Run Project (local environment)

1. Download or clone the code in this repository
2. Execute the ``composer install`` command to install laravel dependencies
3. Execute ``npm install`` to install npm dependencies (Bootstrap)
4. Execute ``npm run dev`` to compile the npm dependencies
5. Create a database and fill the .env file with the correct info (DB connection)
6. Seed the DB running the proper command. Ex:``php artisan migrate:refresh --seed``
7. Execute the serve command ``php artisan serve`` or open your LAMP directory and access to the project folder
8. Access [localhost:8000](http://localhost:8000) (typically) and see the project

## Tests

To run the tests you have to execute the following command ``php artisan test``.
Due to lack of time only user tests are made.

## TO DO

 - Extract some controller functionality to service
 - Reservation and session tests

## Author

Roger Medico Piqué - [roger.medico@gmail.com](mailto:roger.medico@gmail.com)
