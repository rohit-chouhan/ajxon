# Ajxon
A Json to Ajax UI Generator for Dynamic Work, with PHP, Ajxon framwork provides Ajax, PHP, HTML Form Code from JSON Structor. You can generate complete Dynamic Ajax platform from JSON.

### Ajxon Objects
Objects | Use 
--- | --- |
url | PHP back-end page name, where database queries will generate |
form | HTML form name |
table | Database's Table name where Data will be stored/insert |
type| Ajax Data return type |
include| Name of folder, where **url** will be saved (ex. foldername/save.php), if you leave it blank. it will store in root folder |
field | HTML form's Input name & Database Field Name (You can add multiple Object for Input) |
| | Ex. **"db_field_name" : " input_name : input_type : "true"** |
| | True -> Input Field Required |
| | False -> Optional |
db | Database connectivity information, you have to provide database HOST, USERNAME, PASSWORD, and DATABASE name |

## Sample Json Code
Here the sample JSON objects to you can access and provides identifiers.
```json
{
    "url": "save.php",
    "form": "first_form",
    "table": "users",
    "type": "text",
    "include":"contents",
    "field": {
        "name": "name:text:false",
        "email": "email:email:true",
        "password":"pass:password:true"
    },
    "db": {
        "host": "localhost",
        "username": "root",
        "password": "Codesler@321",
        "database": "codesler"
    }
}
``` 
## Future Code
Automatically generated code after using Ajxon
Code | Output
--- | --- |
HTML | ![HTML CODE](https://i.ibb.co/rFNhKGj/html.png) |
JS/Ajax | ![JS CODE](https://i.ibb.co/wJhZ9ft/js.png) |
PHP | ![PHP](https://i.ibb.co/D80pw3N/php.png) |

## Documention
Download Ajxon and Copy Ajxon.php to your root folder in project. create new page or include Ajxon.php **[require "Ajxon.php";]** in exist page. create obj of **[new Ajxon()]** and have fun!

Function | Use 
--- | --- |
$obj->set(<json>) | the function can send JSON to Ajxon Class.
$obj->html() | return the all HTML Form & Input to Page
$obj->js() | return the Js/Ajax Code to Page
$obj->php() | this function not return any code, the function can only generate code and save automatically to given **"include"** (folder) by user in JSON
    
## Example Complete Code
Here the complete sample code of PHP.

```php
<?php
    require 'Ajxon.php'; //including Ajxon
    $obj=new Ajxon(); //Creating Ajxon object
    $x='{
    "url": "data.php",
    "form": "adds",
    "table": "users",
    "type": "text",
    "include":"contents",
    "field": {
        "name": "nameinput:text:false",
        "email": "emailinput:email:true",
        "password":"passinput:password:true"
    },
    "db": {
        "host": "localhost",
        "username": "root",
        "password": "",
        "database": "xxxx"
    }
}'; //Json Code

$obj->set($x); //Send JSON Code to Ajxon

$obj->php(); //Save (Database's) php file in Back-end

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sample Ajxon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php $obj->html();?>
    <!-- Getting HTML Input/Button -->
</body>
    <?php $obj->js();?>
    <!-- Getting JS/Ajax Code -->
</html>
```

## License
[MIT](https://choosealicense.com/licenses/mit/) License

## Developers
This framework is developed by [Rohit Chouhan](https://facebook.com/itsrohitofficailprofile), Rohit Chouhan is the co-founder and CEO of [Codesler](https://g.co/kgs/1jTqhr), a company that's offering digital marketing services and an accomplished web developer.
