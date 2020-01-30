<?php

namespace App\Controller;

use App\Entity\Act;
use App\Entity\Event;
use App\Entity\EventSearch;
use App\Form\ContactType;
use App\Form\EventSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @return Response
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
     * @return Response
     */
    public function showActs(EntityManagerInterface $em)
    {
        $acts = $em->getRepository(Act::class)->findAll();

        return $this->render('wild/showActs.html.twig', [
            'acts' => $acts,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_to'))
                ->subject('Demande d\'informations')
                ->html($this->renderView('email/notification.html.twig', [
                    'clients_infos' => $form->getData(),
                ]));
            try {
                $mailer->send($email);
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue veuillez réessayer svp .');
            }
        }

        return $this->render('wild/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
