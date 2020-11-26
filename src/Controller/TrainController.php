<?php

namespace App\Controller;

use App\Entity\Station;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function landingAction(StationRepository $stationRepository): Response
    {
        if (count($stations = $this->getUser()->getStations()) == 1)
        {
            $id = $stations->first()->getId();
            $slug = $stations->first()->getSlug();
            return new RedirectResponse($this->generateUrl('train_on_station', ['id'=> $id, 'slug'=>$slug]), 301);
        }
        else
        {
            $stations = $stationRepository->findAll();
        }
        return $this->render('train/landing.html.twig', [
            'stations' => $stations
        ]);
    }

    public function stationAction($id = 0, $slug = null): Response
    {
        $station = $this->em->getRepository(Station::class)->find($id);
        if ($station)
        {
            if ($slug == $station->getSlug())
            {
                return $this->render('train/on-station.html.twig', [
                    'station' => $station
                ]);
            }
            else
            {
                $slug = $station->getSlug();
                return new RedirectResponse($this->generateUrl('train_on_station', ['id'=> $id, 'slug'=>$slug]), 301);
            }
        }
        else
        {
            throw new NotFoundHttpException();
        }
    }

    public function detailsAction($id = 0, $slug = null): Response
    {

    }
}
