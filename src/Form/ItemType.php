<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Item;
class ItemType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', Type\TextType::class)
      ->add('description', Type\TextType::class)
      ->add('created', Type\DateTimeType::class)
      ->add('modified', Type\DateTimeType::class)
      ->add('save', Type\SubmitType::class)
    ;
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Item::class,
      'csrf_protection' => false
    ));
  }
}