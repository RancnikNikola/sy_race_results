<?php

namespace App\Form;

use App\Entity\CsvUploadFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// ------------------------------------------------------ //

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class CsvUploadFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('races', RaceType::class, [
                'mapped' => false
            ])
            ->add('csvFile', FileType::class, [
                'label' => 'Upload your CSV',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/x-comma-separated-values', 
                            'text/comma-separated-values', 
                            'text/x-csv', 
                            'text/csv', 
                            'text/plain',
                            'application/octet-stream', 
                            'application/vnd.ms-excel', 
                            'application/x-csv', 
                            'application/csv', 
                            'application/excel', 
                            'application/vnd.msexcel', 
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid CSV document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CsvUploadFile::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'results_item'
        ]);
    }
}
