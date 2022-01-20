<?php 
namespace App\Form\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FileImporterCsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
/*                         'mimeTypes' => [
                            'text/csv',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid csv document', */
                    ])
                ]
            ])
        ;
    }
}