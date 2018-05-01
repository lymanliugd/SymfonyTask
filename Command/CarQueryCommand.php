/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30
 * Time: 22:03
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CarQueryCommand extends ContainerAwareCommand
{
    private  $data = array(); //whole table data
    private $keyset;          //keep original keywords value

    protected function configure()
    {
        $this
            ->setName('cars:query')
            ->setDescription('Car query')
//            ->addArgument(
//                'feature',
//                InputArgument::REQUIRED,
//                'feature and value'
//            )
            ->addOption(
                '--mpg',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--cylinders',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--displacement',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--horsepower',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--weight',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--acceleration',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--model',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
            ->addOption(
                '--origin',
                null,
                InputOption::VALUE_OPTIONAL,
                'feature options'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //start load data--------------------------------
        $file = fopen('https://perso.telecom-paristech.fr/eagan/class/igr204/data/cars.csv','r');
        if (!$file) {
            $output->writeln("Unable to open remote file.\n");
            return;
        }
        try{
            $this->loadData($file);
            fclose($file);
        }catch (\Exception $exception){
            $output->writeln('loading data failed!');
            fclose($file);
            return;
        }

        if(empty($this->data)){
            $output->writeln("data error! \n");
            return;
        }

        //start parsing data-----------------------------------------------------
        $result='';
        $nameKey = 'car';
        $cars = array(); // search cars result
        $count = 0;       //number of Argv

        //output options result
        foreach ($input->getOptions() as $k=>$v){
            if($v && $v!='dev') {
                $count++;
                $key = strtolower($k);
                $value = explode(',',$v);               //handle multi values
                $result.="\n".$this->keyset->getKey($key).":\n";

                foreach ($value as $vv) {
                    $num=0;
                    foreach ($this->data as $d) {
                        if (is_numeric($vv)) {
                            $sub = floatval($d->getFeature($key)) - floatval($vv);
                            if ($sub > -0.00001 && $sub < 0.00001) {
                                $num++;
                                $carname = $d->getFeature($nameKey);
                                if(!isset($cars[$carname])){
                                    $cars[$carname] = $key.',1';
                                }else{
                                    $cars[$carname] = $this->increase($cars[$carname],$key);
                                }
                            }
                        } else if (strcmp($d->getFeature($key), $vv) == 0) {
                            $num++;
                            $carname = $d->getFeature($nameKey);
                            if(!isset($cars[$carname])){
                                $cars[$carname] = $key.',1';
                            }else{
                                $cars[$carname] = $this->increase($cars[$carname],$key);
                            }
                        }
                    }
                    $result.="  ".$vv." - ".$num."\n";
                }
            }
        }

        //output cars' name with or without options
        if(empty($result)){
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

        $output->writeln($result);
    }

    private function loadData($file)
    {
        $this->keyset = new KeySet();

        while (!feof ($file)) {
            $line = fgets ($file, 1024);
            $line = str_replace(array("\n","\r"),"",$line); //remove \n or \r from line
            //get keys
            if(empty($title)){
                $title = explode(';',$line);
                foreach ($title as $v){
                    $this->keyset->setKey(strtolower($v),$v); //all the keys use lower letter & keep value original
                }
                continue;
            }

            $tempData = explode(';',$line);
            //ignore the bad data
            if(count($title)!=count($tempData)){
                continue;
            }

            $car = new Car();
            foreach ($tempData as $k=>$v){
                $car->setFeature(strtolower($title[$k]),$v);
            }
            $this->data[] = $car;
        }
    }

    private function increase($value, $key)
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
}
