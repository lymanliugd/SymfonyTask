<?php
// class Car------------------------
class Car{
    private $features = array();

    public function getFeature($key)
    {
        return (isset($this->features[$key]) ? $this->features[$key] : null);
    }

    public function setFeature($key, $value)
    {
        $this->features[$key] = $value;
    }
}

//class KeySet for different format in keywords such as MPG, Origin
final class KeySet{
    private $keySet = array();

    public function getKey($key)
    {
        return (isset($this->keySet[$key]) ? $this->keySet[$key] : null);
    }

    public function setKey($key,$value)
    {

        $this->keySet[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->keySet[$key]);
    }
}

//function----------------------------
function getKeyFromArgv($str){
    $n = strpos($str,'=');
    $start = 2;
    return substr($str,$start,$n-$start);
}

function getValueFromArgv($str){
    $n = strpos($str,'=');
    $start = $n+1;
    $value = explode(',',substr($str,$start)); //handle multi values
    return $value;
}

function increase($value, $key)
{
    $v = explode(',',$value);
    if(strcmp($v[0],$key)!=0)
    {
        $res = $v[1]+1;
        $res = $key.','.$res;
        return $res;
    }
    else
    {
        return $value;
    }
}

//read command
if(count($argv)<2){
    echo 'Please input the argument "cars:query"'."\n";
    exit;
}

//start loading data---------------------------------------------------------------------------
$file = fopen('https://perso.telecom-paristech.fr/eagan/class/igr204/data/cars.csv','r');
if (!$file) {
    echo "Unable to open remote file.\n";
    exit;
}

try {
    $data = array();
    $keyset = new KeySet();
    $l = 0;

    while (!feof($file)) {
        $line = fgets($file, 1024);
        $line = str_replace(array("\n", "\r"), "", $line); //remove \n or \r from line
        //get keys
        if (empty($title)) {
            $title = explode(';', $line);
            foreach ($title as $v) {
                $keyset->setKey(strtolower($v), $v);//all the keys use lower letter & keep value original
            }
            $l++;
            continue;
        }

        $tempData = explode(';', $line);
        //ignore the bad data
        if (count($title) != count($tempData)) {
            echo 'line:' . $l . " data format error!";
            $l++;
            continue;
        }
        $l++;

        $car = new Car();
        foreach ($tempData as $k => $v) {
            $car->setFeature(strtolower($title[$k]), $v);
        }
        $data[] = $car;
    }
}catch (Exception $exception){
    echo 'loading data failed!';
    fclose($file);
    exit;
}

fclose($file);

if(empty($data)){
    echo "data error! \n";
    exit;
}
//atart parsing data-----------------------------------------------------------------
$result = '';
$nameKey = 'car';
$cars = array(); // search cars result
$count = 0;       //number of Argv

for($i=2;$i<count($argv);$i++){
    //for each $argv----------------------------
    $count++;
    $key = strtolower(getKeyFromArgv($argv[$i]));
    $value = getValueFromArgv($argv[$i]);
    if(!$keyset->has($key)){
        echo "The key: [".$key."] does not exist.\n";
        continue;
    }
    $result.="\n".$keyset->getKey($key).":\n";

    foreach ($value as $v) {
        $num=0;
        foreach ($data as $d) {
            if (is_numeric($v)) {
                $sub = floatval($d->getFeature($key)) - floatval($v);
                if ($sub > -0.00001 && $sub < 0.00001) {
                    $num++;
                    $carname = $d->getFeature($nameKey);
                    if(!isset($cars[$carname])){
                        $cars[$carname] = $key.',1';
                    }else{
                        $cars[$carname] = increase($cars[$carname],$key);
                    }
                }
            }
            else if (strcmp($d->getFeature($key), $v) == 0) {
                $num++;
                $carname = $d->getFeature($nameKey);
                if(!isset($cars[$carname])){
                    $cars[$carname] = $key.',1';
                }else{

                    var_dump($cars[$carname]);
                    var_dump($carname);
                    var_dump($key);
                    $cars[$carname] = increase($cars[$carname],$key);
                }
            }
        }
        $result.="  ".$v." - ".$num."\n";
    }
}

if(count($argv)<3){
    $result = "Search result:\n";
    $res = array();

    foreach ($this->data as $v){
        $res[]= $v->getFeature($nameKey);
    }
    $res = array_flip($res);
    $res = array_keys($res);
    sort($res);
    foreach ($res as $r){
        $result.="  ".$r."\n";
    }
}
else{
    $result .= "\nSearch result:\n";
    $res = array();

    foreach ($cars as $k=>$c){
        $n = explode(',',$c);
        if($n[1] == $count){
            $res[]=$k;
        }
    }
    if(empty($res)){
        $result.="  no matched\n";
    }
    sort($res);
    foreach ($res as $r){
        $result.="  ".$r."\n";
    }

}

var_dump($result);

?>
