<?php

namespace App\Controller;

use App\Entity\Act;
use App\Entity\Event;
use App\Entity\EventSearch;
use App\Entity\Price;
use App\Form\ContactType;
use App\Form\EventSearchType;
use App\Form\TicketingType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/ticketing", name="ticketing")
     */
    public function ticketing()
    {
        $form = $this->createForm(TicketingType::class);

        return $this->render('wild/ticketing.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ticketPrice")
     * @param Request $request
     * @return JsonResponse
     */
    public function priceCalculation(
        Request $request,
        EntityManagerInterface $em
    ) {
        $adultTicket = $em->getRepository(Price::class)->findOneBy(['name' => 'Adulte'])->getAmount();
        $childTicket = $em->getRepository(Price::class)->findOneBy(['name' => 'Enfants'])->getAmount();
        $seniorTicket = $em->getRepository(Price::class)->findOneBy(['name' => 'Senior'])->getAmount();

        $ticketing = json_decode(
            $request->getContent(),
            true
        );
        $totalAdultPrice = $ticketing['nbAdult'] * $adultTicket;
        $totalChildPrice = $ticketing['nbChild'] * $childTicket;
        $totalSeniorPrice = $ticketing['nbSenior'] * $seniorTicket;
        $totalPrice = $totalAdultPrice + $totalChildPrice + $totalSeniorPrice;

        return new JsonResponse([
            'total_adult_price' => number_format($totalAdultPrice, 2, ',', ''),
            'total_child_price' => number_format($totalChildPrice, 2, ',', ''),
            'total_senior_price' => number_format($totalSeniorPrice, 2, ',', ''),
            'total_price' => number_format($totalPrice, 2, ',', ''),
        ], 200);
    }
}
