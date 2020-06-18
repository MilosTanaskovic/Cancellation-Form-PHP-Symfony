<?php

/*
* I won't put namespace and use here.
* If you want to run this app you can put these things here
* In this file I'm gong to write just code ( without namespaces, uses and staf like that)
*/

/**
 * Description of CancellationController
 *
 * @author MilosTanaskovic
 */
/**
 * @Security("has_role('ROLE_USER') and is_granted('IS_AUTHENTICATED_FULLY')")
 */

class CancellationController extends AbstractController {
    // put your code here
     /**
     * @var Encryption
     */
    private $encryption;
    public function __construct(Encryption $encryption) {
        $this->encryption = $encryption;
    }

    public function beginCancellationAction($domainName, $hash){

        // hardcode DataBase
        $doman_status = [
            'PendingSuspension' => true,
            'Suspended' => true
        ];
        $service_status = [
            'PendingTermination' => true,
            'Terminated' => true,
        ];

        $cancellationData = new CancellationData($domainName, $hash, $domain_status, $service_status, $serviceName, $multiDomain, $otherDomains, null, null);
        $cancellationView = null;

        if( $cancellationData->isCancelAvailable() ){

            $cancellationForm = $this->createForm(CancellationType::class, $cancellationData);
            $cancellationView = $cancellationForm->createView();
        }
        return $this->render('domain/cancellationForm.html.twig', array('form' => $cancellationView));
    }

    public function cancelAction($domainName, $hash, Request $request){

        // hardcode DataBase
        $doman_status = [
            'PendingSuspension' => true,
            'Suspended' => true
        ];
        $service_status = [
            'PendingTermination' => true,
            'Terminated' => true,
        ];

        $cancellationData = new CancellationData($domainName, $hash, $domain_status, $service_status, $serviceName, $multiDomain, $otherDomains, $expiryDate, $dueDate);
        //$cancellationView = null;
        if( $cancellationData->isCancelAvailable() ){           
      
            $cancellationForm = $this->createForm(CancellationType::class, $cancellationData);
            $cancellationForm->handleRequest($request);

            if($cancellationForm->isSubmitted() ){
                
                if(!$cancellationForm->isValid()){
                    return $this->render('domain/cancellationResults.html.twig', [
                        'cancellationData' => null
                    ]);
                }
               
                if($status_update_result['result'] === 'success' ){
                    $this->addFlash('cancel-success', $cancellationData->getTargetMessage());
                }else {
                    $this->addFlash('cancel-error', 'Error cancelling domain or service');
                    return $this->redirectToRoute('cancellation_begin', ['domainName'=> $domainName, 'hash'=> $hash]);
                }
              
            }else {

                $this->addFlash('cancel-error', 'Form is not submited');
                return $this->redirectToRoute('cancellation_begin', ['domainName'=> $domainName, 'hash'=> $hash]);
            }

        } else {
             $this->addFlash('cancel-error', 'domain and/or service are not available for cancellation');
        }
        return $this->render('domain/cancellationResults.html.twig', [
            'cancellationData' => $cancellationData
        ]);

    }
}
