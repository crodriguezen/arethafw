# arethafw
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


