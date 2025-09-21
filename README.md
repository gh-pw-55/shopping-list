# Tech test Mayden
I have completed tasks 1-6. Having spent approx 5 hours on the task.
1. Create a shopping list that can contain a list of groceries
 * User can create grocery lists `/grocery-lists`
 * View a grocery list `grocery/lists/{id}`
2. Add grocery item to the list
3. Delete grocery item from list using the trash can icon
4. Checkbox marks grocery item from list
5. Data is saved to a sqlite db
6. You can drag and drop items using drag and drop api
7. There is a total for the prices

To setup the project install Laravel Sail [https://laravel.com/docs/12.x/sail#main-content] (wrapper around docker for local dev) then run:
- `docker compose build`
- `sail up -d`
- `sail artisan migrate` - the database is sqlite
- `sail artisan db:seed` - to seed the database, if desired...
Test user is
* email - `test@example.com`
* password - `password`

- `sail composer i`
- `sail artisan key:generate`

Frontend - 
- `sail npm i`
- `sail npm run dev`

The project is set up using Laravel, Vue.JS and Inertia.JS. It is a stack I am reasonably familiar with. 

View the routes at `routes/web.php`

There are 2 new controllers (`app\Http\Controllers`)- 
* GroceryController
* GroceryListController

Which have corresponding models and migrations

The Vue.js frontend is in the `resources/js/Pages/GroceryList` directory
* Index 
* Show

We can view these by visiting `/grocery-lists` and `/groceries`

The tests are in the `tests\Feature\GroceryCompletionTest` class
Run the tests using `sail artisan test`

