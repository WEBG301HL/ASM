<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Xay dung form
        $builder
        ->add('name')
        ->add('price')
        ->add('quantity', TextType::class)
        ->add('created', DateType::class, [
            'widget' => 'single_text', 'required'=>false // nut chon thoi gian
        ])
        ->add('file', FileType::class, [
            'label' => 'Product Image',
            'required' => false,
            'mapped' => false
        ])
        ->add('image', HiddenType::class, [
            'required' => false
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Confirm'
        ]);
    }
}

?>