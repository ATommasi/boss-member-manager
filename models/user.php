<?php

class user{
    private $_sessionid = NULL;
    private $loggedin = false;
    
    private $c;
    
    private $sessionData = array();
    
    function __construct ($c) {
        #// pull out objects we need from the container
        $this->c = $c;
    }
 
 
    public function __get($name) {
        if (array_key_exists($name, $this->sessionData)) {
            return $this->sessionData[$name];
        }

        
    }
 
 
    private function isValidSession($ID) {
        
        if (empty($ID)) return false;
        
        $this->sessionData = $this->c["enjin"]->execute("User.checkSession", [$ID]);

        return ($this->sessionData["hasIdentity"] == "1" ? true : false);
        
    }

    public function isLoggedIn() {
        if ($this->setSession($_COOKIE["sessionID"]))
            return true;
        
        return $this->loggedin;
    }

    private function setSession($ID) {
        if (!empty($ID) && $this->isValidSession($ID)) {
            $this->loggedin = true;
            $this->username = $this->sessionData["username"];
            setcookie("sessionID", $ID);
            $this->_sessionid = $ID;
            return true;
        } else {
            $this->DestroySession();
  
            return false;
        }
    }

    private function DestroySession() {
        setcookie("sessionID", '', time()-3600 ); #-- delete cookie
        $_COOKIE["sessionID"] = "";
        $this->loggedin = false;
        $this->_sessionid = NULL;
        $this->sessionData = array();

    }
    
 
    
   function Logout() {
        $this->DestroySession();
    }

    
    function Login($username="", $password="") {
        #// check for cookie first and use it
        if ($this->isLoggedIn())
            return true;
               
        
        try {
            $login_result = $this->c["enjin"]->execute('User.login', array("email"=>$username, "password"=>$password));

            if ($this->setSession($login_result['session_id'])) {
                return true;
            } else {
                return false;
            }
            #echo "<pre>"; print_r($login_result);

        } catch (Exception $e) {
            #echo "<pre>"; print_r($e);
            $this->c["Messages"]->add("Invalid Login", "danger");
            return false;
        }
        
    }    
}
