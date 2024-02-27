<?
namespace App\Model\Table;
use Cake\ORM\Table;

class z1_userTable extends Table{

	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
	}
	
	
		public function validationDefault(Validator $validator)
    {
		/*
		$validator
            ->notEmpty('kind', 'Missing board id')
            ->notEmpty('subject', 'Missing subject')
            ->notEmpty('contents', 'Missing contents');
		$validator->add('subject', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid subject'
		]);


		


		
		 $validator
			->notEmpty('total_token', 'Missing Total Token')
			->notEmpty('sold_token', 'Missing Sold Token')
			->notEmpty('token_value', 'Missing Token value');
		$validator->add('total_token', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid Total Token'
		]);
		$validator->add('sold_token', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid Sold Token'
		]);



		$validator
			->add('subject','custom',[
				'rule'=>  function($value, $context){
						
					if(preg_match("/^[#<>]+$/", $value)) return true;
					return false;
				},
				'message'=>'Invalid subject.',
			])
			->notEmpty('subject', 'Missing Subject');



		*/
		return $validator; 
		
	}
}


?>