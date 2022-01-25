<?php

/**
 * Brainf*ck interpreter class file
 * 
 * Help's to interprete Brainf*ck codes from web easy and fast
 * 
 * PHP version 7.* or later
 * 
 * @category  Interpreter
 * @package   BFInterpreter
 * @author    Monzurul Hasan <monzurulhasan@gmail.com>
 * @github    https://github.com/mhs003
 * @copyright 2022 Ru-Hub
 * @version   v1.0
 * @link      https://github.com/mhs003/PHP-BFInterpreter/blob/main/Interpreter.php
 * @since     File available since Release 1.0
 * 
 * ***************************************
 * Created By Programmer in the Shell
 * File: Interpreter.php
 * Date: 24-Jan-22
 * Time: 2:05:18 PM
 */


/**
 * Help's to interprete Brainf*ck codes
 * 
 * Quick example:
 * 
 * <samp>
 * 
 * $interpreter = new Interpreter();  
 * $interpreter->addCode(",[.>,]");  
 * $interpreter->addInput("this is a stdin-text");  
 * try {  
 *     $interpreter->execute();  
 *     echo $interpreter->getOutputString();  
 * } catch (Error $e) {  
 *     echo $e->getMessage();  
 * }
 * 
 * </samp>
 * 
 * PHP version 7.* or later
 * 
 * @package   BFInterpreter/Interpreter
 * @author    Monzurul Hasan <monzurulhasan@gmail.com>
 * @copyright 2022 Ru-Hub
 * @version   v1.0
 * @link      https://github.com/mhs003/PHP-BFInterpreter/blob/main/Interpreter.php
 * @since     Class available since Release 1.0
 */
class Interpreter
{
    /**
     * Input buffer
     * 
     * @var    string
     * @access private
     */
    private string $input = "";
    
    /**
     * Store user codes
     * 
     * @var    string
     * @access private
     */
    private string $code;

    /**
     * Output buffer
     * 
     * @var    string
     * @access private
     */
    private string $output = "";

    /**
     * Data buffer
     * 
     * @var    array
     * @access private
     */
    private array $buffer;

    /**
     * Data buffer pointer
     * 
     * @var    string
     * @access private
     */
    private int $bufferPointer = 0;

    /**
     * Input buffer pointer
     * 
     * @var    int
     * @access private
     */
    private int $inputPointer = 0;

    /**
     * User code pointer
     * 
     * @var    int
     * @access private
     */
    private int $codePointer = 0;

    /**
     * User code executed counter
     * 
     * @var    int
     * @access private
     */
    private int $codeExecutedCounter = 0;

    /**
     * Max code executed integer
     * 
     * @var    int
     * @static
     * @access private
     */
    private static int $MAX_CODE_EXEC_COUNT_INT = 1048576;


    /**
     * __construct magic method
     */
    public function __construct()
    {
        $numArr = array();
        for ($i = 0; $i <= 30000; $i++) {
            $numArr[$i] = 0;
        }
        $this->buffer = $numArr;
    }

    /**
     * Add user inputs to interpreter
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::addInput( string $input ) : void
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * $interpreter->addInput("YOUR_INPUT_TEXT");
     * 
     * </code>
     * 
     * @param  string $input the string to input
     * @return void
     * @access public
     * @since  Method available since Release 1.0
     */
    public function addInput(string $input): void
    {
        $this->input = $input;
    }

    /**
     * Add user codes to interpreter
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::addCode( string $code ) : void
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * $interpreter->addCode("YOUR_CODES");
     * 
     * </code>
     * 
     * @param  string $code the codes string
     * @return void
     * @access public
     * @since  Method available since Release 1.0
     */
    public function addCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Execute given brainf*ck codes
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::execute() : void
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * try {  
     *     $interpreter->execute();  
     * } catch (Error $e) {  
     *     echo $e->getMessage();
     * }
     * 
     * </code>
     * 
     * @return void
     * @access public
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    public function execute(): void
    {
        while ($this->codePointer < strlen($this->code) && $this->codeExecutedCounter < self::$MAX_CODE_EXEC_COUNT_INT) {
            $codeChar = $this->code[$this->codePointer];
            switch ($codeChar) {
                case '#':
                    $this->removeComments();
                    break;
                case '+':
                    $this->inc();
                    break;
                case '-':
                    $this->dec();
                    break;
                case '<':
                    $this->reverse();
                    break;
                case '>':
                    $this->forward();
                    break;
                case '.':
                    $this->out();
                    break;
                case ',':
                    $this->in();
                    break;
                case '[':
                    $this->loopStart();
                    break;
                case ']':
                    $this->loopEnd();
                    break;
                case ' ':
                    break;
                case "\n":
                    break;
                case "\r":
                    break;
                default:
                    throw new Error("Unexpected character '" . substr($this->code, $this->codePointer - 4, 4) . "<b>{$codeChar}</b>" . substr($this->code, $this->codePointer + 1, 4) . "' at code position <b>{$this->codePointer}</b>");
                    break;
            }
            $this->codeExecutedCounter++;
            $this->codePointer++;
        }
    }

    /**
     * Get the current pointer of data buffer after execution
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::getCurrentDataPointer() : int
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * $interpreter->getCurrentDataPointer();
     * 
     * </code>
     * 
     * @return int
     * @access public
     * @since  Method available since Release 1.0
     */
    public function getCurrentDataPointer(): int
    {
        return $this->bufferPointer;
    }

    /**
     * Get the data buffer as an array after execution
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::getDataPointer( int $limit = 0 ) : array
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * $interpreter->getDataPointers(5);
     * 
     * </code>
     * 
     * @param  int $limit limit the return array length
     * @return array
     * @access public
     * @since  Method available since Release 1.0
     */
    public function getDataPointers(int $limit = 0): array
    {
        if ($limit == 0) {
            $return_array = $this->buffer;
            while(count($return_array) > 0 && ($end = array_pop($return_array)) == 0);
            array_push($return_array, $end);
            if(count($return_array) > 0 && count($return_array) < $this->bufferPointer + 1) {
                return $this->getDataPointers($this->bufferPointer);
            }
            if (count($return_array) > 0 && count($return_array) < 6) {
                return $this->getDataPointers(5);
            }
            return $return_array;
        } else {
            $pointers = array();
            for ($i = 0; $i <= $limit; $i++) {
                $pointers[] = $this->buffer[$i];
            }
            return $pointers;
        }
    }

    /**
     * Get the output string after execution
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::getOutputString() : string
     * 
     * </code>
     * 
     * Here's an example on how to call/use the method:
     * 
     * <code>
     * 
     * $interpreter = new Interpreter();  
     * $interpreter->getOutputString();
     * 
     * </code>
     * 
     * @return string
     * @access public
     * @since  Method available since Release 1.0
     */
    public function getOutputString(): string
    {
        return $this->output;
    }

    /**
     * Increases byte at the position of the data pointer 
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::inc() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    private function inc(): void
    {
        if ($this->buffer[$this->bufferPointer] < 255) {
            $this->buffer[$this->bufferPointer] = $this->buffer[$this->bufferPointer] + 1;
            return;
        } else if ($this->buffer[$this->bufferPointer] == 255) {
            $this->buffer[$this->bufferPointer] = 256 - ($this->buffer[$this->bufferPointer] + 1);
            return;
        }
        $i = $this->codePointer;
        throw new Error("Byte at the data pointer exceed max int value; Code: <b>" . $this->code[$i] . "</b> Position: <b>{$i}</b>");
    }

    /**
     * Decreases byte at the position of the data pointer 
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::dec() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    private function dec(): void
    {
        if ($this->buffer[$this->bufferPointer] > 0) {
            $this->buffer[$this->bufferPointer] = $this->buffer[$this->bufferPointer] - 1;
            return;
        } else if ($this->buffer[$this->bufferPointer] >= -256) {
            $this->buffer[$this->bufferPointer] = 256 + ($this->buffer[$this->bufferPointer] - 1);
            return;
        }
        $i = $this->codePointer;
        throw new Error("Byte at the data pointer precede min int value; Code: <b>" . $this->code[$i] . "</b> Position: <b>{$i}</b>");
    }

    /**
     * Decreases data pointer 
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::reverse() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    private function reverse(): void
    {
        if ($this->bufferPointer > 0) {
            $this->bufferPointer -= 1;
            return;
        }
        $p = $this->codePointer;
        throw new Error("Data pointer cannot precede 0; Code: <b>" . $this->code[$p] . "</b> Position: </b>{$p}</b>");
    }

    /**
     * Increases data pointer 
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::inc() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    private function forward(): void
    {
        if ($this->bufferPointer < count($this->buffer) - 1) {
            $this->bufferPointer += 1;
            return;
        }
        $len = count($this->buffer) - 1;
        $s = $this->codePointer;
        throw new Error("Data pointer cannot exceed {$len}; Code: <b>" . $this->code[$s] . "</b> Position: </b>{$s}</b>");
    }

    /**
     * Output byte at the position of the data pointer
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::out() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     */
    private function out(): void
    {
        $this->output .= chr($this->buffer[$this->bufferPointer]);
    }

    /**
     * Take one byte from input and put it to the position of the data pointer
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::in() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     * @throws Error Code errors
     */
    private function in(): void
    {
        if ($this->inputPointer - 1 < strlen($this->input)) {
            $this->buffer[$this->bufferPointer] = !empty($this->input[$this->inputPointer]) ? ord($this->input[$this->inputPointer]) : 0;
            $this->inputPointer++;
            return;
        }
        $len = strlen($this->input);
        $p = $this->codePointer;
        throw new Error("Insufficient input supplied. Must have more than {$len} inputs;");
    }

    /**
     * If byte at the position of the data pointer is zero,
     *   then jump forward to the command after the
     *   matching ']' command
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::loopStart() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     */
    private function loopStart(): void
    {
        if ($this->buffer[$this->bufferPointer] == 0) {
            $i = 0;
            while ($this->codePointer < strlen($this->code)) {
                $this->codePointer++;
                if ($this->codePointer >= strlen($this->code)) {
                    break;
                }
                $codeChar = $this->code[$this->codePointer];
                if ($codeChar == '[') {
                    $i++;
                } else if ($codeChar != ']') {
                    continue;
                } else if ($i != 0) {
                    $i--;
                } else {
                    return;
                }
            }
        }
    }

    /**
     * If byte at the position of the data pointer is nonzero,
     *   then jump back to the command after the matching
     *   '[' command
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::loopEnd() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     */
    private function loopEnd(): void
    {
        if ($this->buffer[$this->bufferPointer] > 0) {
            $i = 0;
            while (true) {
                if ($this->codePointer <= 0) {
                    break;
                }
                $this->codePointer--;
                $codeChar = $this->code[$this->codePointer];
                if ($codeChar == ']') {
                    $i++;
                } else if ($codeChar != '[') {
                    continue;
                } else if ($i != 0) {
                    $i--;
                } else {
                    return;
                }
            }
        }
    }

    /**
     * Remove comments from user codes
     * 
     * Structure of the method:
     * 
     * <code>
     * 
     * Interpreter::removeComments() : void
     * 
     * </code>
     * 
     * @return void
     * @access private
     * @since  Method available since Release 1.0
     */
    private function removeComments(): void
    {
        $this->code = preg_replace('#\#.*#', '', $this->code);
    }
}
