<?php

namespace App\Helper;

use JDZ\Favicon\Generator;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FaviconHelper
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function generate(string $path, string $id, string $appName, string $shortAppName, string $language, string $startUrl, string $themeColour, string $backgoundColour, string $display, string $sixtyFourIco, string $fortyEightIco, string $apple, string $android, string $ms): Response
    {
        $config = array_merge([
            'filePath'      => $path . $id . ".png",
            'destPath'      => $path . $id . "/",
            'appName'       => $appName,
            'appShortName'  => $shortAppName,
            'appLanguage'   => $language,
            'appStartUrl'   => $startUrl,
            'appThemeColor' => $themeColour,
            'appBgColor'    => $backgoundColour,
            'appDisplay'    => $display,
            'use64Icon'     => $sixtyFourIco,
            'use48Icon'     => $fortyEightIco,
            'noOldApple'    => !$apple,
            'noAndroid'     => !$android,
            'noMs'          => !$ms
        ]);
        $generator = new Generator($config);
        try {
            $generator->execute();
            $zip = new ZipArchive();
            $zipName = 'favicon-' . $id . '.zip';
            $zip->open($zipName,  \ZipArchive::CREATE);
            foreach ($generator->getInfoBuffer() as $file) {
                $zip->addFromString(basename($file),  file_get_contents($file));
            }
            $zip->close();
            $response = new Response(file_get_contents($zipName));
            $response->headers->set('Content-Type', 'application/zip');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
            $response->headers->set('Content-length', filesize($zipName));
            return $response;
        } catch (\JDZ\Favicon\Exception\GeneratorException $e) {
            $this->flashBag->add('generator_error', $e->getMessage());
            return new RedirectResponse("/");
        }
    }
}
