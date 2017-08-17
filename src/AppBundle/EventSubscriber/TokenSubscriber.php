<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Token;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenSubscriber implements EventSubscriberInterface
{
    private $em;

    private $webResourceGenerator;

    public function __construct(EntityManagerInterface $em, WebResourceGeneratorInterface $webResourceGenerator)
    {
        $this->em = $em;
        $this->webResourceGenerator = $webResourceGenerator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {
            /*if (!in_array($token, $this->tokens)) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }*/
            if (!$event->getRequest()->headers->has('X-Auth-Token')) {
                $result = new MessageResult();
                $result->isError();
                $result->setDescription("No puede acceder a este recurso sin estar logueado");
                $result->addDefaultMessage("No se ha mandado el token");

                throw new HttpException(401, $this->webResourceGenerator->createMessageResource($result), null, ['Content-Type' => 'application/json']);
                //throw new AccessDeniedHttpException('This action needs a valid token!');
            }

            $token = $event->getRequest()->headers->get('X-Auth-Token');

            // Find player or launch exception if not find one for token
            /** @var EntityRepository $playerRepository */
            $repository = $this->em->getRepository(Token::class);
            /** @var Token $tokenEntity */
            $tokenEntity = $repository->findOneBy(['tokenString' => $token]);

            if ($tokenEntity === null) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }

            // Add player to request
            $event->getRequest()->attributes->add(['player' => $tokenEntity->getPlayer()]);
        }
    }
}
