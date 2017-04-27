<?php
define('LOCAL_DB_ADDRESS', 'localhost');
define('ONLINE_DB_ADDRESS', 'localhost');
date_default_timezone_set("America/New_York");

class DataConnection extends mysqli {
	private static $dataConnection = null;
	
	private function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);

        if (mysqli_connect_error()) {
            die(mysqli_connect_error());
        }
    }

	public static function getInstance() {
		if (is_null(self::$dataConnection)) 
			self::$dataConnection = new DataConnection(LOCAL_DB_ADDRESS, 'root', 'root', 'recipe_db');
		return self::$dataConnection;
	}
}

class Data {
	private $sql;
	private $rs;
	
	public $table;
	public $key;
	public $columns;
	
	public function init($items) {
		$this->table = $items['table'];
		$this->columns = $items['columns'];
		$this->key = $items['key'];
		$this->reset();
	}
	
	public function reset() {
		foreach ($this->columns as $objCol => $dbCol) {
			$this->$objCol = null;
		}
	}
	
	private function fetch() {
		//return mysql_fetch_assoc($this->rs);
		return $this->rs->fetch_assoc();
	}
	
	private function query() {
		$connection = DataConnection::getInstance();
		//echo $this->sql . "<hr>";
		//$this->rs = mysql_query($this->sql, $connection) or die(mysql_error());
		$this->rs = $connection->query($this->sql);
		return $connection->affected_rows;
	}
	
	public function load($v = null) {
		$key = $this->key;
		if (!$v) {
			$v = $this->$key;                                                                        //$this->$key;
		}
		$this->sql = "SELECT * FROM {$this->table} WHERE {$this->columns[$key]} = {$v}";
		//echo $this->sql;
		if ($this->query()) {
			$asResult = $this->fetch();
			foreach ($this->columns as $objCol => $dbCol) {
				$this->$objCol = $asResult[$dbCol];
			}
			return $this;
		} 
		return false;
	}
	
	public function find() {
		$key = $this->key;
		$where = " WHERE 1 = 1 ";
		$limit = " LIMIT 0, 50";
		foreach ($this->columns as $objCol => $dbCol) {
			if (!is_null($this->$objCol)) {
				$where .= " AND {$dbCol} = '{$this->$objCol}'";
			}
		}

		$this->sql = "SELECT * FROM {$this->table} {$where} {$limit}";
		$asResult = array();
		if ($this->query()) {
			while($a = $this->fetch()) {
				$o = clone $this;
				foreach ($o->columns as $objCol => $dbCol) {
					$o->$objCol = $a[$dbCol];
				}
				$asResult[] = $o;
			}
		}
		return $asResult;
	}
	
	public function cropString($s) {
		$a = array();
		$a = explode(";", $s);
		return $a;
	}

// insert, update data
	public function insert() {
		$insertConn = DataConnection::getInstance();
		$fields = "";
        $values = "";
		foreach ($this->columns as $objCol => $dbCol) {
			if (!is_null($this->$objCol)) {
				$fields .= $dbCol . ',';
				$values .= '"' .mysqli_real_escape_string($insertConn, stripslashes($this->$objCol)) . '",';
			}
		}
		$fields = rtrim($fields, ',');
		$values = rtrim($values, ',');
		$this->sql = "INSERT INTO ". $this->table ." ($fields) VALUES (" . $values . ")";
//var_dump($this->sql);
		if ($insertConn->query($this->sql))
			return mysqli_insert_id($insertConn);
		else return false;
		
	}
	
	//public function search($keywords) {
	//	$searchConn = DataConnection::getInstance();
	//	$sql = "SELECT * FROM " . $this->table . " WHERE ";
	//	if (!is_null($keywords)) {
	//		foreach($keywords as $key) {
	//			$sql .= " rname LIKE '%" . $key . "%'";
	//		}
	//	}
	//	$sql .= " LIMIT 0, 20";
	//	$searchConn->query($sql);
	//}
	
}

class Category extends Data {
    function __construct() {
		$items = array(
			'table' => 'category',
			'key' => 'id',
			'columns' => array(
				'id' => 'cid',
				'parentId' => 'pid',
				'name' => 'cname'
							   ));
		parent::init($items);
	}
	
	public function name() {
		$this->parentId = 1;
		return $this->find();
	}
	
	public function rep() {
		$r = new Recipe();
		$r->categoryId = $this->id;
		return $r->find();
	}
}

class User extends Data {
    function __construct() {
		$items = array(
					   'table' => 'user',
					   'key' => 'id',
					   'columns' => array(
										  'id' => 'uid',
										  'emailAddress' => 'email',
										  'userName' => 'uname',
										  'userPwd' => 'upassword',
										  'userPic' => 'upic',
										  'userInfo' => 'uinfo',
										  'regTime' => 'reg_time')
					   );
		parent::init($items);
	}
	
	public function rep() {
		$r = new Recipe();
		$r->userId = $this->id;
		return $r->find();
	}
	
	public function checkLogin() {
		$mark = true;

		//$email = mysqli_real_escape_string(stripslashes($email));
		if (isset($_SESSION['last_activity'])) {
			if ((time() - $_SESSION['last_activity'] > 18000)) {
				//var_dump($_SESSION);
				// last request was more than 30 minutes ago
				session_unset();     // unset $_SESSION variable for the run-time 
				session_destroy();   // destroy session data in storage
				$mark = false;
			}
			$_SESSION['last_activity'] = time(); 
		} else $mark = false;
		return $mark;
	}
	
	public function login($email, $pwd) {
		
		$this->emailAddress = $email;
		$this->userPwd = md5($pwd);
	
		if(!is_null($this->find())) {
			$u = $this->find()[0];
			$_SESSION["login_user_id"] = $u->id;
			$_SESSION["login_user_name"] = $u->userName;
			$_SESSION["last_activity"] = time();
			$_SESSION["history"] = array();

			header("Location: home.php");
			exit();
		} else {
			echo "Username or password is not correct!";
		}
	}
	
	public function logout($preUrl) {
		session_unset();     
		session_destroy();
		
		header("Location: ".$preUrl);
		exit();
	}
}




class Recipe extends Data {
    function __construct() {
		$items = array(
					   'table' => 'recipe',
					   'key' => 'id',
					   'columns' => array(
										  'id' => 'rid',
										  'name' => 'rname',
										  'reCalorie' => 'calorie',
										  'reIngredient' => 'ingredient',
										  'reInstruction' => 'instruction',
										  'mainPic' => 'main_pic',
										  'creatTime' => 'create_time',
										  'like' => 'likenumber',
										  'categoryId' => 'cid',
										  'userId' => 'uid'));
		parent::init($items);
	}
	
	function load($v = null) {
		parent::load($v);
		
		$this->Category = new Category();
		$this->Category->id = $this->categoryId;
		
		$this->User = new User();
		$this->User->id = $this->userId;
		
		return $this;
	}
	
	// need rewrite
	function newest() {
		$rs = $this->find();
		foreach ($rs as $key => $r) {
			$creatTime[$key] = $r->creatTime;
		}
		array_multisort($rs, SORT_DESC, $creatTime);
		//var_dump($rs);exit;
		return $rs;
	}
	
	function search($keywords) {
		$result = array();
		$rs = $this->newest();
		foreach($rs as $r) {
			$r->load();
			foreach($keywords as $k) {
				//var_dump($r->name);
				//echo "<br>";
				//var_dump($k);
				if (stripos($r->name, $k) !== false) {
					array_push($result, $r);
				}
			}
		}

		return $result;
	}
}

class Comment extends Data {
	function __construct() {
		$items = array(
					   'table' => 'comment',
					   'key' => 'id',
					   'columns' => array(
										  'id' => 'mid',
										  'repId' => 'rid',
										  'comUserId' => 'uid',
										  'comTime' => 'post_time',
										  'comText' => 'comment'));
		parent::init($items);
	}
	
	function allComments($rid) {
		$this->repId = $rid;
		return $this->find();
	}
	
	function load($v = null) {
		parent::load($v);
		
		$this->User = new User();
		$this->User->id = $this->comUserId;
		
	}	
}









?>