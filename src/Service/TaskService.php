<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class TaskService extends Command
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
            ->setName('app:create-task')
            ->setDescription('Creates new task');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $helper = $this->getHelper('question');

        $question = new Question('Enter task name: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This field cannot be empty');
            }

            return $value;
        });

        $data['taskName'] = $helper->ask($input, $output, $question);


        $helper = $this->getHelper('question');

        $question = new Question('Enter password: ');
        $question->setHidden(true);
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The password cannot be empty');
            }

            return $value;
        });

        $data['password'] = $helper->ask($input, $output, $question);


        $helper = $this->getHelper('question');

        $question = new Question('Enter email: ');
        $question->setNormalizer(function ($value) {

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('The email is not valid');
            }

            return $value;
        });

        $data['email'] = $helper->ask($input, $output, $question);

        $task = new tasks();

        $task->settaskName($data['taskName']);
        $task->setPassword($data['password']);
        $task->setEmail($data['email']);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $output->writeln('task created!');
    }


}