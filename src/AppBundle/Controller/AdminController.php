<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{

    /**
     * @Route("/themes", name="admin_themes")
     * @return Response
     */
    public function themeAction(Request $request){
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Theme");

        $themeList = $repository->findAll();

        $theme = new Theme();
        $form=$this->createForm(ThemeType::class,$theme);



        //hydratation
        $form->handleRequest($request);

        //form processing
        if($form ->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

            //redirection pour éviter de poster deux fois les données
            return $this->redirectToRoute("admin_themes");
        }



        return $this->render("admin/theme.html.twig",
            [
                "themeList" => $themeList,
                "themeForm"=>$form->createView()
            ]);
    }

}