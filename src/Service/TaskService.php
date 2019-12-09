<?php


namespace App\Service;


use App\Entity\Columns;
use App\Entity\Tasks;
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

        $data['name'] = $helper->ask($input, $output, $question);


        $helper = $this->getHelper('question');

        $question = new Question('Add some description: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This field cannot be empty');
            }

            return $value;
        });

        $data['description'] = $helper->ask($input, $output, $question);

        $helper = $this->getHelper('question');

        $question = new Question('Add to column[column name]: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This field cannot be empty');
            }

            return $value;
        });

        $column = $helper->ask($input, $output, $question);

        $columnsRepository = $this->entityManager->getRepository(Columns::class);
        $column = $columnsRepository->findOneBy(['name' => $column]);


        if (empty($column)) {

            $output->writeln('<error>Column not found!</error>');

            return false;
        }


        $task = new Tasks();

        $task->setName($data['name']);
        $task->setDescription($data['description']);
        $task->setColumns($column);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $output->writeln('task created!');

        return true;
    }


}