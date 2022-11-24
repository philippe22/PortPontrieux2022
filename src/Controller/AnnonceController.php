<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Annonce;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function liste()
    {
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(Annonce::Class);
		$lesAnnonces = $rep->listeDesAnnoncesAffichables();
		
		return $this->render('annonce/liste.html.twig', Array('lesAnnonces' => $lesAnnonces));
    }

    /**
     * @Route("/detailsAnnonce/{id}", name="detailsAnnonce")
     */
    public function detailAnnonce($id)
    {
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(Annonce::Class);
		$uneAnnonce = $rep->detailsAnnonce($id);
		
		return $this->render('annonce/detailAnnonce.html.twig', Array('uneAnnonce' => $uneAnnonce));
    }
}
