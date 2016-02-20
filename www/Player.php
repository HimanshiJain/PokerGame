<?php 

require_once("config.php");
class Player {
	public $name;
	public $player_id;
	public $cardtable_id;
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
		//echo "open connection";
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
	
	public function set_current_player($player_id,$cardtable_id){
		$this->player_id=$player_id;
		$this->cardtable_id=$cardtable_id;
	}

	public function getPlayerStatus(){
		$result=$this->query("Select player_status from player where player_id='$this->player_id'");
		$fetched=$this->fetch_array($result);
		//echo $fetched[0];
		//if($fetched[0])
			return $fetched[0];
	}
	
	public function getRound(){
		$result=$this->query("Select current_round from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0])
			return $fetched[0];
	}
	
	public function getTurn(){
		$result=$this->query("Select turn from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		//echo $fetched[0];
		//if($fetched[0])
			return $fetched[0];
	}
	public function makeMove($move,$raise){
		$ok=true;
		$r=0;
		$sql="";
		//echo $move;
		if($move=="Raise"){
			$sql = "UPDATE player SET  last_play='$move',last_raise='$raise' WHERE player_id='$this->player_id'";
			
				$r=1;
			//echo "raising";
			

		}else
			if($move=="Fold"){
				$sql = "UPDATE player SET  last_play='$move',player_status=0 WHERE player_id='$this->player_id'";
			}
			else{
				$sql = "UPDATE player SET  last_play='$move' WHERE player_id='$this->player_id'";	
			}
		if ($this->connection->query($sql) === TRUE) {
			//echo "ok";
			//echo "Record updated successfully";
		} else {
			$ok=false;
			//echo "Error updating record: " . $this->connection->error;
		}
		
		if($r==1){
			
			if($this->raise_greater($raise)){
				$sql2="UPDATE cardtable SET  current_bigdeal='$raise' WHERE cardtable_id='$this->cardtable_id'";
				if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			$ok=false;
			//echo "Error updating record: " . $this->connection->error;
		}
			}
			
			
		}
		return $ok;
		//$this->close_connection();
	}
	
	public function raise_greater($raise){
	$result=$this->query("Select current_bigdeal from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0]<$raise)
			return true;	
	}
	
	public function update_turn(){
		$turn=$this->getTurn();
		$result=$this->query("Select position from player where cardtable_id='$this->cardtable_id' and player_status=1");
		$fetched=$this->fetch_array($result);
		//echo $fetched[0];
		if($fetched[0]){
			$sql2="UPDATE cardtable SET  turn='$fetched[0]' WHERE cardtable_id='$this->cardtable_id'";
				if ($this->connection->query($sql2) === TRUE) {
				//echo "Record updated successfully";
				//echo "updated";
			} else {
								//echo "not updated";
				//echo "Error updating record: " . $this->connection->error;
			}
		}
	}
	/**
	 * Returns the name of this player
	 * 
	 * @return string
	 */
	 /*public function create_player(){
		 
		 
		 $this->create_table();//based on the condition
	 }*/
	
	

	/**
	 * Sets the player's current hand
	 * 
	 * @param Sixteenstudio\Poker\Contracts\Hand $hand
	 */
	

	
	
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
$database_player = new Player();
$db_player =& $database_player;

?>