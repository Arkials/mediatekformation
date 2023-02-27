<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
                'label'=>"Nom",
                'required'=>true
            ])
            ->add('description')
                
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $playlist = $event->getData();
                $form = $event->getForm();
                if ($playlist->getName()!=null){
                    $form->add('formations', EntityType::class,[
                    'class' => Formation::class,
                    'choice_label' => 'title',
                    'multiple' => true,
                    'required' => false,
                    'disabled'=>true
                    ]);
                    
                }
                
            })
            
            
            ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
