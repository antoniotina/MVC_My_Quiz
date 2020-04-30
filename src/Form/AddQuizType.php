<?php

namespace App\Form;

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
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control'], 'label'  => 'Name of the genre']);
        for ($i = 1; $i <= $options['data']['number']; $i++) {
            $builder
                ->add("question$i", TextType::class, ['attr' => ['class' => 'form-control'], 'label'  => "Question number $i"]);
            for ($z = 1; $z <= 3; $z++) {
                $builder
                    ->add("question$i-reponse$z", TextType::class, ['attr' => ['class' => 'form-control'], 'label'  => "Answer $z to question number $i"]);
            }
            $builder->add("question$i-answer", ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
                'attr' => ['class' => 'form-control'],
                'label'  => "Correct answer to question $i"
            ]);
        }
        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'save'],
            'attr' => ['class' => 'btn btn-primary']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // 'data_class' => Categorie::class,
            'data_class' => null,
        ));
    }
}
