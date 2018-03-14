<?php

namespace Moci\ApartemensBundle\Controller;

use Moci\ApartemensBundle\MociApartemensBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Moci\ApartemensBundle\Entity\Apartment;

class ApartmentsController extends FOSRestController
{
    /**
     * @Rest\Get("/apartments")
     *
     * @return array|View Return view with status code or the Json Object
     */
    public function getAllApartments()
    {
        $apartments = $this->getDoctrine()->getRepository('MociApartemensBundle:Apartment')->findAll();
        /*$apartments = $this->getDoctrine()->getRepository('MociApartemensBundle:Apartment')
             ->createQueryBuilder()
             ->select('id,enterdate,street,postcode,country,contact_email')
             ->getQuery()
             ->getResult();*/
        if ($apartments === null) {
            return new View("there are no apartments exist", Response::HTTP_NOT_FOUND);
        }
        return $apartments;
    }

    /**
     * @Rest\Get("/apartment/{id}")
     *
     * @param integer $id id of item to be edited
     *
     * @return object|View Return view with status code or the Json Object
     */
    public function getApartment($id)
    {
        $getApartment = $this->getDoctrine()->getRepository("MociApartemensBundle:Apartment")->find($id);
        if($getApartment === null){
            return new View("Apartment not found.", Response::HTTP_NOT_FOUND);
        }
        return $getApartment;
    }

    /**
     * @Rest\Post("/apartment")
     *
     * @param Request $request Request Parameters for Update
     *
     * @return View Return view with status code or the Json Object
     */
    public function postApartment(Request $request){
        $data = new Apartment();
        $enterdate = $request->get('enterdate');
        $street = $request->get('street');
        $postcode = $request->get('postcode');
        $city = $request->get('city');
        $country = $request->get('country');
        $contactEmail = $request->get('contact_email');
        // generates cryptographically secure pseudo-random bytes. bin2hey transforms binary data into hexadecimal data.
        $token = bin2hex(random_bytes(16));
        if(!empty($enterdate) && !empty($street) && !empty($postcode) && !empty($city) && !empty($country) && !empty($contactEmail)){
            $data->setEnterdate(new \DateTime($enterdate));
            $data->setStreet($street);
            $data->setPostcode($postcode);
            $data->setCity($city);
            $data->setCountry($country);
            $data->setContactEmail($contactEmail);
            $data->setToken($token);
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $this->sendEmail($token);
            return new View("Apartment added successfully", Response::HTTP_OK);
        }else{
            return new View("Please fill all fields", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Put("/apartment/{id}/{token}")
     *
     * @param integer $id id of item to be edited
     * @param string $token Token to verify, that the user is allowed to update the Item
     * @param Request $request Request Parameters for Update
     *
     * @return View Returns view with status code or the Json Object
     */
    public function updateApartment($id, $token, Request $request){
        //return new View(var_dump($request), Response::HTTP_OK);
        $sn = $this->getDoctrine()->getManager();
        $apartment = $this->getDoctrine()->getRepository("MociApartemensBundle:Apartment")->find($id);
        if($apartment->getToken() !== $token){
            return new View("Access denied", Response::HTTP_FORBIDDEN);
        }
        $enterdate = $request->get('enterdate');
        $street = $request->get('street');
        $postcode = $request->get('postcode');
        $city = $request->get('city');
        $country = $request->get('country');
        $contactEmail = $request->get('contact_email');

        if(!empty($enterdate) && !empty($street) && !empty($postcode) && !empty($city) && !empty($country) && !empty($contactEmail)){
            $apartment->setEnterdate(new \DateTime($enterdate));
            $apartment->setStreet($street);
            $apartment->setPostcode($postcode);
            $apartment->setCity($city);
            $apartment->setContactEmail($contactEmail);
            $apartment->setToken($token);
            $sn->flush();
            return new View("Apartment updated", Response::HTTP_OK);
        }else{
            return new View("Please fill all fields", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Delete("/apartment/{id}/{token}")
     *
     * @param integer $id id of item to be edited
     * @param string $token Token to verify, that the user is allowed to update the Item
     *
     * @return View Returns view with status code or the Json Object
     */
    public function deleteApartment($id, $token){
        $sn = $this->getDoctrine()->getManager();
        $apartment = $this->getDoctrine()->getRepository('MociApartemensBundle:Apartment')->find($id);
        if($apartment->getToken() !== $token){
            return new View("Access denied", Response::HTTP_FORBIDDEN);
        }
        else {
            $sn->remove($apartment);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }

    public function sendEmail($token){
        $getApartment = $this->getDoctrine()->getRepository("MociApartemensBundle:Apartment")->findOneBy(array('token' => $token));
        $id = $getApartment->getId();
        $email = $getApartment->getContactEmail();
        $url = "http://animusfrontend.moritz-cichon.de/".apartment."/".$id."/".$token;
        $message = \Swift_Message::newInstance()
            ->setSubject('Wohnung erfolgreich angelegt')
            ->setFrom('Webservice@moritz-cichon.de')
            ->setTo($email)
            ->setBody("Hallo,<br/><br/>ihre Wohnung wurde erfolgreich angelegt, unter der folgenden URL können Sie die Wohnung bearbeiten/löschen:<br/><br/>URL: ".$url."<br/>ID: ".$id."<br/>Token: ".$token."<br/><br/>Mit besten Grüßen,<br/><br/>der Wohnungsservice.");
        $message->setContentType("text/html");
        $this->get('mailer')->send($message);
    }
}
