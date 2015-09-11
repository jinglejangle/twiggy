<?php
namespace shefphp\twiggyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpFoundation\Request;

class NewEventCommand extends ContainerAwareCommand { 

    protected function configure()
    {
        $this
            ->setName('shefphp:event')
            ->setDescription('Greet someone')
            ->addArgument(
                    'config',
                    InputArgument::REQUIRED,
                    'Path to json config file'
                    )
            ->addOption(
                    'yell',
                    null,
                    InputOption::VALUE_NONE,
                    'If set, the task will yell in uppercase letters'
                    )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument('config');

        if(!file_exists($configFile)){ 
            throw new \Exception("config file is not found."); 
        }

        $this->readConfigFile($configFile);  

        $request = new Request();
        $this->getContainer()->enterScope('request');
        $this->getContainer()->set('request', $request, 'request');


        //month day speaker topic  twitter 

      
        $content = ''; 
        foreach($this->config as $event){  
            $tmp=[]; 
            foreach($event as $k=>$v){ 
                $tmp[$k] = $v; 
            }
            $content .= $this->getContainer()->get('twig')->render('shefphptwiggyBundle:Default:Event.html.twig', $tmp);
        }


        if(!isset($this->config['outputFile'])){ 
            $output->writeln( $content ) ; 
        }else{
              if(!file_exists($this->config['outputFile'])){ 
                    file_put_contents($this->config['outputFile'], $content); 
              }
        }


    }

    private function readConfigFile($configFile){ 

        $content = file_get_contents($configFile); 
        $this->config = @json_decode($content);
        if(!is_object($this->config) && !is_array($this->config)){ 
            throw new \Exception("lolz, bad json man. "); 
        }
        return $this->config; 
    }


    private function createJson(){ 

        $config = array( array("speaker"=>"Claire", "twitter"=>"http://twitter.com/claire", "month"=>"Sept", "day"=>"11th", "topic"=>"Testing stuff" )); 
        return( json_encode($config));

    }

}

