<?php
    class Memory
    {
        private $id;
        private $description;
        private $place;
        private $date;
        private $picture;

        public function __get($ivar) {
            return $this->$ivar;
        }

        public function __set($ivar, $description) {
            $this->$ivar = $description;


        }

        public function __toString() {
            $format = "<hr/>Memory ID: %s<br/>Place: %s<br/><hr/>";

            return sprintf($format, $this->__get('id'), $this->__get('place'));
        }
    }
 ?>
