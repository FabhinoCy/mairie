<?php

namespace App\EventSubscriber;

use App\Repository\EvenementRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $evenementRepository;
    private $router;

    public function __construct(
        EvenementRepository $evenementRepository,
        UrlGeneratorInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->evenementRepository = $evenementRepository;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        $evenements = $this->evenementRepository
            ->createQueryBuilder('evenement')
            ->where('evenement.beginAt BETWEEN :start and :end OR evenement.endAt BETWEEN :start and :end')
            ->andWhere('evenement.public = 1')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        $user = $this->tokenStorage->getToken()->getUser();

        $evenementsPrivate = $this->evenementRepository
            ->createQueryBuilder('evenement')
            ->where('evenement.beginAt BETWEEN :start and :end OR evenement.endAt BETWEEN :start and :end')
            ->andWhere('evenement.public = 0')
            ->andWhere('evenement.user = :user')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;

        $evenements = array_merge($evenements, $evenementsPrivate);


        foreach ($evenements as $evenement) {

            // this create the events with your data (here evenement data) to fill calendar
            $evenementEvent = new Event(
                $evenement->getTitle(),
                $evenement->getBeginAt(),
                $evenement->getEndAt(), // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $backgroundColor = $evenement->getBackgroundcolor();
            $borderColor = $evenement->getBordercolor();
            $textColor = $evenement->getTextcolor();

            $evenementEvent->setOptions([
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'textColor' => $textColor
            ]);

            $evenementEvent->addOption(
                'url',
                $this->router->generate('app_evenement_show', [
                    'id' => $evenement->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($evenementEvent);
        }
    }
}