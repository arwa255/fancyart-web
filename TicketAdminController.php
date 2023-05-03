<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Evenement;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


#[Route('/admin/ticket')]
class TicketAdminController extends AbstractController
{
    #[Route('/', name: 'admin_ticket_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager
            ->getRepository(Ticket::class)
            ->findAll();
        $events = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        $pieChart = new PieChart();

        $charts = [['Tickets', 'Nombre par Evenements']];

        foreach ($events as $evt) {
            $evtN = 0;
            foreach ($tickets as $t) {
                if ($evt == $t->getEvent()) {
                    $evtN++;
                }
            }

            array_push($charts, [$evt->getNomevent(), $evtN]);
        }

        $pieChart->getData()->setArrayToDataTable($charts);

        $pieChart->getOptions()->setTitle('Taux de tickets par Evenements');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);

        return $this->render('ticketAdmin/index.html.twig', [
            'tickets' => $tickets,
            'piechart' => $pieChart,
        ]);
    }

    #[Route('/new', name: 'admin_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticketAdmin/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{idticket}', name: 'admin_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticketAdmin/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{idticket}/edit', name: 'admin_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticketAdmin/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{idticket}', name: 'admin_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getIdticket(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
}
