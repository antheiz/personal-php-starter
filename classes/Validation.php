<?php

class Validation
{

    private $_passed = false,
    $_errors = array();

    // validate input by user
    public function check($items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                switch ($rule) {
                    case 'required':
                        if (trim($_POST[$item]) == false && $rule_value == true) {
                            $this->addError("$item is required.");
                        }
                        break;
                    case 'min':
                        if (strlen($_POST[$item]) < $rule_value) {
                            $this->addError(" $item must have a minimal $rule_value character ");
                        }
                        break;
                    case 'max':
                        if (strlen($_POST[$item]) > $rule_value) {
                            $this->addError(" $item must have a maximum $rule_value character ");
                        }
                        break;
                    case 'match':
                        if ($_POST[$item] != $_POST[$rule_value]) {
                            $this->addError("$item dot not match with $rule_value ");
                        }
                        break;

                    default:
                        break;
                }
            }
        } // endforeach

        if (empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }

    // Add information error for user
    private function addError($error)
    {
        $this->_errors[] = $error;
    }

    // For show up the error messages
    public function errors()
    {
        return $this->_errors;
    }

    // Validation by user is passed
    public function passed()
    {
        return $this->_passed;
    }

}