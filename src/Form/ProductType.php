<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        ->add('description')
        ->add('created', DateType::class, [
            'widget' => 'single_text', 'required'=>false // nut chon thoi gian
        ])
        ->add('cat', EntityType::class, ['class'=>Category::class,'choice_label'=>'id'])
        ->add('sup', EntityType::class, ['class'=>Supplier::class,'choice_label'=>'id'])
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