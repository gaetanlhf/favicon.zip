<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\FaviconFormType;
use App\Helper\FaviconHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\UploaderHelper;
use Symfony\Component\HttpFoundation\Cookie;

class GenerateController extends AbstractController
{
    #[Route("/generate", name: "app_generate")]
    public function index(Request $request, UploaderHelper $uploaderHelper, FaviconHelper $faviconHelper): Response
    {
        $faviconForm = $this->createForm(FaviconFormType::class);
        $faviconForm->handleRequest($request);

        if ($faviconForm->isSubmitted() && $faviconForm->isValid()) {
            $data = $faviconForm->getData();
            $id = $uploaderHelper->uploadFile($this->getParameter("img_temp"), $data["drop"]);
            if ($id == null) {
                return $this->redirectToRoute("app_home");
            }
            $favicon = $faviconHelper->generate($this->getParameter("img_temp"), $id, $data["appName"], $data["shortAppName"], $data["language"], $data["startUrl"], $data["themeColour"], $data["backgroundColour"], $data["display"], $data["sixtyFourIco"], $data["fortyEightIco"], $data["apple"], $data["android"], $data["ms"], $data["tileColour"]);
            $uploaderHelper->deleteUploadedFile($this->getParameter("img_temp"), $id);
            if ($favicon == null) {
                return $this->redirectToRoute("app_home");
            }
            if ($data["downloadToken"] != null) {
                $favicon->headers->setCookie(Cookie::create("downloadToken", $data["downloadToken"], strtotime("tomorrow"), "/", null, null, false, false, Cookie::SAMESITE_STRICT));
            }
            return $favicon;
        } else {
            $form_errors = array();
            foreach ($faviconForm->getErrors(true) as $error) {
                $form_errors[] = $error->getMessage();
            }
            $this->addFlash("form_error", $form_errors);
            return $this->redirectToRoute("app_home");
        }
    }
}
