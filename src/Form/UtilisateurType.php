<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'required' => false,
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 20
                ]
            ])
            ->add('adresseEmail', EmailType::class)
            ->add('plainPassword', PasswordType::class, [
                "mapped" => false,
                //Les assertions
                "constraints" => [
                    new NotBlank(),
                    new NotNull(),
                    new Length(min:8, max:30, minMessage: "Le mot de passe est trop court", maxMessage: "le mot de passe est trop long"),
                    new Regex(pattern:"#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$#", message: "Le mot de passe n'est pas valide. Votre mot de passe doit avoir au moins une minuscule, une majuscule et un chiffre")
                ],
                'attr' => [
                    'minlength' => 8,
                    'maxlength' => 20,
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)[a-zA-Z\\d]{8,30}$'
                ]
            ])
            ->add('fichierPhotoProfil', FileType::class, [
                "mapped" => false,
                //Les assertions
                "constraints" => [
                    new File(
                        maxSize: '10M',
                        maxSizeMessage: "La taille du fichier est trop grande. Votre fichier ne doit pas dépasser 10 Mégaoctets.",
                        extensions: [
                            'jpg',
                            'png'
                        ],
                        extensionsMessage: "Le format du fichier est incorrecte. Veuillez insérer un fichier jpg ou png.")
                ]])
            ->add('inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
