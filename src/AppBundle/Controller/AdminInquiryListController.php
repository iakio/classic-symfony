<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/inquiry")
 */
class AdminInquiryListController extends Controller
{
    /**
     * @Route("/search")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createSearchForm();
        $form->handleRequest($request);
        $keyword = null;
        if ($form->isValid()) {
            $keyword = $form->get('search')->getData();
        }

        $em = $this->getDoctrine()->getManager();
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');
        $inquiryList = $inquiryRepository->findAllByKeyword($keyword);

        return $this->render('Admin/Inquiry/index.html.twig',
            [
                'form' => $form->createView(),
                'inquiryList' => $inquiryList,
            ]
        );
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createSearchForm()
    {
        return $this->createFormBuilder()
            ->add('search', SearchType::class)
            ->add('submit', SubmitType::class, [
                'label' => '検索',
            ])
            ->getForm();
    }
}
