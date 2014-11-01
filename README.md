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
The filename must match the class name expect it will need underscore case such as `user_product.class.php`. Note the `.class.php` as it's extension.

## Routers
Routes are organized in a file called `/router.php`. Routes map URL paths to controllers. To map a path, call the `add()` method and pass an HTTP path followed by the path to the controller. The following example shows how to setup three paths.

```php
Router::add('/', '/app/controllers/home.php');
Router::add('/users', '/app/controllers/users/list.php');
Router::add('/users/register', '/app/controllers/users/register/form.php');
```

With these routes, if someone navigates to `www.example.com`, the `home.php` controller will take the request. Likewise if the user visits `www.example.com/users`, the `list.php` controller will take the request.

> Note that in order for the router to take effect, we cannot have a real file located at the path that we expect the router to control. For instance we cannot have a real file at `/users`. When there is a conflict between having a real file exist and having a router path, the real file will load and not the router.

## Controllers
Controllers are the end-points for your application's HTTP requests. Even though most HTTP requests will go through the Router, the Router only serves to point to the correct Controller. Your controllers will be located under `/app/controllers`. This file shows how a basic controller works:

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

Since controllers are included by the router and not the Auto Loader, the class name doesn't need to match the filename. So naming your contollers `class Controller` is okay. The purpose of the controller is to organize the page-specific code including orchestrating Models and Views.

All controllers should extend some higher level controller, in this case we're extending `AppController`. This means that your code has a ton of features built into the controller without you having to do extra work. The `AppController` in this case sets up views and renders the views for you automatically. We'll talk about how controllers work with views later in this document.

## Views & Templates

Your application needs a way of organizing HTML templates for reuse. Views exist to organize your HTML templates into a hierarchy. And where templates consist of mostly HTML, Views also serve to provide the programming logic for it's respected template. Each View will be associated with just one template.





Views are how you organize your application's hierarchy of HTML Templates. Your Views will be located under `/app/views` and you will see the default View there. Where Templates consist of mostly HTML, views are the logic behind that HTML. Each View will 



Views make references to Templates which are located under `/app/templates`. Templates are PHP files with mostly HTML content and will be loaded by the Views directly (without the Auto Loader) so they do not follow the naming convention we mentioned before. 



The `main.php` template is where you should look first. It is the main template that will hold all other templates. You will notice a lot of work done here for you, but feel free to modify as needed. 




Views work by nesting `View` objects within each other. Each `View` object must contain the path to one template. We will learn more about how Controllers use views later in this document.









Your controller will have access to the `View` object through a variable called `$this->view`. The `View` Object will give you access to that view's respective template. You can pass variables to a view's template as follows:

```php
$this->view->welcome = 'Welcome to MVC';
```

Note that passing variables to `$this->view` will make those variables available in the `main.php` template. Further, all output generated by the controller will turn into a variable called



















-- controllers --


You will often need to pass variables from the controller to the views. This is done by doing `$this->view->varname` where `$this` is the controller, `$this->view` is the main view,  and `varname` is the varable name in the view. For instance:

This will pass a variable called `$welcome` to the `main.php` view. 
```php
$this->view->primary_header->welcome = 'Welcome Student!';
```
This however, will pass a variable called `$welcome` to the `primary_header.php` view. Note that these two variables called `$welcome` do not collide with each other since each view will be in its own scope.

With these respective views you would just need to do a php echo for the variable as this sample view implies:
```php
<h3><?php echo $welcome; ?></h3>
```

### Page Specific Content

One extra note: All output from the controller is captured and placed in a varialbe called `$main_content`. You will see this variable being echo'ed in the `main.php` view. Further, if you want to pass variables from the controller to the "Page Specific Output" at the bottom of the controller's page, then you will also need to use `$this->view->...`. For example, if you wanted to pass a variable called $welcome to the "Page Specific Ouput" then the following would serve as a good example:

```php
<?php

// Controller
class Controller extends AppController {
	public function init() {
		$this->view->welcome = 'Welcome to MVC';
	}
}
$controller = new Controller();

// Extract Main Controler Vars
extract($controller->view->vars);

?>

<h3><?php echo $welcome; ?></h3>
```

## Models

...








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




