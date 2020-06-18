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
     * @var string
     */
    private $serviceName;

    /**
     * @var bool 
     */
    private $multiDomain; 

    /**
     * @var array 
     */
    private $otherDomains;

    /**
     * @var array
     */
    private $expiryDate; // for Domain
    private $dueDate; // for Hosting

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

    /**
     * Reason for Dismissal
     * @var bool
     */
    private $noNeed;      // Don't it any more.
    private $quality;     // Quality
    private $support;     // Support
    private $newProvider; // Found a different provider
    private $price;       // Too expencive
    private $cutCosts;    // Need to cut cost
    /**
     * What would convince you to stay?
     * @var string 
     */
    private $convinceStay;
    
    private $hashToken; 


    public function __construct($aDoamin, $aHash, $aDomain_status, $aService_status, $aHosting, $aMultiDomain, $aOtherDomains, $aExpiryDate, $aDueDate) {
        
        $this->domainName = $aDoamin;
        $this->hash = $aHash;
        $this->serviceName = $aHosting;
        
        $this->multiDomain = $aMultiDomain;
        $this->otherDomains = $aOtherDomains;
        
        $this->expiryDate = $aExpiryDate;
        $this->dueDate = $aDueDate;         
        
        $this->domainTransitions = [];
        foreach ($aDomain_status as $status => $both){
            if(key_exists($status, self::$status_to_immediate_mapping)){
                $transition = new Transition($status, $both);
                $this->domainTransitions[] = $transition; 
            }                     
        }
        $this->serviceTransitions = [];
        foreach ($aService_status as $status => $both){
            if(key_exists($status, self::$status_to_immediate_mapping)){
                $transition = new Transition($status, $both);
                $this->serviceTransitions[] = $transition;
            }            
        }
        // calculate immediate
        $this->immediate = true;
        foreach ($this->domainTransitions as $transition) {
            $this->immediate = $this->immediate && self::isStatusImmeadiate($transition->getName());
        }
        foreach ($this->serviceTransitions as $transition) {
            $this->immediate = $this->immediate && self::isStatusImmeadiate($transition->getName());
        }
  
        // calculate cancel domain
        $this->cancelDomain = false;
        foreach ($this->domainTransitions as $transition) {
            if(self::isStatusImmeadiate($transition->getName()) == $this->immediate && $transition->getBoth() == false  && !$this->isImplicit($this->immediate)){
                $this->cancelDomain = true;
                break;
            }         
        }  
        // calculate cancel service 
        //$this->cancelDomain = count($this->serviceTransitions) == 0;
        $this->cancelService = false;
        foreach ($this->serviceTransitions as $transition) {
              if(self::isStatusImmeadiate($transition->getName()) == $this->immediate && $transition->getBoth() == false ){
                  $this->cancelService = true;
                  break;
              }         
        }
        
        // calculate each checkboxes(reason for cancellation)
        $this->noNeed = false;
        
        
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
    public function getServiceName() {
        return $this->serviceName;
    }
    // getter
    public function getMultiDomain(){
        return $this->multiDomain;
    }
    // getter
    public function getOtherDomains(){
        return $this->otherDomains;
    }
    // getter
    public function getDueDate() {
        return $this->dueDate;
    }
    // getter
    public function getExpiryDate() {
        return $this->expiryDate;
    }
    // getter
    public function isImmediate(){
        return $this->immediate;
    }
    // setter
    public function setImmediate($immediate){
        $this->immediate = $immediate;
    }
    // function/method
    public function isImmediateAvailable(){
        $normal_exist = false;
        $immediate_exist = false;
        foreach ($this->domainTransitions as $transition) {
            // ! is the same as == false
            if(self::isStatusImmeadiate($transition->getName())){
                $immediate_exist = true;
            }else {
                $normal_exist = true;
            }          
        }       
        foreach ($this->serviceTransitions as $transition) {
            if(self::isStatusImmeadiate($transition->getName())){
                $immediate_exist = true;
            }else {
                $normal_exist = true;
            }         
        } 
        return $immediate_exist && $normal_exist && $this->areDomainAndServiceInCompatibileStates();
    }

    // getter
    public function getCancelDomain(){
        return $this->cancelDomain;
    }
    // setter
    public function setCancelDomain($cancelDomain){
        $this->cancelDomain = $cancelDomain;
    }
    // function/method
    public function isCancelDomainAvailable($immediate = null){

        if($immediate === null){ // in this case method returns if chekbox1 is available at all
            return count($this->serviceTransitions) > 0 && count($this->domainTransitions) > 0;
        }else {     // in this case method returns if chekbox1 is visible for given $immediate value       
            foreach ($this->domainTransitions as $transition) {
                if(self::isStatusImmeadiate($transition->getName()) == $immediate && ($transition->getBoth() == true || $this->isImplicit($immediate))){
                    return true;
                }         
            } 
            return false;
        }
    }

    // getter
    public function getCancelService(){
        return $this->cancelService;
    }
    // setter
    public function setCancelService($cancelService){
        $this->cancelService = $cancelService;
    }
    // function/method
    public function isCancelServiceAvailable($immediate = null){

        if($immediate === null){ // in this case method returns if chekbox2 is available at all
            return count($this->serviceTransitions) > 0 && count($this->domainTransitions) > 0;
        }else {     // in this case method returns if chekbox2 is visible for given $immediate value       
            foreach ($this->serviceTransitions as $transition) {
                if(self::isStatusImmeadiate($transition->getName()) == $immediate && $transition->getBoth() == true ){
                    return true;
                }         
            } 
            return false;
        }
    }

    // function/method
    public function isSubmitAvailable($immediate){

        return (!$this->isCancelDomainAvailable($immediate) || !$this->isCancelServiceAvailable($immediate));
    }

    // function/method
    public function getTargetTransition(){

        if($this->getCancelDomain()){
            $transition = $this->isImmediate() ? 'suspended' : 'pendingsuspension';
            foreach ($this->domainTransitions as $domainTransition){
                if($domainTransition->getName() == $transition){
                    $both = $domainTransition->getBoth() ? $this->getCancelService() : null;
                    break;
                }
            }
            
            // api params[domainid,transition,both]
            
            return new Transition($transition, $both);
        }else {
            $transition = $this->isImmediate() ? 'terminated' : 'pendingtermination';
            $both = null;
            // api params[serviceid,transition,both]

            return new Transition($transition, $both);
        }
    }
    // function/method
    public function getTargetMessage(){

        if($this->getCancelDomain()){          
           
            if($this->isImmediate()){
                if($this->getCancelService()){
                    $message = 'We have suspended domain and terminated service';
                }else {
                    $message = 'We suspended domain';
                }                   
            }else {
               if($this->getCancelService()){
                   $message = 'we have stoped renewing domain and service';
               }else {
                   $message = 'We have stoped renewing domain';
               }
            }
        }else {
           
            if($this->isImmediate()){
                $message = 'we have terminated service';
            }else {
                $message = 'we have stoped renewing service';
            }
        }
        return $message;
    }

    // function/method
    public function isCancelAvailable(){

        return count($this->domainTransitions) > 0 || count($this->serviceTransitions) > 0;
    }

    // function/method
    public function isImplicit($immediate = null){

        foreach ($this->domainTransitions as $domainTransition){ 
            if($immediate !== null && self::isStatusImmeadiate($domainTransition->getName()) !== $immediate ){
                
                continue;
            }
            if($domainTransition->getBoth()){
                return false;
            }else {
                foreach ($this->serviceTransitions as $serviceTransition){
                    if(self::isStatusImmeadiate($serviceTransition->getName()) == self::isStatusImmeadiate($domainTransition->getName())){
                        return true;
                    }
                }
            }           
        }
        return false;
    }

    // getter and setter for 6 checkboxes
    public function getNoNeed() {
        return $this->noNeed;
    }
    public function setNoNeed($noNeed){
        $this->noNeed = $noNeed;
    }
    
    public function getQuality(){
        return $this->quality;
    }
    public function setQuality($quality){
        $this->quality = $quality;
    }
    
    public function getSupport(){
        return $this->support;
    }
    public function setSupport($support){
        $this->support = $support;
    }
    
    public function getNewProvider() {
        return $this->newProvider;
    }
    public function setNewProvider($newProvider) {
        $this->newProvider = $newProvider;
    }
    
    public function getPrice() {
        return $this->price;
    }
    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function getCutCosts() {
        return $this->cutCosts;
    }
    public function setCutCosts($cutCosts) {
        $this->cutCosts = $cutCosts;
    }
    
    public function getConvinceStay() {
        return $this->convinceStay;
    }
    public function setConvinceStay($convinceStay) {
        $this->convinceStay = $convinceStay;
    }
    
    public function getHashToken() {
        return $this->hashToken;
    }
    
    public function getCancellationComment() {
        
        $comment = [];
        
        if($this->noNeed){
            $comment[] = 'noNeed';
        }
        if($this->quality){
            $comment[] = 'quality';
        }
        if($this->support){
            $comment[] = 'support';
        }
        if($this->newProvider){
            $comment[] = 'newProvider';
        }
        if($this->price){
            $comment[] = 'price';
        }
        if($this->cutCosts){
            $comment[] = 'cutCosts';
        }
        if($this->convinceStay){
            $comment[] = $this->convinceStay;
        }
        if($this->hashToken){
            $comment[] = $this->hashToken;
        }
       
        return json_encode($comment);
    }
    // function/method
    private function areDomainAndServiceInCompatibileStates() {
        
        $domain_normal_exist = false;
        $domain_immediate_exist = false;
        $service_normal_exist = false;
        $service_immediate_exist = false;
        
        foreach ($this->domainTransitions as $transition) {
            // ! is the same as == false
            if(self::isStatusImmeadiate($transition->getName())){
                $domain_immediate_exist = true;
            }else {
                $domain_normal_exist = true;
            }          
        }       
        foreach ($this->serviceTransitions as $transition) {
            if(self::isStatusImmeadiate($transition->getName())){
                $service_immediate_exist = true;
            }else {
                $service_normal_exist = true;
            }         
        } 
       
        return count($this->domainTransitions) == 0 || count($this->serviceTransitions) == 0 || ($domain_normal_exist == $service_normal_exist && $domain_immediate_exist == $service_immediate_exist);
    }
}