<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Nécessaire pour la pagination
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

use App\Entity\Emplacement;

class TarifsController extends AbstractController
{
    /**
     * @Route("/tarifs", name="tarifs")
     */
    public function liste(Request $request, PaginatorInterface $paginator) //: Response
    {
        $manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(Emplacement::class);
		$lesEmplacements = $rep->findAll();
        
        
		$lesEmplacementsPagines = $paginator->paginate(
            $lesEmplacements, // Requête contenant les données à paginer (ici nos emplacements)
            $request->query->getInt('page', 1), // No de la page en cours (dans l'URL), 1 si aucune page
            3 // Nombre de résultats par page
            );
        
		return $this->render('tarifs/index.html.twig', Array('lesEmplacements'=>$lesEmplacementsPagines));

    }
}
