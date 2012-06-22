# Scaffold Laravel Bundle

Easily create scaffolds to manage your database.

## Installation

Install using the Artian CLI:

	php artisan bundle:install scaffold
	php artisan bundle:publish

Don't forget that each time you generate a scaffold, you will need to add the
Controller in your routes. Or, simply use this:

```php
<?php

Route::controller(Controller::detect());
```

## A Few Examples

### Creating a new Table and Scaffold

Say you wanted to make a blog that contained posts. You wanted to be able
to create, edit, and delete these posts. You could code all of that, or you
could just run:

	php artisan scaffold::make post title:string content:text timestamps
	php artisan migrate

The migration, model, controller, and views will all be automatically generated.
Neat, huh?

### Using an existing Table to create a Scaffolding

Note: This is not implemented yet.

To create a new scaffold on an already existing table, simply run:

	php artisan scaffold::make post

Notice that the singular form of the table name is given.