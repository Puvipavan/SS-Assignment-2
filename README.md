# Software Security(IE5042) - Assignment 2
---

## Deploy the Application

### Method 1 (Using WAMP/LAMP Stack)

#### Dependencies:
- Apache/Nginx Web Server
- PHP >= 7.4
- MYSQL >= 8.0 or MariaDB >= 10.4
- Apache with mod_rewrite module / Nginx with WordPress specific configuration

#### Steps:
- Copy files to web root directory
- Navigate to http://your-domain/wp-admin/install.php
- Then follow the instructions


### Method 2 (Using Docker)

#### Dependencies:
- Docker

#### Steps:
- If port 8000 is already being used by another process, then change the port in docker-compose.yml file
- Run following command: `docker compose up`
- Then navigate to http://localhost:8000/wp-admin/install.php
- Database details:
    - Database Name: wordpress
    - Username: wordpress
    - Password: wordpress
    - Database Host: database
    - Table Prefix: wp_
- Then follow the instructions

---
###### Note: This is a vulnerable application and all the plugins are activated using a mu-plugin which can be found at [wp-content/mu-plugins/activate.php](wp-content/mu-plugins/activate.php)