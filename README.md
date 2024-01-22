# PHP-BFInterpreter
***Brainf-ck* interpreter written in php**  
Interprete your *Brainf-ck* codes easily with php

---

## Requirement
- PHP version 7.0 or later

---

## Quick start
`Interpreter.php` is the most important file here. Place the file in your root folder (or anywhere you want) and make a `index.php` file.  
A sample `index.php` file is attached with this repo.

**Here is an example on how to start with:**
```php
<?php
require_once 'path/to/Interpreter.php';

$intp = new Interpreter();
$intp->addCode(",[.>,]"); // Add codes to the interpreter
$intp->addInput("a simple stdin-text"); // Add inputs for your codes

try {
  $intp->execute(); // Execute added codes. This will throw exceptions, so put this in a try-catch block
  echo $intp->getOutputString(); // Get output string of your codes
} catch (Error $e){
  echo $e->getMessage();
}
```

---

<s>Sorry. Can't get you a live environment for this project :l</s>

&copy; **Monzurul Hasan**
