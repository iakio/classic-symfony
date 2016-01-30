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
use Symfony\Component\Console\Question\Question;

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

        $inquiryList = $inquiryRepository->findUnprocessed();

        if (count($inquiryList) > 0) {
            $output->writeln(count($inquiryList).'件の未処理お問い合わせがあります');

            if ($this->confirmSend($input, $output)) {
                $this->sendMail($inquiryList, $output);
            }
        } else {
            $output->writeln('未処理無し');
        }
    }

    private function confirmSend($input, $output)
    {
        $qHelper = $this->getHelper('question');

        $question = new Question('通知メールを送信しますか？[y/n]', null);
        $question->setValidator(function ($answer) {
            $answer = strtolower(substr($answer, 0, 1));
            if (!in_array($answer, ['y', 'n'])) {
                throw new \RuntimeException(
                    'yまたはnを入力してください'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(3);

        return $qHelper->ask($input, $output, $question) == 'y';
    }

    /**
     * @param $inquiryList
     * @param OutputInterface $output
     * @param $container
     */
    protected function sendMail($inquiryList, OutputInterface $output)
    {
        $container = $this->getContainer();
        $templating = $container->get('templating');
        $message = \Swift_Message::newInstance()
            ->setSubject('[CS] 未処理お問合せ通知')
            ->setFrom('webmaster@example.com')
            ->setTo('admin@example.com')
            ->setBody(
                $templating->render(
                    'mail/admin_unprocessedInquiryList.txt.twig',
                    ['inquiryList' => $inquiryList]
                )
            );
        $container->get('mailer')->send($message);
        $output->writeln(count($inquiryList) . '件の未処理を通知');
    }
}
