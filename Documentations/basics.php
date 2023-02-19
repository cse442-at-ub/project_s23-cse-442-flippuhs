<?php
//every php script starts with <?php and ends with ? >

//To define and reference variables use $name
$myInteger = 31;
$myString = "My name is Chris";
$myFloat = 2.5;
$myBoolean = true;



//----------------------------------------------STRINGS------------------------------------------------//
echo "STRING PRACTICE\n";
//should print out My name is Chris
//String Concatenation uses . instead of + and newline character has to be wrapped in double quotes
echo $myString . "\n";
//OR you can just enclose everything in double quotes (can use variables in quotes)
echo "Hi, $myString\n";
//length of string
echo "length of myString is: " . strlen($myString) . "\n";
echo "\n";



//----------------------------------------------ARRAYS--------------------------------------------------//
echo "ARRAYS PRACTICE\n";
$myArray = array(1,2,3,4,5);
echo "Third element of myArray is: $myArray[2]\n";
echo "\n";



//----------------------------------------------MAPS--------------------------------------------------//
echo "MAPS PRACTICE\n";
$map = array();
$map["name"]="chris";
$map["age"]="21";

echo "name from map: ".$map["name"].", age from map: ".$map["age"]."\n";
echo "\n";



//----------------------------------------IF ELSE STATEMENTS---------------------------------------------//
echo "IF ELSE PRACTICE\n";
//comparisons use &&, ||, ==, ===, <, >, <=, >=
if($myInteger%2 == 0){
    echo "myInteger: $myInteger is even\n";
}
else{
    echo "myInteger: $myInteger is odd\n";
}
//NOTE: this returns true even when comparing int with string
if($myInteger == "31"){
    echo "true\n";
}
//Instead use ===
if($myInteger === "31"){
    echo "this should not print";
}
echo "\n";



//----------------------------------------------FOR LOOPS-------------------------------------------------//
echo "FOR LOOP PRACTICE\n";
//Simple for loop
$myArray2 = array(4,6,2,8,10);
$sumOfArray = 0;
for($i=0;$i<count($myArray2);$i++){
    $sumOfArray += $myArray2[$i];
}
//Should print 30
echo $sumOfArray . "\n";
echo "\n";



//----------------------------------------------FUNCTIONS-------------------------------------------------//
echo "FUNCTION PRACTICE\n";
function myFunction($array){
    $sumOfArray = 0;
    for($i=0;$i<count($array);$i++){
        $sumOfArray += $array[$i];
    }
    return $sumOfArray;
}

//should print 100
$myArray3 = array(25,25,50);
$returnValue = myFunction($myArray3);
echo $returnValue . "\n";
echo "\n";



//------------------------------------------------CLASS---------------------------------------------------//
echo "CLASS PRACTICE\n";
class User{
    public $name;
    private string $location = "";

    //Constructor
    function __construct($name){
        $this->name = $name;
    }

    public function getLocation() : string{
        return "$this->name's location is: $this->location";
    }

    public function setLocation(string $loc){
        $this->location = $loc;
    }
}

$userChris = new User("Chris");
$userChris->setLocation("Queens, NY");
echo $userChris->getLocation();
echo "\n";

?>