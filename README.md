# 2do_app
App for adding and managing tasks

### _**How to install:**_
 - Clone repository
 - Add your database connection in .env
 - Use terminal in project folder and type: 
   - `composer install`
   - `php bin/console tailwind:build` (if u have problem with memory then use `php -d memory_limit=256M bin/console tailwind:build`)
   - `php bin/console doctrine:database:create` (or do it yourself)
   - `php bin/console doctrine:migrations:migrate`


### _How To Use:_
 - On the left site create your first board and click on it
 - Create your board categories and move by <- or -> buttons to the right place
 - Create a task by "Dodaj" button on the category you want to place.
 - Move tasks by dragging and dropping

Stack:
1. PHP - 8.5
2. Symfony - 8.1
