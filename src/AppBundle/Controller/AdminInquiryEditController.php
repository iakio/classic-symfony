<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class AdminInquiryEditController
 * @package AppBundle\Controller
 * @Route("/admin/inquiry")
 */
class AdminInquiryEditController extends Controller
{
    private function createInquiryForm($inquiry)
    {
        return $this->createFormBuilder($inquiry,
            ["validation_groups" => ["admin"]])
            ->add('processStatus', ChoiceType::class, [
                'choices' => [
                    '未対応' => 0,
                    '対応中' => 1,
                    '対応済み' => 2,
                ],
                'empty_data' => 0,
                'expanded' => true,
            ])
            ->add('processMemo', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'label' => '保存',
            ])
            ->getForm();
    }

    /**
     * @Route("/{id}/edit")
     * @ParamConverter("inquiry", class="AppBundle:Inquiry")
     * @Method("get")
     */
    public function inputAction(Inquiry $inquiry)
    {
        $form = $this->createInquiryForm($inquiry);

        return $this->render('Admin/Inquiry/edit.html.twig', [
            'form' => $form->createView(),
            'inquiry' => $inquiry
        ]);
    }

    /**
     * @Route("/{id}/edit")
     * @ParamConverter("inquiry", class="AppBundle:Inquiry")
     * @Method("post")
     */
    public function inputPostAction(Request $request, Inquiry $inquiry)
    {
        $form = $this->createInquiryForm($inquiry);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl(
                'app_admininquirylist_index'
            ));
        }

        return $this->render('Admin/Inquiry/edit.html.twig', [
            'form' => $form->createView(),
            'inquiry' => $inquiry
        ]);
    }

}
