# Bootcamp MVC
The bootcamp MVC framework is intended to give the students a simple MVC starting point to develop their in-class projects with. This guide will help you navigate your way through the framework and learn its technique.

# Database Setup.
Modify the constants in the file `/app/app_settings.php` to reflect your database credentials. Some of the example code provided for you depends on a `user` table existing in that database. You can use the `/database.sql` SQL file to create that table. Note that you can change this table as needed but the examples might depend on the original schema of this table

# SQL Statements

The `db` object allows you to pass SQL statements and it returns a [mysqli result](http://php.net/manual/en/class.mysqli-result.php) object as seen below:

```php
$user_id = 1;

// SQL
$sql = "
	SELECT *
	FROM user
	WHERE user_id = '{$user_id}'
	";

// Execute
$results = db::execute($sql);
```

To prevent SQL Injection Attacks, variables should always be cleansed before you place them into an SQL statement by doing escaping. In the above example, we didn't escape the $user_id but that's because we are sure the value is equal to `1`. If there's even a small chance that the value you want to pass into your SQL statement contains data that the user could have influenced, then you need to do escaping as follows:

```php
$user_id = db::escape($_GET['user_id']);

// SQL
$sql = "
	SELECT *
	FROM user
	WHERE user_id = '{$user_id}'
	";

// Execute
$results = db::execute($sql);
```
In this case the `user_id` came from the HTTP/GET request and therefore it cannot be trusted. Escaping the value before we put it in the SQL statement will make it safe.

## db::insert(`table`, `values`);
Insert statements can be made by using `db::insert()` with a table name and an associative array:

```php
$insert_values = [
	'first_name' => 'John',
	'last_name' => 'Smith',
	'email' => 'john@smith.com',
	'datetime_added' => 'NOW()'
];

$insert_values = db::auto_quote($insert_values, ['datetime_added']);

// Insert
$results = db::insert('user', $insert_values);

// The results object given to us after the insert will have certain
// qualities that we might want, such as the recent Insert ID
$user_id = $results->insert_id;
```
Notice that before the associative array is ready for SQL, we need to apply a method called `db::auto_quote()` to the associative array. The Auto Quote method will prepare any values by adding quotes to it. For instance if we want to insert a value called `John`, what we really need is `'John'` (with the single quotes around it). That's what Auto Quote will do. But let's say we pass a varialbe into our associative array and the value of that variable might be null such as:
```php
$insert_values = [
	'first_name' => $_POST['first_name']
];
```
What if the `$_POST['first_name']` has no value? You might think that the Auto Quote method would produce `''`. But what we want for MySQL is `NULL`. Auto Quote takes care of that for you and chooses `NULL`.

Also note that the Auto Quotes will do escaping for you.

What if you want a MySQL function to be applied to a column such as the `NOW()` function? In this case we don't want to pass `'NOW()'` to MySQL, we want to pass `NOW()`. So when we use the `db::auto_quote()` method above, you'll notice we can pass an array as a second argument. The purpose of this optional second argument (array) is to supply a list of key names we don't want the Auto Quote logic to apply to.

## db::update(`table`, `values`, `where`);
Updates work almost exactly like Inserts but with a third argument passed:
```php
db::update('user', $insert_values, "WHERE user_id = '1'");
```
The third argument of `db::update()` allows you to write the SQL's `WHERE` statement. Be sure to also do `db::auto_quote()` on values before they're passed in.

## db::insert_duplicate_key_update(`table`, `values`);

This method will attempt an INSERT statement but if the statement fails due to the key already existing, then the statement turns into an UPDATE statement.

# MVC
All of your Models, Views, and Controllers will be created in a the `/app` folder (in the respective models, views, and controllers folders). The framework has an Auto Loading feature which means you won't need to manually include classes that exist in the `/app` folder. But in order for it to work files must follow a specific naming convention. Each file should have one class and it's class name needs to be title-case as follows:
```php
// Notice the name of the class UserProduct in title-case
class UserProduct extends Model {
  ...
}
```
The filename must match the class name expect it will need underscore-case such as `user_product.class.php`. Note the `.class.php` as it's extension.

## Routers
The purpose of a Router is to capture HTTP requests and route them to Controllers. There is a file on the web root called `.htaccess` which tells all HTTP requests that are for files that don't exist, to go to the router located at `/router.php`. To create a route, call the `add()` method and pass a URL path followed by the path to the controller. The following example shows how to setup three routes.

```php
Router::add('/', '/app/controllers/home.php');
Router::add('/users', '/app/controllers/users/list.php');
Router::add('/users/register', '/app/controllers/users/register/form.php');
```

With these routes, if someone navigates to `www.example.com`, the `home.php` controller will take the request. Likewise if the user visits `www.example.com/users`, the `list.php` controller will take the request.

> Note that in order for the routes to work, we cannot have a real file located at the URL path. For instance we cannot have a real file at `/users`. When there is a conflict between having a real file exist and having a route path, the real file will load and prevent the router from loading entirely.

## Controllers
Controllers are the end-points for your application's HTTP requests. The Router will be the first point of entry for PHP, but it will soon hand the control over to the Controller depending on which URL was requested. Your controllers will be located under `/app/controllers`. This file shows how a basic controller works:

```php
<?php

// Controller
class Controller extends AppController {
	public function init() {
		// Page code goes here
	}
}
$controller = new Controller();

// Extract Main Controler Vars
extract($controller->view->vars);

?>

<!-- Page specific HTML goes here -->
```

> Note that you will need an `init()` method in your Controller. This method will be called when the Controller Starts. This method should hold your page-specific code.

Since controllers are included by the router and not the Auto Loader, the class name doesn't need to match the filename. So naming your contollers `class Controller` is okay. The purpose of the controller is to organize the page-specific code including orchestrating Models and Views.

All controllers should extend some higher level controller, in this case we're extending `AppController`. This means that your code has a ton of features built into the controller without you having to do extra work. The `AppController` in this case sets up views and renders the views for you automatically. We'll talk about how Controllers work with Views later in this document.

## Views / Templates

Your application needs a way of organizing HTML templates for reuse. Views exist to organize your HTML templates into a hierarchy. While templates mostly consist of HTML, Views also serve to provide the programming logic for it's respected template (each View will be associated with just one template).

Views and Templates will exist under `/app/views` and `/app/templates` respectively. You will notice a Default View and some templates already setup for you. These are meant to be customized depending on your application's needs but serve as starting points to help you learn how Views and Templates work. By explpring the Default View, you will notice it associates itself with the `master.php` Template and then it creates Sub Views within itself. This is how the hierarchy is created. When the Default View gets rendered, it will also render all of its Sub Views.

If you open the `master.php` Template, you will notice a place where it outputs the contents of the `primary_header.php` Template. You will also notice were it outputs the contents of the a variable called `$main_content`. The Main Content variable will consist of "page specific content" for each page. Let's explpore how Controllers work with Views and how to make "page specific content" in the next section.

## Controllers and Views

Your Controller will have access to the Default View's hierarchy through a variable called `$this->view`. This variable is provided by `AppController`. 

You can send variables from the Controller to the View/Sub View objects as follows:

```php
// Pass a varialbe called 'foo' into the Master Template
$this->view->foo = 'hello';

// Pass a variable called 'bar' to the Primary Header Template
$this->view->primary_header->bar = 'world';
```

The View objects are smart and know when you're trying to access a View/Sub View vs when you're trying to make a variable. In this case `$this->view->foo` knows that there isn't a Sub View called `foo` and therefore you're making a new variable. In the case of `$this->view->primary_header->bar `, it knows that `primary_header` is a Sub View of the Default View and therefore allows you to pass new variables into that Sub View like `bar`. 

Since `$this->view` corresponds directly to the Default View, and the Default View loads the `master.php` Template, passing variables to `$this->view` will make those variables available in the `master.php` Template.

Besides passing variables into specific Views/Sub Views, you should note that all output created in your controller will be collected and turned into a variable called `$main_content` (inside the `master.php` Template)

Let's take a look at that basic setup for a Controller again:

```php
<?php

// Controller
class Controller extends AppController {
	public function init() {
	
		// Page code goes here
		$this->view->title = 'Hello World';
	}
}
$controller = new Controller();

// Extract Main Controler Vars
extract($controller->view->vars);

?>

<h1><?php echo $title; ?></h1>
```

Since any output from the controller gets turned into the `$main_content` variable on the `master.php` Template, you can see here that our `$main_content` will consist of an `<h1>` tag. But also notice that in order to pass information from the `init()` method of our Controller down into the output, we also have to use `$this->view` as well.

## Router to Controller to View

Now that we've talked about these parts in detail, let's review how they all work together.

**First,**  the `/router.php` file takes control of the HTTP request. Let's say the user visited `www.example.com`. Since the router has this line of code, the Home Controller will start up:

```php
Router::add('/', '/app/controllers/home.php');
```

**Second,** the Home Controller starts and extends the `AppController`:

```php
class Controller extends AppController {
...
```

**Third,** the `AppController` sets it the View it wants to use, in this case it's the Default View at `default_view.class.php`. This Default View is associated with the `master.php` Template and sets up Sub Views which will be associated with respective Templates.

**Fourth,** the Controller's `init()` method will be called allowing the page-specific code to run inside the `init()`. This code will need to send any output to the Views by using the variable: `$this->view`.

**Fifth,** when the script ends, the Controller is already engineered to call the View's render methods and output the application to the client.

## Models

Where Routers, Controllers, and Views all work to create the output; Models serve as a middle layer between the Controllers and the Database. Models are saved in `/app/models` and are created as classes that extend the `Model` class:

```php
class User extends Model {
}
```

### Naming
Models directly correspond to database tables and are required to be named similarly to their respective table. Database tables should be named with all lowercase and underscore's for spaces. Models are the same but with Title-Case and without spaces. For instance a table name of `user_product` would require it's Model to be named `UserProduct`.

The Model will anticipate that the name of the Primary ID on the table is the same as the table name, but with `_id` added. So for instance the `user_product` table should have a Primary ID of `user_product_id`. However this can be easily overriden by providing a custom Primary ID:

```php
class UserProduct extends Model {
	protected static $table_id = 'id';
}
```

### Usage

Just by merit of extending the `Model` class, your Model is already very powerful. With no methods created whatsoever, we can do this with our User Model we created in the first example:

```php
$user_id = 1;
$user = new User($user_id);
echo $user->first_name; // Outputs "Lindsey"
```

Notice how we can instantiate a new User object by passing in the User ID. By doing this the Model will perform the nessesary SQL statement to get all the information about User:1. If you used the `database.sql` file to start your database then you'll have a user table already with two users inserted. 

The User Model we created can opperate with no methods to get user information as we just saw. However you should add methods to your Models as nessesary for your application. Methods inside of Models should serve to perform operations on the Model data. For instance if you want to insert, update, or delete records from the database, you should create a Model that represents a database table and create methods to perform those actions inside the Model

#### Updating

To update a record in your database, create a Model for the table (such as the User Model) and then create a method similar to this:

```php
public function update($input) {

	$sql_values = [
		'first_name' => $input['first_name'],
		'last_name' => $input['last_name'],
		'email' => $input['email'],
		'password' => $input['password']
	];

	$sql_values = db::auto_quote($sql_values);
	db::update('user', $sql_values, "WHERE user_id = {$this->user_id}");
	return new User($this->user_id);

}
```

Based on previous documentation, you should be familiar with how to use the `db` object to do an UPDATE statement. Notice that this method takes an associative array for `$input`. Each input variable corresponds to a database field. This pattern isn't required but just serves as an example.

Imagine we have an `init()` method of a Controller. Also imagine that this controller receives a form request. This example shows how to use the `update` method we just created:

```php
...
public function init() {

	// Validate the $_POST data first

	// Get the User ID from the $_POST
	$user_id = $_POST['user_id'];
	
	// Start a new User Object
	$user = new User($user_id);
	
	// Update the User
	$user = $user->update($_POST);
}
...
```





#### Inserting















# NPM, Gulp, Sass, and Bower
To use this MVC framework you may want to take advantage of NPM (Node Package Manager), Bower, Gulp, and Sass. All of these things are optional and you can create a project with this framework without these tools. However, these tools are AWESOME and will make your life easier with development.

## NPM for Ubuntu
Install Node
```sh
$ sudo apt-get install nodejs
```
Fix Node. With Ubuntu Step 3 might not work until you do this fix. So try Step 3 first, then if it doesn't work, do Step 2 then Step 3
```sh
$ sudo ln -s /usr/bin/nodejs /usr/bin/node
```

## NPM for OSX
Install Homebrew (a similar tool to Ubuntu's apt-get)
```sh
sudo ruby -e "$(curl -fsSL https://raw.github.com/Homebrew/homebrew/go/install)"
```
Use Homebrew to install Node
```sh
$ brew install node
```

## NPM Usage
Having NPM installed allows you to use already-made Node applications (called Modules) on your project. Your project will store the Node Modules in folder called `node_modeules`. The instructions for installing node modules are written in a file called `package.json`. To install the Node Modules issue the following bash command:
```sh
$ npm install
```

## Using Gulp and Sass
Assuming you installed NPM in the previous steps, you can now use Gulp to watch for Sass changes. Run the following command in bash
```sh
$ gulp
```
Issuing this command tells gulp to follow the instructions in the `gulpfile.js`. That file instructs Gulp to watch for changes to any `.scss` files in the `/css` folder and to turn them into `.css` files. As long as your Gulp program is running, this CSS transformation will take place. You can turn off the Gulp watch script by typing CTRL+C

## Bower
Assuming you installed NPM in the previous steps, you can now install Bower globally on your computer with the following command:
```sh
$ sudo npm install -g bower
```
Where NPM will install Node Modules into a foler, Bower will install Bower Components into a folder called `bower_components`. And just like doing `npm install` will look in the `package.json` for what to install, Bower will look in `bower.json` to see what it needs to install. In this case we will be installing jQuery, Modernizr, and ReptileForms. The command to tell bower to install the tools listed in the bower file is:
```sh
$ bower install
```
Your bower components will now be installed in your `bower_components` folder.




