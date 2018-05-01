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

    
