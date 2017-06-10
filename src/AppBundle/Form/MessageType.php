<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $bulider, array $options)
    {
        $bulider->add('UserName')->add('Msg');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //    $resolverArray = ('data_class' => 'AppBundle\Entity\job');
        $resolver->setDefaults(array('data_class' => 'AppBundle\Entity\Message'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appBundle_Message';
    }
}
