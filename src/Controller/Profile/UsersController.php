<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContributionType;
use App\Entity\Contributions;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/profile', name: 'profile_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/contribute', name: 'contrib')]
    public function contribute(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contribution = new Contributions();
                
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $currentUser = $this->getUser();
        $contribution->setUsers($currentUser);
        $date = new \DateTime('@'.strtotime('now'));
        $contribution->setCreatedAt($date);
        $status = '0';
        $contribution->setStatus($status);
        $number = rand(1,90000);
        $tid = "TID".(string)$number;
        $contribution->setTransactionID($tid);

        $entityManager->persist($contribution);
        $entityManager->flush();
        $this->addFlash('message','Contribution ajouté avec succès');
        return $this->redirectToRoute('profile_index');
        }
        return $this->render('profile/users/contribute.html.twig', [
            'contributeForm' => $form->createView(),
        ]);
    
}
   
}
