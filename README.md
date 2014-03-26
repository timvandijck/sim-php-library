# Selligent Interactie Marketing PHP Client
A library to help you interact with the SIM (Selligent Interactive Marketing) Soap webservice.

# Usage
## Creating a user
To use the web service, it’s mandatory to create an ‘automation’ user in the SELLIGENT Manager. It is this 
user that will be used for authentication in the API calls. 

To do so, go to User/Group Management and create a new user, give it a name and password and only 
rights to “automation”.

## Setup
To setup the library include the file of the PHP-class you need (BroadcastClient.class.php to use the Broadcast API, IndividualClient.class.php to use the Individual API).

You also have to create a configarray with your webservice url and credentials.

```
require_once('IndividualClient.class.php');
require_once('BroadcastClient.class.php');

$config = array(
  'individual_url' => 'http://mydomain.example/automation/Individual.asmx?WSDL',
  'broadcast_url' => 'http://mydomain.example/automation/Broadcast.asmx?WSDL',
  'login' => 'yourusername',
  'password' => 'yourpassword'
);
```

## Individual Client Methods
### Check the status
The easiest thing to check is the status of the webservice.

```
$query = new IndividualClient($config);
$result = $query->getSystemStatus();
```

### Adding a user to a list
If you want to add a user to a list you have to define the list id and add the properties to the query object. You can find the list ID in your manager by clicking the map your list is in.

```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->addProperty('NAME', 'R2D2')
                ->addProperty('MAIL', 'r2d2@gmail.com')
                ->createUser();
```

### Get a user by ID
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->setUserId(21)
                ->getUserById();
```

### Update a user
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->setUserId(21)
                ->addProperty('NAME', 'Luke Skywalker')
                ->addProperty('MAIL', 'skywalker@gmail.com')
                ->updateUser();
```

### Find a user with a filter
Get a user by defining a filter.
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->addFilter('MAIL', 'r2d2@gmail.com')
                ->getUserByFilter(); 
```

### Find users by filter
You can also get an array of user ID's by defining filters.
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->addFilter('GENDER', 'male')
                ->addFilter('COUNTRY', 'Belgium')
                ->getUsersByFilter();
```

### Find a user by constraint
Using the getUserByConstraint() method you can use statements like "LIKE".
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->setConstraint("MAIL like 'r2d2@gmail.com'")
                ->getUserByConstraint();
```

### Find a list of users by constraint
Using the getUserByConstraint() method you can use statements like "LIKE".
```
$query = new IndividualClient($config);
$result = $query->setList(295)
                ->setConstraint("MAIL like '%gmail%'")
                ->getUsersByConstraint();
```

# The future
- Test and write documentation for the Broadcast API
- Look into the new REST-api