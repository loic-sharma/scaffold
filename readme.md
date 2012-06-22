# Scaffold Laravel Bundle

Easily create scaffolds to manage your database.

## Installation

Install using the Artian CLI:

	php artisan bundle:install scaffold
	php artisan bundle:publish

Then, place the bundle's name in your **application/bundles.php** file.

```php
<?php

return array(

	'scaffold',
);
```

Don't forget that each time you generate a scaffold, you will need to add the
Controller in your routes. Or, simply use this:

```php
<?php

Route::controller(Controller::detect());
```

## A Few Examples

### Creating a new Table and Scaffold

Say you want to make a blog that contains posts, which you want to be able
to create, edit, and delete. You could manually code all of that, or you
could just run:

	php artisan scaffold::make post title:string content:text timestamps
	php artisan migrate

The migration, model, controller, and views will all be automatically generated.
Now isn't that a bit faster?

Notice how each of the fields are listed after the table's name, which is in singular form.
The field's name is provided first, followed by it's type. All of Laravel's different
field types from the Schema are supported.

If you wish your scaffold to automatically timestamp, simply add timestamps to the end of
the command. 

### Using an existing Table to create a Scaffolding

Note: This is not implemented yet.

To create a new scaffold on an already existing table, simply run:

	php artisan scaffold::make post

Notice that the singular form of the table name is given.