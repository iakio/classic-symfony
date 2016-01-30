<?php
/**
 * Created by PhpStorm.
 * User: ishida
 * Date: 2016/01/31
 * Time: 0:00
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cs:test')
            ->setDescription('test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $repository = $em->getRepository('AppBundle:Concert');
        $concertList = $repository->findAll();

        $serializer = $container->get('jms_serializer');
        $json = $serializer->serialize($concertList, 'json');

        dump($json);
    }


}
