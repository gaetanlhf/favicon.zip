<?php

namespace App\Helper;

use JDZ\Favicon\Generator;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Filesystem;

class FaviconHelper
{
    private $flashBag;
    private $filesystem;

    public function __construct(FlashBagInterface $flashBag, Filesystem $filesystem)
    {
        $this->flashBag = $flashBag;
        $this->filesystem = $filesystem;
    }

    public function generate(string $path, string $id, string $appName = null, string $shortAppName = null, string $language = null, string $startUrl = null, string $themeColour = null, string $backgoundColour = null, string $display = null, bool $sixtyFourIco = null, bool $fortyEightIco = null, bool $apple = null, bool $android = null, bool $ms = null, string $tileColour = null, int $tilePadding = null): Response
    {

        $hexColorPattern = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';

        if ($android && (!$appName || !$shortAppName || !$language || !$startUrl || !$themeColour || !$backgoundColour) || $ms && (!$tileColour || !$tilePadding)) {
            $this->flashBag->add("generator_error", "You have not filled out some fields correctly.");
            return new RedirectResponse("/");
        }
        if ($themeColour && !preg_match($hexColorPattern, $themeColour)) {
            $this->flashBag->add("generator_error", "Invalid theme color format. Expected hexadecimal color.");
            return new RedirectResponse("/");
        }
        if ($backgoundColour && !preg_match($hexColorPattern, $backgoundColour)) {
            $this->flashBag->add("generator_error", "Invalid background color format. Expected hexadecimal color.");
            return new RedirectResponse("/");
        }
        if ($tileColour && !preg_match($hexColorPattern, $tileColour)) {
            $this->flashBag->add("generator_error", "Invalid tile color format. Expected hexadecimal color.");
            return new RedirectResponse("/");
        }

        $config = array_merge([
            "filePath"      => $path . $id . ".png",
            "destPath"      => $path . $id . "/",
            "appName"       => $appName,
            "appShortName"  => $shortAppName,
            "appLanguage"   => $language,
            "appStartUrl"   => $startUrl,
            "appThemeColor" => $themeColour,
            "appBgColor"    => $backgoundColour,
            "appDisplay"    => $display,
            "use64Icon"     => $sixtyFourIco,
            "use48Icon"     => $fortyEightIco,
            "noOldApple"    => !$apple,
            "noAndroid"     => !$android,
            "noMs"          => !$ms,
            "tileColor"     => $tileColour,
            "tilePadding"   => $tilePadding
        ]);
        $generator = new Generator($config);
        try {
            $generator->execute();
            $zip = new ZipArchive();
            $zipName = "favicon-" . $id . ".zip";
            $zip->open($zipName,  \ZipArchive::CREATE);
            $faviconBuffer = $generator->getInfoBuffer();
            if ($android) {
                $file = file_get_contents($path . $id . "/favicon/manifest.json");
                $file = str_replace("\/", "/", $file);
                $file = str_replace("/favicon/", "/", $file);
                $this->filesystem->remove($path . $id . "/favicon/manifest.json");
                $this->filesystem->appendToFile($path . $id . "/favicon/manifest.json", $file);
                $this->filesystem->rename($path . $id . "/favicon/manifest.json", $path . $id . "/favicon/manifest.webmanifest");
                $faviconBuffer = str_replace("manifest.json", "manifest.webmanifest", $faviconBuffer);
            }
            if ($ms) {
                $file = file_get_contents($path . $id . "/favicon/browserconfig.xml");
                $file = str_replace("/favicon", "", $file);
                $file = str_replace("#333", $tileColour, $file);
                $this->filesystem->remove($path . $id . "/favicon/browserconfig.xml");
                $this->filesystem->appendToFile($path . $id . "/favicon/browserconfig.xml", $file);
            }
            foreach ($faviconBuffer as $file) {
                $zip->addFromString(basename($file),  file_get_contents($file));
            }
            $this->filesystem->appendToFile($path . $id . "/code.html", "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"/favicon-16x16.png\" />");
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"/favicon-32x32.png\" />");
            if ($android) {
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"application-name\" content=\"$appName\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"apple-mobile-web-app-title\" content=\"$appName\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"theme-color\" content=\"$themeColour\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"manifest\" href=\"/manifest.webmanifest\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"36x36\" href=\"/android-chrome-36x36.png\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"48x48\" href=\"/android-chrome-48x48.png\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"72x72\" href=\"/android-chrome-72x72.png\" />");
            }
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"96x96\" href=\"/favicon-96x96.png\" />");
            if ($android) {
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"144x144\" href=\"/android-chrome-144x144.png\" />");
            }
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"icon\" type=\"image/png\" sizes=\"192x192\" href=\"/android-chrome-192x192.png\" />");
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"/apple-touch-icon.png\">");
            if ($apple) {
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"60x60\" href=\"/apple-touch-icon-60x60.png\" />");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"/apple-touch-icon-72x72.png\" />");
            }
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"/apple-touch-icon-76x76.png\" />");
            if ($apple) {
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"/apple-touch-icon-114x114.png\" />");
            }
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"/apple-touch-icon-120x120.png\" />");
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"/apple-touch-icon-152x152.png\" />");
            $this->filesystem->appendToFile($path . $id . "/code.html", "\n<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/apple-touch-icon-180x180.png\" />");
            if ($ms) {
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-TileColor\" content=\"$tileColour\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-config\" content=\"/browserconfig.xml\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-square70x70logo\" content=\"/mstile-70x70.png\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-square144x144logo\" content=\"/mstile-144x144.png\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-square150x150logo\" content=\"/mstile-150x150.png\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-wide310x150logo\" content=\"/mstile-310x150.png\">");
                $this->filesystem->appendToFile($path . $id . "/code.html", "\n<meta name=\"msapplication-square310x310logo\" content=\"/mstile-310x310.png\">");
            }
            $zip->addFromString(basename($path . $id . "/code.html"), file_get_contents($path . $id . "/code.html"));
            $zip->close();
            $response = new Response(file_get_contents($zipName));
            $response->headers->set("Content-Type", "application/zip");
            $response->headers->set("Content-Disposition", "attachment;filename=\"$zipName\"");
            $response->headers->set("Content-length", filesize($zipName));
            return $response;
        } catch (\JDZ\Favicon\Exception\GeneratorException $e) {
            $this->flashBag->add("generator_error", $e->getMessage());
            return new RedirectResponse("/");
        }
    }
}
