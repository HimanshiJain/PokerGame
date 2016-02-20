<?php
require_once("config.php");

class MySQLDatabase {

	private $connection;
	public $last_query;
	private $real_escape_string;
	private $magic_quotes_active;
	public $stmt;
	
	function __construct() {
		$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string = function_exists( "mysql_real_escape_string" );
	}
	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (!$this->connection) {
			die("Database connection failed: ".mysqli_error());
		} 
	}

	public function close_connection() {
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	
	public function insert_user( $username){
		$this->prepare("INSERT INTO user(user_name) VALUES ( ?)");
		mysqli_stmt_bind_param($this->stmt, 's', $username);
		$this->execute();
		$last_id = mysqli_insert_id($this->connection);
		$this->close_connection();
		return $last_id;
	}
	
	public function create_player($user_id){
		$ids=array();
		$this->prepare("INSERT INTO player(user_id,position,player_status) VALUES ( ?,1,1)");
		mysqli_stmt_bind_param($this->stmt, 's', $user_id);
		$this->execute();
		$ids["player_id"] = mysqli_insert_id($this->connection);
		$player_id=$ids["player_id"];
		$no_of_players=1;
		$this->prepare("INSERT INTO cardtable(p1_id,no_of_players) VALUES ( ?,?)");
		mysqli_stmt_bind_param($this->stmt, 'ii', $player_id,$no_of_players);
		$this->execute();
		$ids["cardtable_id"] = mysqli_insert_id($this->connection);
		$cardtable_id=$ids["cardtable_id"];
		$sql = "UPDATE player SET  cardtable_id='$cardtable_id' WHERE player_id='$player_id'";

		if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			//echo "Error updating record: " . $this->connection->error;
		}
		$this->close_connection();
		return $ids;
	}
	
	public function add_player($user_id,$cardtable_id,$no_of_players){
		$this->prepare("INSERT INTO player(user_id,cardtable_id,position,player_status) VALUES ( ?,?,?,1)");
		//echo "prepared";
		$no_of_players++;
		mysqli_stmt_bind_param($this->stmt, 'iii', $user_id,$cardtable_id,$no_of_players);
		//echo "binded";
		$result=$this->execute();
		//echo "executed";
		$player_id= mysqli_insert_id($this->connection);
		//echo $player_id;
		if(!$result){
			//echo "not inserted player";
		}
		
		$column="p".$no_of_players."_id";
		//echo $column;
		$sql = "UPDATE cardtable SET  ".$column."='$player_id',no_of_players='$no_of_players' WHERE cardtable_id='$cardtable_id'";

		if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			//echo "Error updating record: " . $this->connection->error;
		}

		$this->connection->close();
		return $player_id;
	} 
	
	
	
	public function query($sql) {
		
		$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	public function escape_value( $value ) {
		if( $this->real_escape_string ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysqli_real_escape_string($this->connection, $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}

	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}

	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}

	public function insert_id() {
		return mysqli_insert_id($this->connection);
	}
	
	public function prepare($str){
		$this->stmt=mysqli_prepare($this->connection,$str);
	}
	
	public function execute(){
		 mysqli_stmt_execute($this->stmt);
	}

	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}

	private function confirm_query($result) {
		if (!$result) {
			$output = "Database query failed: ".mysqli_error($this->connection);
			$output .= "last sql query ".$this->last_query;
			die( $output );
		}
	}
}
$database = new MySQLDatabase();
$db =& $database;

?>