<?php
/**
 * Created by PhpStorm.
 * User: ishida
 * Date: 2016/01/30
 * Time: 0:10
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyUnprocessedInquiryCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('cs:inquiry:notify-unprocessed')
            ->setDescription('未処理お問合せ一覧を通知');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine')->getManager();

        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        $inquryList = $inquiryRepository->findUnprocessed();

        if (count($inquryList) > 0) {
            $templating = $container->get('templating');
            $message = \Swift_Message::newInstance()
                ->setSubject('[CS] 未処理お問合せ通知')
                ->setFrom('webmaster@example.com')
                ->setTo('admin@example.com')
                ->setBody(
                    $templating->render(
                        'mail/admin_unprocessedInquiryList.txt.twig',
                        ['inquiryList' => $inquryList]
                    )
                );
            $container->get('mailer')->send($message);
            $output->writeln(count($inquryList).'件の未処理を通知');
        } else {
            $output->writeln('未処理無し');
        }
    }
}
