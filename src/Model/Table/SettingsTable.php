<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SettingsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

    }
    public function validationDefault(Validator $validator)
    {


        $validator
            ->notEmpty('module_name', 'Module name cannot be blank')
            ->notEmpty('value', 'Please define value');



       /*  $validator
            ->add('value','custom',[
                'rule'=>  function($value, $context){
                    $error = 1;
                    if($context['data']['type'] == 'limit'){
                      if (is_numeric($value))
                        {
                            $error = 0;
                        }
                    }else if($context['data']['type'] == 'int'){
                      if(is_int($value) && (preg_match('/^-?[0-9]+$/', $value)))
                      {
                          $error = 0;
                      }
                    }
                    else if($context['data']['type'] == 'percentage') {
                        if ((preg_match('/^-?[0-9]+$/', $value)) && ($value >= 0) && ($value<=100)) {
                            $error = 0;
                        }

                    }
                    else
                    {
                        $error = 0;
                    }
                    if($error == 1)
                    {
                        return false;
                    }
                    return true;

                },
                'message'=>'Incorrect value',
            ]); */


        return $validator;


    }



}
?>
