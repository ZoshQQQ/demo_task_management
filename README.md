Demo API Task management with JWT token authenticate by Symfony
========================

"Demo API Task management" is an application created to show
how it works [API Platform][1] with JWT authenticate.

Requirements
------------

  * PHP 8.1.0 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][2].

Installation
------------

There are 2 different ways of installing this project depending on your needs:

**Option 1.** [Download Symfony CLI][4] and check `symfony` binary installed
on your computer to run this command:

```bash
$ symfony php -v
```

**Option 2.** [Download Composer][6] and use the `composer` binary installed
on your computer to run these commands:

```bash
# clone the code repository and install its dependencies
$ git clone https://github.com/ZoshQQQ/demo_task_management
$ cd demo_task_management/
$ composer install
```

Usage
-----

There's no need to configure anything before running the application. There are
2 different ways of running this application depending on your needs:

**Option 1.** [Download Symfony CLI][4] and run this command:

```bash
$ cd demo_task_management/
$ symfony serve
```

Then access the application in your browser at the given URL (<http://localhost:8000> by default).

**Option 2.** Use a web server like Nginx or Apache to run the application
(read the documentation about [configuring a web server for Symfony][3]).

On your local machine, you can run this command to use the built-in PHP web server:

```bash
$ cd demo_task_management/
$ php -S localhost:8000 -t public/
```

**Option 3.** Add migrations and run this command.:

```bash
$ symfony console doctrine:migrations:migrate
```
Usage API
-----

**- Show API.** Open URL <http://localhost:8000/api>

**- How to add user.** Run command:
```bash
$ symfony console app:add-user
```
**- How to get JWT token.** Run command:
```bash
$ curl -X POST --location "http://localhost:8000/api/login_check" \
    -H "Content-Type: application/json" \
    -d "{
          \"username\": \"usermail@gmail.com\",
          \"password\": \"password\"
        }"
```

[1]: https://api-platform.com/
[2]: https://symfony.com/doc/current/setup.html#technical-requirements
[3]: https://symfony.com/doc/current/setup/web_server_configuration.html
[4]: https://symfony.com/download
[6]: https://getcomposer.org/
