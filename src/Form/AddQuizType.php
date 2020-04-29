<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AddQuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categorie = $options['data']['categorie'];
        $builder
            ->add('name', TextType::class);
        for ($i = 1; $i <= $options['data']['number']; $i++) {
            $builder
                ->add("question$i", TextType::class);
            for ($z = 1; $z <= 3; $z++) {
                $builder
                    ->add("question$i-reponse$z", TextType::class);
            }
            $builder->add("question$i-answer", ChoiceType::class, [
                'choices' => [
                    '1' => 'stock_yes',
                    '2' => 'stock_no',
                    '3' => 'stock_no',
                ],
            ])
                    ->add('save', SubmitType::class, [
                        'attr' => ['class' => 'save'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // 'data_class' => Categorie::class,
            'data_class' => null,
        ));
    }
}
