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

class CancellationData {
 // put your code here
    /**
     * @var string
     */
    private $domainName;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    protected $domainTransitions;
    /**
     * @var array
     */
    protected $serviceTransitions;
    
    /**
     * @var bool
     */
    private $immediate;
    /**
     * @var bool
     */
    private $cancelDomain;

    /**
     * @var bool
     */
    private $cancelService;

    private static $status_to_immediate_mapping = [
        'pendingsuspension' => false,
        'suspended' => true,
        'pendingtermination' => false,
        'terminated' => true
    ];

    public function __construct(){

    }

    private static function isStatusImmediate($status){
        return self::$status_to_immediate_mapping[$status];
    }

    // getter
    public function getDomainName(){
        return $this->domainName;
    }
    // getter
    public function getHash(){
        return $this->hash;
    }
    // getter
    public function isImmediate(){
        return $this->immediate;
    }
    // setter
    public function setImmediate($immediate){
        $this->immediate = $immediate;
    }
    // function
    public function isImmediateAvailable(){

    }

    // getter
    public function getCancelDomain(){
        return $this->cancelDomain;
    }
    // setter
    public function setCancelDomain($cancelDomain){
        $this->cancelDomain = $cancelDomain;
    }
    // function
    public function isCancelDomainAvailable(){

    }

    // getter
    public function getCancelService(){
        return $this->cancelService;
    }
    // setter
    public function setCancelService($cancelService){
        $this->cancelService = $cancelService;
    }
    // function
    public function isCancelServiceAvailable(){

    }

    // function
    public function isSubmitAvailable(){

    }

    // function
    public function getTargetTransition(){

    }
    // function
    public function getTargetMessage(){

    }

    // function
    public function isCancelAvailable(){

    }

    // function
    public function isImplicit(){

    }


}