<?php

/**
 * Brainf*ck interpreter index file
 * 
 * PHP version 7.* or later
 * 
 * @category  Interpreter
 * @package   BFInterpreter/IndexFile
 * @author    Monzurul Hasan <monzurulhasan@gmail.com>
 * @copyright 2022 Ru-Hub
 * @version   v1.0
 * @link      https://github.com/mhs003/PHP-BFInterpreter
 * @since     File available since Release 1.0
 * 
 * ***************************************
 * Created By Programmer in the Shell
 * File: index.php
 * Date: 24-Jan-22
 * Time: 2:05:07 PM
 */

include "Interpreter.php";

$interpreter = new Interpreter();
$interpreter->addCode(trim("
# Hello World program
++[>++[>+++<-]<-]
>>[<<+>>-]<<++
[>+++[>++>++>+++>+<<<<-]>->+>->-<<<<<-]
>>++.>+++.>----..+++.>++++.<<--------------.>.+++.------.--------.>+.

# [[-]<]< # Remove comment to clear data pointer memory
"));
// $interpreter->addInput("stdin-text");
try {
    $interpreter->execute();
    echo $interpreter->getOutputString();
    echo "<br><br>Data Pointer Position: " . $interpreter->getCurrentDataPointer();
    echo "<br><br>Data Pointers:<pre>";
    print_r($interpreter->getDataPointers(20));
    echo "</pre>";
} catch (Error $e) {
    echo "Error: " . $e->getMessage();
}
