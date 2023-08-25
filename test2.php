<?php
// fixme: this is a hack, but it works
//fixme 
//! 

CLASS DUMMY
{
    /**
     * Split the given JSON selector into the field and the optional path and wrap them separately.
     *
     * @param  string  $column
     * @return array
     */
    function wrapJsonFieldAndPath($column)
    {
        $parts = explode('->', $column, 2);

        $field = $this->wrap($parts[0]);

        $path = count($parts) > 1 ? ', '.$this->wrapJsonPath($parts[1], '->') : '';

        return [$field, $path];
    }

    /**
     * Here we will do something special with the path & delimiters. can be a long onliner . version 20230824
     * 
     * @param  string  $path
     * @param  string  $delimiter
     * @return string
     */
    function wrapJsonPath($paths, $delimiter)
    {
        // If the path is a JSON selector, we will wrap it differently than a
        // regular path. We will need to split this path into segments and
        // then wrap each segment individually, etc.
        $segments = explode($delimiter, $paths);
        $wrappedSegments = array_map(function ($segment) {
            return "'".$segment."'";
        },$paths);
        return $wrappedSegments;
    }

    /**
     * This function will something special with the value. Version 20230824
     * @param  string  $value
     * @return string
     */
    function wrap($value)
    {
        // If the value looks like a JSON selector, we will wrap it differently
        return $value;
    }

}

for ($i=0;$i>=10;$i++){
    
}

$i=0;
while ($i<10){
    $i++;
    echo "<br> While loop at $i";
}

do {
    # code...
} while ($a <= 10);

do {
    # code...
} while ($a <= 10);

foreach ($array as $key => $value) {

}

for($i=0;$i<12;$i++){
    
}

$path="";
$paths=array();
$delimiter=",";
$dum =new DUMMY();

$dum->wrapJsonFieldAndPath('test');

$dum->wrapJsonPath($paths, $delimiter);

$dum->wrap('test');
if($ftf){
    echo "yes";
}

if($t=="i"){

}

$rt =new sample();
$rt->test2();

class sample {
    public $dir="";
    public static $a=0;
    public function __construct(){
        self::$a=1;
    }

    public static function test(){
        self::$a=1;
        print_r(self::$a);
       self::$dir =dirname(__DIR__);
    }

    public function test2(){
        echo "hello";
        $this->test();
    }
}
function console_log($data){
    echo '<script> console.log('.json_encode($data).')</script>';
}

?>
