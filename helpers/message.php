<?php
class Messages {
    #-- messages types:
    #-- success, info, warning, danger, debug

    private $messages = array();
    private $debug = array();


    function getAll() {
        return $this->messages;
    }

    function get($type) {
        return $this->messages[$type];
    }

    function getTypes() {
        return keys($this->messages);
    }

    function add ($msg, $type="info") {
        $this->messages[$type][] = $msg;

    }

    function addDebug($msg) {
        $this->debug[] = $msg;
    }

    function getDebug() {
        return $this->debug;
    }
}

?>