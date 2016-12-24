<?php

class Database extends PDO
{
private $DB_TYPE= 'mysql';
private $DB_HOST=DB_HOST;
private $DB_NAME= DB_NAME;
private $DB_USER=  DB_USER;
private $DB_PASS=  DB_PASS;
	
	public function __construct()
	{
		parent::__construct($this->DB_TYPE.':host='.  $this->DB_HOST.';dbname='.  $this->DB_NAME, $this->DB_USER, $this->DB_PASS);
		
	}
	
        /**
         * select
         * @param  $sql an SQL string
         * @param  array $array Parameter to bind
         * @return array mixed
         */
        public function select($sql,$array=array(),$fetchMode=PDO::FETCH_ASSOC) 
        {
            $stmt=  $this->prepare($sql);
            foreach ($array as $key => $value) {
                $stmt->bindValue("$key", $value);
            }
            $stmt->execute();
            return $stmt->fetchAll($fetchMode);
            
        }
        
	/**
	 * insert
	 * @param string $table A name of table to insert into
	 * @param string $data An associative array
	 */
	public function insert($table, $data)
	{
		ksort($data);
		
		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));
		
		$sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		return $sth->execute();
	}
	
	/**
	 * update
	 * @param string $table A name of table to insert into
	 * @param string $data An associative array
	 * @param string $where the WHERE query part
	 */
	public function update($table, $data, $where)
	{
		ksort($data);
		
		$fieldDetails = NULL;
		foreach($data as $key=> $value) {
			$fieldDetails .= "`$key`=:$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');
		
		$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
		
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
		
		return $sth->execute();
	}
        /**
         * delete
         * 
         * @param string $table
         * @param string $where
         * @param integer $limit
         * @return integer
         */
        public function delete($table,$where,$limit=1) 
         {
            return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
            
        }
	
}