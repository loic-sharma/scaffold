# Scaffold Laravel Bundle

Easily manage CRUD operations on your database.

## Installation

Install using the Artian CLI:

	php artisan bundle:install scaffold
	php artisan bundle:publish

## A Few Examples

### Editing Existing Tables

Go check out the **scaffold** route. That was easy.

### Creating a new Table

Say you wanted to make a blog that contained posts. You wanted to be able
to create, edit, and delete these posts. You could code all of that, or you
could just run:

	php artisan scaffold::make post title:string content:text timestamps
	php artisan migrate

The migration, model, controller, and views will all be automatically generated.
Neat, huh?