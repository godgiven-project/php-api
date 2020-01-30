Readme php-api
===============================
PHP-API using the fat-free framework to building a rest-api

## project structure
<pre>
lib/
app/
    controllers/
    languages/
    model/

class/
    email/
    json/
    sms/
    translate/
    date_j.php
    master_db.php

config/
    ggn_config.php
    ggn_acl.php
    
ggn_permission.php
ggn_routes.php
ggn_cloud.php
ggn_load.php
index.php
</pre>

you can define any routes as you like in `ggn_routes.php` as follows:

$f3->route('Method Rout','Class->Function');
- Method: GET|POST|PUT|delete
- Rout: start by `/`
- Class: PHP Class
- Function: function of your Class

Mapper is a fatfree DB class  
-------------------------------
ggn_Mapper is godgiven_Mapper class that extends of original Mapper 
and able to change between Databases if you use master and slave options
