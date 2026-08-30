<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nazwa zadania',
                'attr' => [
                    'placeholder' => 'Nazwa zadania',
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500',
                ],
                'label_attr' => ['class' => 'block text-sm font-semibold text-slate-700 mb-1'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis (opcjonalnie)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Opis zadania',
                    'rows' => 4,
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500',
                ],
                'label_attr' => ['class' => 'block text-sm font-semibold text-slate-700 mb-1'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz zadanie',
                'attr' => [
                    'class' => 'bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded transition cursor-pointer',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'csrf_protection' => false,
        ]);
    }
}
