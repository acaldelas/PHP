<?php
include_once"Lexer.php";
include_once"Token.php";

static $letters = "abcdefghijklmnopqrstuvxyzz";
static $digit = "0123456789";
static $values = [];
static $currentToken;
static $oneIndent = "   ";
static $lex;
    


$header = "<html>\n" . "   <head>\n" . "   <title>Program Evaluatio </title>\n" . "   <head>\n" . "<body>\n" . "<pre>\n";
echo $header;

$programsURL = fopen("http://cs5339.cs.utep.edu/longpre/assignment2/programs.txt","r") or die("Unable to open this file!");
while(!feof($programsURL)){
    $programsInputLine = fgets($programsURL);
    $programsInputLine = trim($programsInputLine);
    echo $programsInputLine . "\n";
    $inputURL = fopen($programsInputLine,"r") or die("It died at fopen");
    $program = " ";
    while(!feof($inputURL)){
        $program = $program . "\n" . fgets($inputURL)or die("TF YOU DIED");
    }
           
    $lex = new Lexer($program);
            
    $currentToken =$lex->next();
    try {
        execProg($oneIndent);
        
        if($currentToken->type!=Token::EOF ){
            echo "Unexpected faulure\n";
            throw new Exception();
        }
    }
    catch(Exception $ex){
        echo "Program parsing aborted\n";
    }
    echo "\n";
               
}
    
echo  "<pre>\n </body>\n" . "</html>";
       
function execProg($indent){
    global $currentToken;
    
    while($currentToken->type ==Token::ID || $currentToken->type== Token::IF_TAG){
        
        execStatement($indent, true);
    }
    echo "\n";
    execResults($indent);
    
}


function execStatement($indent, $executing){
    global $currentToken;
    
    if($currentToken->type ==Token:: ID){
        
        execAssign($indent, $executing);

    }else{
        execConditional($indent, $executing);
    }
}

function execAssign($indent, $executing){
    global $currentToken;
    global $lex;
    global $values;
    

    $c = $currentToken->str;
    
    $currentToken = $lex->next();
    
    if($currentToken->type !=Token::EQUAL){
        echo "\n equal sign expected ";
        throw new Exception();
            
    }
    
    $currentToken = $lex->next();
    
    echo $indent. $c . "=";
    
    $value = execExpr($indent);
    echo "\n";
   
    if($executing){
        $values[$c] = $value;
        
    }
}
function execConditional($indent, $executing){
    global $currentToken;
    global $lex;
    echo $indent . "if";
    global $oneIndent;
    $currentToken = $lex->next();
    $condResult = execCond($indent);
    
    echo "  {\n";
    if($currentToken->type !== Token::LBRACKET){
        echo "\n Left Bracket expected";
        throw new Exception();
    }
    $currentToken = $lex->next();
    while($currentToken->type==Token::ID || $currentToken->type ==Token:: IF_TAG){
        execStatement($indent= $indent . $oneIndent,$condResult);
    }
    if($currentToken->type !=Token::RBRACKET){
        echo "\n Right Bracket or statement expected";
        throw new Exception();
    }
        
    echo $indent . "}";
    $currentToken = $lex->next();
    if($currentToken->type ==Token:: ELSE_TAG){
        $currentToken = $lex->next();
        if($currentToken->type !=Token::LBRACKET){
            echo "\n left bracket expeted";
            throw new Exception();
        }
        $currentToken = $lex->next();
        echo "  else{\n";
        while($currentToken->type ==Token:: ID || $currentToken->type ==Token:: IF_TAG){
            execStatement($indent= $indent . $oneIndent, !$condResult);
        }
        if($currentToken->type !=Token:: RBRACKET){
            echo "\nRight Braket or statement";
            throw new Exception();
        }
        echo $indent . "}";
        $currentToken = $lex->next();

    }
    echo "\n";
}
function execCond($indent){
    global $currentToken;
    global $lex;
    global $values;
    if($currentToken->type !=Token:: LPAREN){


        echo "\n left parenthesis expected";
        throw new Exception();
    }
    echo "(";
    $currentToken = $lex->next();
    $v1 = execExpr($indent);
    
    if($currentToken->type !=Token:: LESS){
        echo "\nless than expected";
        throw new Exception();

    }
    echo "&lt;";
    $currentToken = $lex->next();
    $v2 = execExpr($indent);
    if($currentToken->type !=Token:: RPAREN){
        echo "RIght prenthis expected";
        throw new Exception();
            
    }
    echo ")";
    
    $currentToken = $lex->next();
    
    
    return $v1<$v2;
}
function execExpr($indent){
    global $lex;
    global $currentToken;
    global $values;
    
    
    if($currentToken->type ==Token::VALUE){
       
        $value =  $currentToken->val;
        echo $value;
        
        $currentToken = $lex->next();
        
       
        return $value;
    }
    if($currentToken->type ==Token:: ID){
        $c = $currentToken->str;
        echo $c;
        if(array_key_exists($c,$values)){
            
            $currentToken = $lex->next();
            return (int) $values[$c];
        }else{
            echo "Reference to an undefined variable:";
            throw new Exception();
        }
        echo " an Expression shoul be a digit or a letter";
        throw new Exception();
                
    }
}
    function execResults($indent){
        global $currentToken;
        global $lex;
        global $values;
        if($currentToken->type !=Token::COLON){
            echo "\n colon or statement expected";
            throw new Exception();
        }
        $currentToken = $lex->next();
        
        while($currentToken->type==Token::ID){
            $c = $currentToken->str;
            $currentToken = $lex->next();
            if(array_key_exists($c, $values)){
                echo "the val of " . $c . " is " . $values[$c]."\n";
            }else{
                echo "the value of " . $c . " is undefined \n";
                
            }
                        
        }

    }

