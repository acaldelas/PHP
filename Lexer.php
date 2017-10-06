<?php 
include("Token.php");
class Lexer{
    static $letters = "abcdefghijklmnopqrstuvxyz";
    static $digits ="0123456789";

    private $prog,$i;

    function __construct($s){
        $this->prog = str_split($s);
       
        $this->i = 0;
    }
    function next(){
        
        while($this->i < count($this->prog) && ctype_space($this->prog[$this->i])){
            $this->i++;
            }
        
        
        if($this->i >= count($this->prog)){
            return new Token(Token::EOF);
        }
        switch($this->prog[$this->i]){
        case '(':
            $this->i++;
            return new Token(Token::LPAREN,'(');
        case ')':
            $this->i++;
            return new Token(Token::RPAREN,"");
        case '{':
            $this->i++;
            return new Token(Token::LBRACKET,'{');
        case '}':
            $this->i++;
            return new Token(Token::RBRACKET,'}');
        case '<':
            $this->i++;
            return new Token(Token::LESS,'<');
        case '=':
            $this->i++;
            return new Token(Token::EQUAL, '=');
        case ':':
            $this->i++;
            return new Token(Token::COLON, ':');
        }
        if(array_search($this->prog[$this->i], str_split($this::$digits),true)!==false){
            $digit = $this->prog[$this->i];
            $this->i++;
            
            return new Token(Token::VALUE,"". $this::$digits,intval($digit));
        }
       
        if(array_search($this->prog[$this->i], str_split($this::$letters),true)!==false){
            $id = "";
            while($this->i <count($this->prog)&& array_search($this->prog[$this->i],str_split($this::$letters),true)!==false){
               $id =  $id.$this->prog[$this->i];
                $this->i++;
            }
            if(strcmp("if", $id) ==0){
                return new Token(Token::IF_TAG,$id);
                
            }
            if(strcmp("else",$id) == 0){
                return new Token(Token::ELSE_TAG,$id);
                

            }
            if(strlen($id)==1){
                return new Token(Token::ID,$id);
            }
            
        }
            
                
    }
}
?>
