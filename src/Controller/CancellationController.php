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

        $cancellationData = new CancellationData($domainName, $hash, $domain_status, $service_status);
        $cancellationView = null;

        if( $cancellationData->isCancelAvailable() ){

            $cancellationForm = $this->createForm(CancellationType::class, $cancellationData);
            $cancellationView = $cancellationForm->createView();
        }
        return $this->render('domain/cancellationForm.html.twig', array('form' => $cancellationView));
    }

    public function cancelAction($domainName, $hash, Request $request){

    }
}
