<?php


namespace App\Service;


use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserService extends Command
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
            ->setName('app:create-user')
            ->setDescription('Creates new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $helper = $this->getHelper('question');

        $question = new Question('Enter user name: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The user name field cannot be empty');
            }

            return $value;
        });

        $data['userName'] = $helper->ask($input, $output, $question);


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

        $user = new Users();

        $user->setUserName($data['userName']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User created!');
    }


}
