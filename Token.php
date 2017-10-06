<?php 
class Token{
	const LPAREN=0;
    const RPAREN=1;
    const LBRACKET=2;
    const RBRACKET = 3;
    const LESS = 4;
    const EQUAL = 5;
    const COLON = 6;
    const ID = 7;
    const VALUE = 8;
    const IF_TAG = 9;
    const ELSE_TAG = 10;
    const EOF = 11 ;
    const INVALID = 12;
    var $str;
    var $type;
    var $val;

    function __construct(){
        $argv = func_get_args();
        switch(count($argv)){
        case 1:
            self:: __construct1($argv[0]);
            break;
        case 2:
            self:: __construct2($argv[0], $argv[1]);
            break;
        case 3:
            self::__construct3($argv[0], $argv[1], $argv[2]);
        }
    }
	function __construct1($theType){
        $this->type = $theType;
		 }
    function __construct2($theType, $theString){
        $this->type = $theType;
        $this->str = $theString;
    }
    function __construct3($theType, $theString, $theVal){
        $this->type = $theType;
        $this->str = $theString;
        $this->val = $theVal;
    }
}







?>