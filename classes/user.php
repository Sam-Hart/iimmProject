<?php
class User {
	private $_db;
	public $id;
	public $user_name;
	public $user_favorite_film;
	public $user_comment;
	public $user_rated_films = array();
	
	
	public function User($database) {
		$this->_db = $database;
		if(isset($_SESSION['user_id'])) {
			$this->id = $_SESSION['user_id'];
		}
	}
	
	public function Login($username, $password) {
		
		$login_sql = "CALL prc_user_login(?, ?, @result, @userId)";
		$login_statement = $this->_db->prepare($login_sql);
		$data = array($username, $password);
		$login_statement->execute($data);
		$output = $this->_db->query("SELECT @result, @userId")->fetch(PDO::FETCH_ASSOC);
		if($output['@result'] == '0' AND isset($output['@userId'])) {
			$_SESSION['user_id'] = $output['@userId'];
			$this->_id = $output['@userId'];
		}
	}
	
	public function Logout() {
		unset($_SESSION['user_id']);
	}
	
	public function GetUserData() {
		
		if(isset($this->id)) {
			
			
			$user_sql = "SELECT a.user_name, a.user_favorite_film, a.user_comment, b.film_name FROM reg_user a, film b WHERE user_id = :id AND a.user_favorite_film = b.film_id";
			$user_statement = $this->_db->prepare($user_sql);
			
			$user_statement->execute(array(':id' => $this->id));
			if($row = $user_statement->fetch()){
				$this->user_name = ucfirst($row['user_name']);
				$this->user_comment = $row['user_comment'];
				$this->user_favorite_film = $row['film_name'];
				
			}	
			$ratings_sql = "SELECT a.film_name, b.rating_text FROM film a, rating b, user_rated_films c WHERE a.film_id = c.film_id AND b.rating_id = c.rating_id AND c.user_id = :id";
			$ratings_statement = $this->_db->prepare($ratings_sql);
			$ratings_statement->execute(array(':id' => $this->id));
			
			while($row = $ratings_statement->fetch()) {
				$entry = array();
				$entry['film_name'] = $row['film_name'];
				$entry['rating_text'] = $row['rating_text'];
				$this->user_rated_films[] = $entry;	
			}
		}
	}
}

?>