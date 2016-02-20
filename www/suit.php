<?php
require_once("config.php");
class suit{
	protected $cardValues = [
        1 => 'Ace',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Jack',
        12 => 'Queen',
        13 => 'King',
        14 => 'Ace'
    ];

    protected $cardSuits = [
        'Club',
        'Spade',
        'Diamond',
        'Heart'
    ];
	public $msuit=array();
	
	private $connection;
	public $last_query;
	private $real_escape_string;
	private $magic_quotes_active;
	public $stmt;
	
	function __construct() {
		$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string = function_exists( "mysql_real_escape_string" );
		$this->createDeck();
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
	
	
	public function createDeck(){
		
		for($i=0;$i<13;++$i){
			foreach($this->cardSuits as $key=>$s){
				$this->msuit[]=$s."_".($i+1);
			}
		}
	}
	
	/**
     * Returns the number of cards in this deck
     * 
     * @return integer
     */
    public function cardCount()
    {
        return $this->msuit->count();
    }

    /**
     * Shuffles the deck
     * 
     * @return void
     */
    
	
	public function getCards(){
		shuffle($this->msuit);
		$cards=array();
		for($i=0;$i<13;$i++){
			$cards[]=$this->msuit["$i"];
		}
		return $cards;
	}

	public function saveCommunityCards($cardtable_id,$c1,$c2,$c3,$c4,$c5){
		$sql = "UPDATE cardtable SET  card1='$c1',card2='$c2',card3='$c3',card4='$c4',card4='$c4' WHERE cardtable_id='$cardtable_id'";

		if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			//echo "Error updating record: " . $this->connection->error;
		}

		$this->connection->close();
	}
	
	public function savePlayerCards($player_id,$c1,$c2){
		$sql = "UPDATE player SET  card1='$c1',card2='$c2' WHERE player_id='$player_id'";

		if ($this->connection->query($sql) === TRUE) {
			//echo "Record updated successfully";
		} else {
			//echo "Error updating record: " . $this->connection->error;
		}

		$this->connection->close();
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
$database_suit = new suit();
$db_suit =& $database_suit;

?>