<?php


namespace App\Command;

use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class ReadFile extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:my_project';

    protected function configure()
    {
        $this
        ->addArgument('filename', InputArgument::REQUIRED, 'Type full file name with emails')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $filesystem = new Filesystem();
        try {
            $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
        $fp = fopen("Incorrect.csv", "w");
        $fp_1 = fopen("Correct.csv", "w");
        $fp_2 = fopen("Summary.csv", "w");

        $emails = file_get_contents($filename, '');
        $verse = explode("\n", $emails);
        $max = sizeof($verse);

        $correct = 0;
        $incorrect = 0;
        for ($i=0; $i<$max; $i++) {
            $email = $verse[$i];
               if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   fwrite($fp_1, $email);
                   $correct++;
               }
               else
               {
                   fwrite($fp, $email);
                   $incorrect++;
               }
        }
            fwrite($fp_2, "$max e-mail adresses were checked. $correct were correct and $incorrect were incorrect");
            return 0;
        }
    }