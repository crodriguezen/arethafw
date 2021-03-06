# ArethaFW
A PHP Library for common Web applications.

Although there are various frameworks focused on the correct use of patterns, methodologies, and any good practice that speeds up the development of a Web application, the reality is that you always end up carrying out a series of repetitive tasks between different projects. Additionally, in legacy projects, there is the task of migrating existing functionalities to the different frameworks to make the functions compatible, which is why to reduce our development time required a non-restrictive solution.


- - - -

Some of the main features includes:


* Multiple database access (PostgreSQL, MySQL)
* Server side form validation
* Session handling
* User access handling

- - - -

## Initialization

***Server Side***

Includes the Aretha.php file
```php
include 'arethafw/Aretha.php';
```

**Client Side (HTML)**

Aretha adds some functionality on the client side.
<br/>
Add CSS and JavaScript to your Views.

```html
<link rel="stylesheet" href="arethafw/css/aretha.css">
<script src="arethafw/js/aretha.js"></script>
```

## Web forms validation

### From ajax request with json response

**Server side script:**

```php
include '../arethafw/Aretha.php';

$response = array(
  'status' => "fail", 
  'code' => "001",
  'message' => "",
  'mandatory' => array(),
  'fieldok' => false
);

$fields = array(
  array("name" => "string_field"  , "mandatory" => "Y", "type" => "String"),
  array("name" => "string_field2" , "mandatory" => "N", "type" => "String", "min_length" => 7),
  array("name" => "phone_field"   , "mandatory" => "Y", "type" => "Phone" , "min_length" => 7, "max_length" => 13),
  array("name" => "optional_field", "mandatory" => "N", "type" => ""),
  array("name" => "email_field"   , "mandatory" => "N", "type" => "Email"),
  array("name" => "zipcode_field" , "mandatory" => "N", "type" => "Zipcode", "min_length" => 5, "max_length" => 5)
);

$validate = Aretha::validateParams($fields, "", "");
$response['status']    = $validate['status'];
$response['code']      = $validate['code'];
$response['message']   = $validate['message'];
$response['mandatory'] = $validate['mandatory'];
$response['fieldok']   = $validate['fieldok']; // Found errors
$response['range']     = $validate['range']; // Range errors
$response['type']      = $validate['type']; // Type errors

if ($response['fieldok']) {
  // All OK - Do something with data
} else {
  // Something its wrong - Feedback the user
}

header("Content-type:application/json");
echo json_encode($response);
```

**Response example (mandatory error):**

```json
{
  "status":"fail",
  "code":"P002",
  "message":"Parámetros obligatorios incompletos",
  "mandatory":["string_field","string_field2"],
  "fieldok":false,
  "range":[],
  "type":[]
}
```
**Response example (type error):**

```json
{
  "status":"fail",
  "code":"P003",
  "message":"Parámetros con el tipo incorrecto.",
  "mandatory":["email_field"],
  "fieldok":false,
  "range":[],
  "type":[
    {
      "name":"email_field",
      "detail":"[E-mail Min: 5]"
    },
    {
      "name":"email_field",
      "detail":"[E-mail must have an @]"
    }
  ]
}
```

## Common functions

<details>
<summary><strong>Session start</strong></summary>
<p>
<pre>
Aretha::sessionStart();
</pre>
</p>
</details>

<details>
<summary><strong>Check if session is granted</strong></summary>
<p>
<pre>
Aretha::sessionGranted();
</pre>
</p>
<p>
<strong>Example</strong>
<pre>
if (Aretha::sessionGranted()) {
  // Access granted
} else {
  // Access denied
  header('Location: login.php');
}
</pre>
</p>
</details>

<details>
<summary><strong>Display all errors</strong></summary>
<p>
<pre>
Aretha::allErrors();
</pre>
</p>
</details>

<details>
<summary><strong>Load PlugIn</strong></summary>
<p>
<pre>
Aretha::loadPlugin('name');
</pre>
<strong>Note:</strong> Plugin invocation must be at the end of the body of HTML Document
</p>
</details>



