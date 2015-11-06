<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Token;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends FOSRestController
{
    /**
     * List all apps.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return array
     */
    public function getAppsAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:App');

        return $repository->findAll();
    }

    /**
     * Get single App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets a App for a given guid",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $guid the app guid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function getAppAction($guid)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:App');

        return $repository->findOneByGuid($guid);
    }

    /**
     * Get tokens for a given App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets Tokens for a given App",
     *     output = "AppBundle\Entity\Token",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $guid the app guid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postAppTokensAction($guid)
    {
        $request = $this->get('request');

        if (!$request->request->get('key')) {
            return 'no key';
        }

        if (!$request->request->get('secret')) {
            return 'no secret';
        }

        if ($request->request->get('key') && $request->request->get('secret')) {

           
            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:App');

            $app = $repository->findOneByGuid($guid);

            $token = new Token();

            $token->setKey($request->request->get('key'));
            $token->setSecret($request->request->get('secret'));
            $token->setApp($app);

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($token);
                $entityManager->flush();

                return $app;
            } catch(\Exception $e) {
                throw new \Exception('Could not persist token');
            }

        }
    }
}
