<?php
namespace AppBundle\Command;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: kuni
 * Date: 2017/03/20
 * Time: 15:03
 *
 * php bin/console app:user:add 'First Last' '名無権平' example@example.com test1234 ROLE_SUPER_ADMIN 09000000000
 *
 */
class AddUserCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('app:user:add')
            ->setDescription('Add User')
            ->addArgument('name_en', InputArgument::REQUIRED, 'nameEn')
            ->addArgument('name_jp', InputArgument::REQUIRED, 'nameJp')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('role', InputArgument::REQUIRED, 'role[ ROLE_SUPER_ADMIN or ROLE_ADMIN or ROLE_USER ]')
            ->addArgument('tel', InputArgument::REQUIRED, 'tel')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name_en = $input->getArgument('name_en');
        $name_jp = $input->getArgument('name_jp');
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $role = $input->getArgument('role');
        $tel = $input->getArgument('tel');

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getEntityManager();

        $user = new User();
        $encoder = $container->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($user, $plainPassword);

        $foundUser = $em->getRepository('AppBundle:User')->findAll();
        if( $foundUser ){
            $output->writeln('Already registered the email.' . $email);
            exit;
        }

        $user->setNameEn($name_en);
        $user->setNameJp($name_jp);
        $user->setEmail($email);
        $user->setPassword($encodedPassword);
        $user->addRole($role);
        $user->setTel($tel);

        $em->persist($user);
        $em->flush();

        $output->writeln('Created user ' . $name_en);
    }
}