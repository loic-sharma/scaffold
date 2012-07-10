# Scaffold Laravel Bundle

Easily create scaffolds to manage your database.

## Installation

Install using the Artisan CLI:

	php artisan bundle:install scaffold
	php artisan bundle:publish

Then, place the bundle's name in your **application/bundles.php** file:

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

### Creating a full Blog... in seconds!

Say you want to make a blog that contains posts that are posted by users. You could manually code all of that, or you
could just run:

	$ php artisan scaffold::make blog.comment content:text belongs_to:blog.post,user timestamps
	$ php artisan migrate

	$ php artisan scaffold::make blog.post title:string content:text belongs_to:user has_many:blog.comment timestamps
	$ php artisan migrate

	$ php artisan scaffold::make user username:string password:string has_many:blog.post,blog.comment
	$ php artisan migrate

Now isn't that a bit faster?

### Dissecting the Blog Example

This is the basic structure to generate a new scaffold:

	php artisan scaffold::make <name> <attributes> <relationships> <timestamps>

`name`: The scaffold's name (always singular). Notice that the scaffold can be
nested by adding a prefix and a period.

`attributes`: The scaffold's attributess, separated by a space. The
different supported attribute types are: _string_, _integer_, _float_,
_boolean_, _date_, _timestamp_, _text_, and _blob_. The general syntax for an
attribute is:

	name:type
	
	title:string

Additionally, you can make a attribute nullable:

	name:type:nullable
	
	title:string:nullable

Or, you can set a character limit for an attribute:

	name:type:length

	title:string:255

You can even set a character limit and make an attribute nullable:
	
	name:type:length:nullable
	
	title:string:255:nullable

`relationships`: The relationships between this scaffold and other scaffolds,
separated by a space. The different supported relationships are: _has_one_,
_has_one_or_many_, _belongs_to_, _has_many_, and _has_many_and_belongs_to_.
The general syntax for a relationship is:

	relationship:scaffold
	
	has_many:post

Note that the scaffold is **always** in its singular form. Additionally,
several similar relationships may be defined at the same time using a comma
(but no spaces):

	relationship:scaffold1,scaffold2
	
	has_many:post,comment

It is important to note that the different scaffolds listed within a
similar relationship should be ordered by importance. For example, a post
is more important than a comment. Additionally, if the scaffold is nested it
should be prefixed like so:

	has_many:blog.post,blog.comment

`timestamps`: If included, this will make the scaffold automatically timestamp
when rows are created or updated. If `timestamps` is omitted, the scaffold
will not do this.