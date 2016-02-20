<?php

require_once("config.php");

class cardTableDatabase{
	public $engaged=false;
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
	public function set_cardtable_id($cardtable_id){
		$this->cardtable_id=$cardtable_id;
	}
	
	public function getRound(){
		$result=$this->query("Select current_round from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0])
			return $fetched[0];
	}
	
	public function checkRoundCompletion(){
		$ps=array();
		$equal=true;
		for($i=1;$i<=4;++$i){
		$result=$this->query("Select c.p1_id,p.last_raise from cardtable as c, player as p where c.cardtable_id=p.cardtable_id and c.cardtable_id='$this->cardtable_id'  and c.p1_id=p.player_id");
		$fetched=$this->fetch_array($result);
		$ps[$i]=$fetched[0];
		if($i>1 && $ps[$i-1]!=$ps[$i])
			return false;
		}
		$round=$this->getRound()+1;
		$sql = "UPDATE cardtable SET  current_round='$round'WHERE cardtable_id='$this->cardtable_id'";

		if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			//echo "Error updating record: " . $this->connection->error;
		}

		$this->connection->close();
		return true;
		
	}
	public function istable_complete(){
		$result=$this->query("Select no_of_players from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0]<4)
			return false;
		else return true;
	}
	
	public function getBigBlind(){
		$result=$this->query("Select current_bigdeal from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0])
			return $fetched[0];
	}
	
	public function istable_engaged(){
		
	}
	
	public function set_engaged(){
		$this->engaged=true;
	}
	
	public function getOtherPlayersStatus($cardtable_id,$position){
		
	}
	
	public function getPlayers($player_id){
		$result=$this->query("Select cardtable_id,no_of_players from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched['no_of_players']<4)
			return $fetched['no_of_players'];
	}
	
	public function getPosition($player_id){
		$result=$this->query("Select position from player where player_id='$player_id'");
		$fetched=$this->fetch_array($result);
		if($fetched[0]!=null)
			return $fetched[0];
	}
	
	public function getTurn(){
		$result=$this->query("Select cardtable_id,turn from cardtable where cardtable_id='$this->cardtable_id'");
		$fetched=$this->fetch_array($result);
		if($fetched['turn']!=null)
			return $fetched['turn'];
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
$database_card = new cardTableDatabase();
$db_card =$database_card;
?>