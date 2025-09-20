# Tech test Mayden
I have completed tasks 1-5. Having spent approx 3.5 hours on the task.

To setup the project install Laravel Sail (wrapper around docker) then run:
- `docker compose build`
- `sail up -d`
- `sail artisan migrate` - the database is sqlite
- `sail artisan db:seed` - to seed the database, if desired...
Test user is
* email - `test@example.com`
* password - `password`

The project is set up using Laravel, Vue.JS and Inertia.JS. It is a stack I am reasonably familiar with. 

There are 2 new controllers (`app\Http\Controllers`)- 
* GroceryController
* GroceryListController

Which have corresponding models and migrations

The frontend is in the `resources/js/Pages/GroceryList` directory
* Index 
* Show

We can view these by visiting `/grocery-lists` and `/groceries`

The tests are in the `tests\Feature\GroceryCompletionTest` class
Run the tests using `sail artisan test
