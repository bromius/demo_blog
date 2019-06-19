# "Simple blog" project
### Especially written for source code inspection

---

###### Includes:

- PHP7 benefits
- Simple MVC model
- Namespaces and autoload
- Exceptions and errors handling
- PSR friendly
- AJAX based interactions
- CSRF checks
- Mostly DocBlock commented

---

###### Installation:

1. Create database "demo_blog"
2. Import database content from resources/db.sql
3. Create own project config using example file Application/config/development.example.php
	- For OS Windows (default development environment) create Application/config/development.php
	- For OS Linux (default production environment) create Application/config/production.php
4. Create virtual hosts for application and static files
	- {yourdomain} with home directory init/www/
	- static.{yourdomain} with home directory init/static/
5. Apply/reload server configuration and check {yourdomain} in your browser

---

![Screenshot](https://github.com/bromius/demo_blog/blob/master/resources/screenshot.png)

---

###### (c) Kinweb