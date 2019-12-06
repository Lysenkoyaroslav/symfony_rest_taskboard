<?php


namespace App\Service;


use App\Entity\Dashboard;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DashboardService extends Command
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager, string $name = null)
    {
        $this->entityManager = $entityManager;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:create-dashboard')
            ->setDescription('Creates new dashboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $helper = $this->getHelper('question');

        $question = new Question('Enter dashboard name: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This field cannot be empty');
            }

            return $value;
        });

        $data['name'] = $helper->ask($input, $output, $question);



        $dashboard = new Dashboard();

        $dashboard->setName($data['name']);

        $this->entityManager->persist($dashboard);
        $this->entityManager->flush();

        $output->writeln('Dashboard created!');
    }


}
