<?php

namespace App\Form;

use App\Enum\AvailableFilesExtension;
use App\Service\FileFetcherService;
use App\Service\ListDataBaseFormDTOMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class ListDatabaseFormType extends AbstractType
{
    public function __construct(private FileFetcherService $fileFetcher)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $files = $this->fileFetcher->getFilesInDirectory();
        $extensions = [
            AvailableFilesExtension::CSV => AvailableFilesExtension::CSV,
            AvailableFilesExtension::XML => AvailableFilesExtension::XML,
            AvailableFilesExtension::TXT => AvailableFilesExtension::TXT,
        ];

        $builder->add(
            'databaseFileToProcess',
            ChoiceType::class,
            [
                'label' => 'Оберіть файл для процесінгу',
                'choices' => $files,
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                    'size' => 5,
                ],
            ])
            ->add(
                'selectedExtension',
                ChoiceType::class,
                [
                    'label' => 'Оберіть розширення фінального файлу',
                    'choices' => $extensions,
                    'multiple' => false,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
            ->add(
                'tableToSelect',
                TextType::class,
                [
                    'label' => 'Table name to read articles',
                    'required' => true,
                    'data' => 'wp1of20_posts',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The text field cannot be blank.',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Enter text',
                    ],
                ])
            ->add(
                'contentColumn',
                TextType::class,
                [
                    'label' => 'Table column to parse',
                    'required' => true,
                    'data' => 'post_content',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The text field cannot be blank.',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Enter text',
                    ],
                ])
            ->add(
                'titleColumn',
                TextType::class,
                [
                    'label' => 'Table column to parse',
                    'required' => true,
                    'data' => 'post_title',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'The text field cannot be blank.',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Enter text',

                    ],
                ])
            ->setMethod('POST')
            ->setAction('/process/data');

        $builder->setDataMapper(new ListDataBaseFormDTOMapper());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListDataBaseFormDTO::class,
        ]);
    }

}