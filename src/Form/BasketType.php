<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Basket;
class BasketType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('user', UserType::class)
      ->add('created', Type\DateTimeType::class)
      ->add('modified', Type\DateTimeType::class)
      ->add('save', Type\SubmitType::class)
    ;
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Basket::class,
      'csrf_protection' => false
    ));
  }
}