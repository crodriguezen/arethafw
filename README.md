# arethafw
A PHP Library for common Web applications


- - - -

Some of the main features includes:


* Multiple database access (PostgreSQL, MySQL)
* Server side form validation
* Session handling
* User access handling

- - - -

## Initialization

<strong>Server Side</strong>
```php
include 'arethafw/Aretha.php';
```

<strong>Client Side (HTML)</strong>

Aretha adds some functionality on the client side. 
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


