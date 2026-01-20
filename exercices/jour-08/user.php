<?php
class User
{
	public function __construct(
	
		private string $name,
		private string $email,
		private ?float $dateRegistration = null
	){
		$this->dateRegistration ??= time();
	}

	public function isNewMembre(): bool{
		return time() - $this->dateRegistration < strtotime('1 month');
	}
	}

$user = new User('John', 'johndoe@me.com', time() - 100);
$user2 = new User('Jane', 'janedoe@me.com', time() - 1000);
$user3 = new User('Joe', 'joe@me.com', time() - 1000000000000000000);
$users = [$user, $user2, $user3];

foreach($users as $user){
	if($user->isNewMembre()){
		echo 'New user!';
}else{
	echo 'Old user!';	
}
echo '<br>';
}