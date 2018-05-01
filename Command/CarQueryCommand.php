<?php
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
    private  $data = array();
    private $keyset;

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
        //output options result
        foreach ($input->getOptions() as $k=>$v){
            if($v && $v!='dev') {
                $key = strtolower($k);
                $value = explode(',',$v);               //handle multi values
                $result.="\n".$this->keyset->getKey($key).":\n";

                foreach ($value as $v) {
                    $num=0;
                    foreach ($this->data as $d) {
                        if (is_numeric($v)) {
                            $sub = floatval($d->getFeature($key)) - floatval($v);
                            if ($sub > -0.00001 && $sub < 0.00001) {
                                $num++;
                            }
                        } else {
                            if (strcmp($d->getFeature($key), $v) == 0) {
                                $num++;
                            }
                        }
                    }
                    $result.="  ".$v." - ".$num."\n";
                }
            }
        }

        //without options output cars' name
        if(empty($result)){
            $result = "Search result:\n";
            $key = 'car';
            $res = array();

            foreach ($this->data as $v){
                $res[]= $v->getFeature($key);
            }
            $res = array_flip($res);
            $res = array_keys($res);
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
}