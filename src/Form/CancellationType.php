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

     /**
     * @var TranslatorInterface
     */
    private $translator;

     public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
        $this->class = '\Services\CancellationData';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $cancellationData = $event->getData();
            $form = $event->getForm();
        
            // Radio Buttons (normal and immediate cancel) -> checkbox3(in docs)
            if($cancellationData->isImmediateAvailable()){

                if(!($cancellationData->isCancelDomainAvailable(false) || $cancellationData->getCancelDomain())){ // Checkbox1 for Domain is not available
                    $normalLabel = $this->translator->trans('form.label.normalCancel.Service.%SERVICE_NAME%', array('%SERVICE_NAME%' => $cancellationData->getServiceName()), 'forms');
                }else if(!($cancellationData->isCancelServiceAvailable(false) || $cancellationData->getCancelService())){
                    $normalLabel = $this->translator->trans("form.label.normalCancel.Domain.%DOMAIN_NAME%", ['%DOMAIN_NAME%' => $cancellationData->getDomainName()],"forms");
                    
                }else {
                    $normalLabel = $this->translator->trans('form.label.normalCancel', array(), 'forms');
                }
                
                
                if(!($cancellationData->isCancelDomainAvailable(true) || $cancellationData->getCancelDomain())){
                    $immediateLabel = $this->translator->trans('form.label.immediateCancel.Service.%SERVICE_NAME%', array('%SERVICE_NAME%' => $cancellationData->getServiceName()), 'forms');
                }else if(!($cancellationData->isCancelServiceAvailable(true) || $cancellationData->getCancelService())){
                    $immediateLabel = $this->translator->trans('form.label.immediateCancel.Domain.%DOMAIN_NAME%', array('%DOMAIN_NAME%' => $cancellationData->getDomainName()), 'forms');
                }else {
                    $immediateLabel = $this->translator->trans('form.label.immediateCancel', array(), 'forms');
                }

                $form->add('checkbox3', RadioBooleanType::class, [
                    'required' => false,
                    'label' => false,
                    'label_attr' => ['class' => 'radio-inline radio gmbook radio-immediate radio-normal'],
                    'choices' => [$normalLabel=>'0', $immediateLabel=>'1'],
                    'expanded' => true,
                    'placeholder' => false,
                    'property_path' => 'immediate'
                ]);
             }

             // Checkbox1 - Domain Cancel
            if($cancellationData->isCancelDomainAvailable() ){

                if( !$cancellationData->isImmediateAvailable() && $cancellationData->isImmediate() ){ // Radio buttons are not available
                    $domainLabel = $this->translator->trans('form.label.cancelDomainAvailable.immediateDomain.%DOMAIN_NAME%', array('%DOMAIN_NAME%' => $cancellationData->getDomainName()), 'forms');
                }else{
                    $domainLabel = $this->translator->trans('form.label.cancelDomainAvailable.%DOMAIN_NAME%', array('%DOMAIN_NAME%' => $cancellationData->getDomainName()), 'forms');
                }

                $form->add('checkbox1', CheckboxType::class, [
                    'required' => false,
                    'label' => $domainLabel,
                    'property_path' => 'cancelDomain'
                ]);
            }

            // Checkbox2 - Service Cancel
            if($cancellationData->isCancelServiceAvailable()){

                if(!$cancellationData->isImmediateAvailable() && $cancellationData->isImmediate() ){ // Radio buttons are not available
                    $serviceLabel = $this->translator->trans('form.label.cancelServiceAvailable.immediateService.%SERVICE_NAME%', array('%SERVICE_NAME%' => $cancellationData->getServiceName()), 'forms');
                }else{
                    $serviceLabel = $this->translator->trans('form.label.cancelServiceAvailable.%SERVICE_NAME%', array('%SERVICE_NAME%' => $cancellationData->getServiceName()), 'forms');
                }

                $form->add('checkbox2', CheckboxType::class, [
                    'required' => false,
                    'label' => $serviceLabel,
                    'property_path' => 'cancelService'
                ]);
            }

            // reason for dismissal - checkboxes 
            $form->add('checkbox4', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.noLongerNeeded', 
                'property_path' => 'noNeed'
            ]);
            $form->add('checkbox5', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.foundADifferentProvider', 
                'property_path' => 'quality'
            ]);
            $form->add('checkbox6', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.Quality', 
                'property_path' => 'support'
            ]);
            $form->add('checkbox7', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.Support', 
                'property_path' => 'newProvider'
            ]);
            $form->add('checkbox8', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.tooExpensive', 
                'property_path' => 'price'
            ]);
            $form->add('checkbox9', CheckboxType::class ,[
                'required' => false,
                'label' => 'form.label.needToCutCosts', 
                'property_path' => 'cutCosts'
            ]);

            // What would convince you to stay?
            $form->add('convinceStay', TextareaType::class , [
                'required' => false,
                'label' => false,
                'attr'=> array('placeholder'=>'form.placeholder.convinceStay','cols'=> 30, 'rows'=>5,'maxlength'=>150),
            ]);

            // Submmit Buttons
            $form->add('submmit1', SubmitType::class, [
                'label' => 'Submmit',
                'attr' => array('class' => 'btn btn-block btn-md btn-success LongRunning'),
                'disabled' => true
            ]);
            $form->add('submmit2', SubmitType::class, [
                'label' => 'form.label.submitCancellation',
                'attr' => array('class' => 'btn btn-block btn-md btn-success LongRunning'),
                'disabled' => true
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
