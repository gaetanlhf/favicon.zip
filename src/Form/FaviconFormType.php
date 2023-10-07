<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class FaviconFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("drop", DropzoneType::class, [
                "label" => false,
                "required" => true,
                "attr" => [
                    "accept" => "image/*",
                    "placeholder" => "Drag and drop an image or browse",
                ],
                "constraints" => [
                    new Image([
                        "maxSize" => "1M",
                        "mimeTypes" => [
                            "image/*"
                        ],
                        "mimeTypesMessage" => "Only image formats are supported."
                    ],)
                ],
            ])
            ->add("fortyEightIco", CheckboxType::class, ["label" => "Include a 48x48 version in the .ico file",  "data" => false, "label_attr" => ["class" => "checkbox-switch"], "attr" => ["autocomplete" => "off"], "required" => false])
            ->add("sixtyFourIco", CheckboxType::class, ["label" => "Include a 64x64 version in the .ico file", "data" => false, "label_attr" => ["class" => "checkbox-switch"], "attr" => ["autocomplete" => "off"], "required" => false])
            ->add("android", CheckboxType::class, ["label" => "Generate Chrome/Android images and webmanifest", "data" => false, "label_attr" => ["class" => "checkbox-switch"], "attr" => ["data-bs-toggle" => "collapse", "data-bs-target" => "#collapseAndroid", "autocomplete" => "off"], "required" => false])
            ->add("appName", TextType::class, ["label" => "Application name", "attr" => ["placeholder" => "My app"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("shortAppName", TextType::class, ["label" => "Short application name", "attr" => ["placeholder" => "App"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("language", TextType::class, ["label" => "Language", "attr" => ["placeholder" => "en-GB"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("startUrl", TextType::class, ["label" => "Start URL", "attr" => ["placeholder" => "/"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("themeColour", TextType::class, ["label" => "Theme colour", "attr" => ["placeholder" => "#333"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("backgroundColour", TextType::class, ["label" => "Background colour", "attr" => ["placeholder" => "#fff"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("display", ChoiceType::class, ["label" => "Display", "data" => "minimal", "choices"  => [
                "Fullscreen" => "fullscreen",
                "Standalone application" => "standalone",
                "Minimal set of UI elements" => "minimal",
                "Browser" => "browser"
            ], "placeholder" => false, "required" => false])
            ->add("apple", CheckboxType::class, ["label" => "Generate old Apple touch images", "label_attr" => ["class" => "checkbox-switch"], "attr" => ["autocomplete" => "off"], "required" => false])
            ->add("ms", CheckboxType::class, ["label" => "Generate Microsoft Windows and IE tile images", "label_attr" => ["class" => "checkbox-switch"], "attr" => ["data-bs-toggle" => "collapse", "data-bs-target" => "#collapseMs", "autocomplete" => "off"], "required" => false])
            ->add("tileColour", TextType::class, ["label" => "Tile colour", "attr" => ["placeholder" => "#333"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("tilePadding", TextType::class, ["label" => "Tile padding (in pixel)", "attr" => ["placeholder" => "10"], "row_attr" => [
                "class" => "form-floating",
            ], "required" => false])
            ->add("downloadToken", HiddenType::class)
            ->add("submit", SubmitType::class, ["label" => "Generate", "attr" => ["class" => "btn btn-primary btn-lg w-100 mx-0"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
