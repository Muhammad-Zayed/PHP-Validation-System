<?php

class Validator
{
    protected $errorHandler;
    protected $db;
    protected $data ;
    protected $rules = [
        'required','minlength' , 'maxlength' , 'email' , 'alphanumeric' , 'match' ,'unique'
    ];
    public $messages = [
        'required'  => 'The :field Field Is Required',
        'minlength' => 'The :field Field Most Be At Least :satisifer character',
        'maxlength' => 'The :field Field Most Be At Most :satisifer character',
        'email'     => 'This Is Not Valid Email',
        'alphanumeric' => 'The :field Most not Contain Any Special Characters',
        'match'     => 'The :field Field Must Match :satisifer Field',
        'unique'     => 'This :field Is Already Used'
    ];
    
    public function __construct(Database $db ,  ErrorHandler $errorHandler)
    {
        $this->db = $db ; 
        $this->errorHandler = $errorHandler;
    }

    public function check($data , $rules)
    {
        $this->data = $data;

        foreach($data as $item => $value){
            if( array_key_exists($item , $rules) ) {

                $this->validate($item , $value , $rules[$item]);
            }
        }
        return $this;
    }

    public function fails()
    {
        return $this->errorHandler->hasErrors();
    }

    public function errors()
    {
        return $this->errorHandler;
    }

    protected function validate($field , $value , $rules)
    {
        foreach($rules as $rule => $satisifer){
            if (in_array($rule , $this->rules)) {
                if (!call_user_func_array([$this , $rule], [$field , $value , $satisifer])) 
                {
                    $this->errorHandler->addError( 
                        str_replace(
                        [':field', ':satisifer'],
                        [$field , $satisifer],
                        $this->messages[$rule])

                        ,$field);     
                }
            }
        }
    }
    protected function required ($field , $value , $satisifer)
    {
        
        return !empty(trim($value));
    }
    protected function minlength ($field , $value , $satisifer)
    {
        return  mb_strlen($value) >= $satisifer ;
    }

    protected function maxlength ($field , $value , $satisifer)
    {
        return  mb_strlen($value) <= $satisifer ;
    }

    protected function email ($field , $value , $satisifer)
    {
        return filter_var($value , FILTER_VALIDATE_EMAIL) ;
    }

    protected function alphanumeric ($field , $value , $satisifer)
    {
        return ctype_alnum($value);
    }

    protected function match ($field , $value , $satisifer)
    {   
        return $value === $this->data[$satisifer];
    }

    protected function unique ($field , $value , $satisifer)
    {   
        return !$this->db->table($satisifer)->exists($field ,$value);
    }
}