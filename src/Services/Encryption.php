<?php

/**
 * Description of Encryption
 *
 * @author milos
 */

class Encryption {
    //put your code here
    
    private $cancellationSecret;
    
    public function __construct($config) {
        
        $this->cancellationSecret = $config['XXXXXX'];
    }
    public function verifyCancellationHash($domainName, $hash){
       if($domainName !== null && $domainName !== '' && $hash !== null && $hash !== '' ){
           $hashHmac = hash_hmac("XXX", $domainName, $this->XXXXXX);
       
            return $hashHmac === $hash;
       }else {
           return false;
       }   
    }
}