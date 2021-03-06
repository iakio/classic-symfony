<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/inquiry")
 */
class InquiryController extends Controller
{
    /**
     * @Route("/")
     * @Method("get")
     */
    public function indexAction()
    {
        return $this->render('Inquiry/index.html.twig', [
            'form' => $this->createInquiryForm()->createView()
        ]);
    }

    /**
     * @Route("/")
     * @Method("post")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexPostAction(Request $request)
    {
        $form = $this->createInquiryForm();
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $inquiry = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($inquiry);
            $em->flush();
            $message = \Swift_Message::newInstance()
                ->setSubject("Webサイトからのお問合せ")
                ->setFrom("webmaster@example.com")
                ->setTo("admin@example.com")
                ->setBody(
                    $this->renderView(
                        'mail/inquiry.txt.twig',
                        ['data' => $inquiry]
                    )
                );
            $this->get('mailer')->send($message);

            return $this->redirect($this->generateUrl('app_inquiry_complete'));
        }
        return $this->render('Inquiry/index.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/complete")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completeAction()
    {
        return $this->render('Inquiry/complete.html.twig');
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createInquiryForm()
    {
        return $this->createFormBuilder(new Inquiry())
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('tel', TextType::class, [
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    '公演について' => 0,
                    'その他' => 1
                ],
                'expanded' => true
            ])
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'label' => '送信'
            ])
            ->getForm();
    }
}
