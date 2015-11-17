<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Token;
use AppBundle\Entity\App;

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
     * @param int $appGuid the app guid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function getAppAction($appGuid)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:App');

        $entity = $repository->findOneByGuid($appGuid);

        if (!$entity) {
            throw new HttpException(404, "Entity App not found");
        }

        return $entity; 
    }

     /**
     * Creates an App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets Tokens for a given App",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postAppAction()
    {
        $request = $this->get('request');

        $slug = $request->request->get('slug');

        if (!$slug) {
            throw new HttpException(500, 'Missing Slug');
        }

        $app = new App();

        $app->setSlug($slug);
        $app->setGuid(rand());

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($app);
            $entityManager->flush();

            return $app;

        } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw new HttpException(500, "App already exists");

        } catch(\Exception $e) {
            throw new HttpException(500, $e . " : The app could not be created");
        }  
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
     * @param int $appGuid the app guid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postAppTokensAction($appGuid)
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

        $app = $repository->findOneByGuid($appGuid);

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

    /**
     * Removes a token.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @return View
     */
    public function deleteAppTokenAction($appGuid, $tokenKey) {
        $token = $this->getDoctrine()
            ->getRepository('AppBundle:Token')
            ->findOneByKey($tokenKey);

        if (!$token) {
            throw new HttpException(404, "Token not found");
        }

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($token);
            $entityManager->flush();

        } catch(\Exception $e) {
            throw new HttpException(500, "Your Token Entity could not be deleted");
        }  

        $app = $this->getDoctrine()
            ->getRepository('AppBundle:App')
            ->findOneByGuid($appGuid);

        return $app;
    }

    /**
     * Removes an app.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @return View
     */
    public function deleteAppAction($appGuid) {
        $app = $this->getDoctrine()
            ->getRepository('AppBundle:App')
            ->findOneByGuid($appGuid);

        if (!$appGuid) {
            throw new HttpException(404, "App not found");
        }

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($app);
            $entityManager->flush();

        } catch(\Exception $e) {
            throw new HttpException(500, "Your App Entity could not be deleted");
        }  

        throw new HttpException(204);
    }
}
