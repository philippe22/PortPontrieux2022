<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Louer;
use App\Form\LouerType;

class LouerController extends AbstractController
{
    /**
     * @Route("/listeLocation/{cote}", name="listeLocation")
     */
    public function listeLocation($cote)
    {
        $manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(Louer::class);
		$lesLocations = $rep->listeLocation($cote);
        return $this->render('louer/index.html.twig', Array('lesLocations' => $lesLocations, 'cote' => $cote));
    }

    /**
     * @Route("/louer", name="insereLocation")
     */
    public function inserer(Request $request)
    {
		// Création du formulaire
		$louer = new Louer();
		$form   = $this->createForm(LouerType::class, $louer);
		
		// Insertion dans la BDD si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request); // remplissage du formulaire à partir de la requête Http
			if ($form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($louer);
				$em->flush();
				
				$request->getSession()->getFlashBag()->add('info', 'Emplacement bien enregistré.');
				
				return $this->redirectToRoute('accueil');
			}
		}
		
		return $this->render('louer/inserer.html.twig', array('form' => $form->createView()));
    }
}
