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
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\HttpException;



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

        $entity = $repository->findOneByGuid($guid);

        throw new HttpException(400, "blahblah");

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find app entity');
        }


        return $entity; 
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
            throw new HttpException(500, 'Missing Key');
        }

        if (!$request->request->get('secret')) {
            throw new HttpException(500, 'Missing Secret');
        }

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

        } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw new HttpException(500, "Token already exists");
        }  
    }
}
