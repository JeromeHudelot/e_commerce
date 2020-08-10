<?php

	namespace App\Command;
	
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputArgument;
	use App\Entity\User;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use Doctrine\ORM\EntityManagerInterface;
	
	class CreateUser extends Command
	{
		protected static $defaultName = 'app:create-user';
		
		private $requirePassword;
		private $requireUsername;
		private $passwordEncoder;
		private $em;
		
		public function __construct($requirePassword = true, $requireUsername = true, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em){
			
			$this->requirePassword = $requirePassword;
			$this->requireUsername = $requireUsername;
			$this->passwordEncoder = $passwordEncoder;
			$this->em = $em;
			
			parent::__construct();
			
		}

		protected function configure()
		{
			
			$this
				->setDescription('Creates a new user.')
				->setHelp('This command allows you to create a user...')
				->addArgument('username', $this->requireUsername ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'Username')
				->addArgument('password', $this->requirePassword ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'User password');
		}

		protected function execute(InputInterface $input, OutputInterface $output)
		{
			$user = new User();
			
			$user->setUsername($input->getArgument('username'));
			$user->setPassword($this->passwordEncoder->encodePassword($user,$input->getArgument('password')));

			$this->em->persist($user);
			$this->em->flush();
			
			return true;
		}
	}