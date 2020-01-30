<?php

namespace App\Controller;

use App\Entity\Act;
use App\Entity\Event;
use App\Entity\EventSearch;
use App\Form\EventSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showEvents(EntityManagerInterface $em, PaginatorInterface $paginator,Request $request)
    {
        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);
        $events = $paginator->paginate(
            $em->getRepository(Event::class)->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            3

        );
        return $this->render('wild/showEvents.html.twig', [
            'events' => $events,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @Route("/acts", name="acts")
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showActs(EntityManagerInterface $em)
    {
        $acts = $em->getRepository(Act::class)->findAll();

        return $this->render('wild/showActs.html.twig', [
            'acts' => $acts,
        ]);
    }
}
