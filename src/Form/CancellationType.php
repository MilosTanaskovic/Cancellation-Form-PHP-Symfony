<?php 

/*
* I won't put namespace and use here.
* If you want to run this app you can put these things here
* In this file I'm gong to write just code ( without namespaces, uses and staf like that)
*/

/**
 * Description of CancellationData
 *
 * @author MilosTanaskovic
 */

 class CancellationType extends AbstractController{
     // put your code here

     private $class;

     public function __construct() {
        $this->class = '\Services\CancellationData';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $cancellationData = $event->getData();
            $form = $event->getForm();
        
            // Radio Buttons (normal and immediate cancel) -> checkbox3(in docs)
            if($cancellationData->isImmediateAvailable()){
                $form->add('checkbox3', RadioBooleanType::class, [
                    'required' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'radio-inline radio gmbook radio-immediate radio-normal'],
                    'choices' => ['form.label.normalCancel'=>'0','form.label.immediateCancel'=>'1'],
                    'expanded' => true,
                    'placeholder' => false,
                    'property_path' => 'immediate'
                ]);
             }
             // Checkbox1 - Domain Cancel
            if($cancellationData->isCancelDomainAvailable() ){
                $form->add('checkbox1', CheckboxType::class, [
                    'required' => false,
                    'label' => 'Cancel Domain',
                    'property_path' => 'cancelDomain'
                ]);
            }
            // Checkbox2 - Service Cancel
            if($cancellationData->isCancelServiceAvailable()){
                $form->add('checkbox2', CheckboxType::class, [
                    'required' => false,
                    'label' => 'Cancel Service',
                    'property_path' => 'cancelService'
                ]);
            }
            // Submmit Button
            $form->add('submmit1', SubmitType::class, [
                'label' => 'Submmit',
                
            ]);

        
        });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'cancellation',
            'translation_domain' =>'forms'
        ));
    }
    public function getBlockPrefix()
    {
        return 'cancellation_data_type';
    }

    public function getDefaultOptions()
    {
        return array('data_class' => $this->class);
    }
 }
