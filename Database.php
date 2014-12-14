<?php

class MysqlDatabaseConnector
{
	private $_host;
	private $_database;
	private $_user;
	private $_password;
	
	function __construct($host, $database, $user, $password)
	{
		$this->_host = $host;
		$this->_database = $database;
		$this->_user = $user;
		$this->_password = $password;
	}
	
	public function createConnection()
	{
		$con = "";
		try {
			$con=mysqli_connect($this->_host,$this->_user,$this->_password,$this->_database);
			if (mysqli_connect_errno()) {
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  return 0;
			}
			return $con;
		}
		catch(Exception $e) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return 0;
		}
	}
}

class DataTable
{
	var $columns;
	var $rows;
	function ToHtml()
	{
		$output = '<table class=\'query-result-table\'><tr class=\'header-row\'>';
		$ceven=true;
		$reven=true;
		$cfirst=true;
		$clast = false;
		for($i=0; $i<count($this->columns); $i++)
		{
			$clast = $i==(count($this->columns)-1);
			$output .= "<th class='".($cfirst?" first":"").($clast?" last":"").($ceven?" even":"")."'>" . $this->columns[$i]->name . '</th>';
			$ceven = !$ceven;
			$cfirst=false;
		}
		$output .= '</tr>';
		$rfirst = true;
		for($i=0; $i<count($this->rows); $i++)
		{
			$rlast = $i==(count($this->rows)-1);
			$output .= "<tr class='data-row".($rfirst?" first":"").($rlast?" last":"").($reven?" even":"")."'>";
			$reven = !$reven;
			$rfirst = false;
			$cfirst=true;
			$ceven = true;
			for($j=0; $j<count($this->columns); $j++)
			{
				$clast = $j==(count($this->columns)-1);
				$output .= "<td class='".($cfirst?" first":"").($clast?" last":"").($ceven?" even":"")."'>" . $this->rows[$i][$j] . "</td>";
				$ceven = !$ceven;
				$cfirst=false;
			}
			$output .= "</tr>";
		}
		$output .= "</table>";
		return $output;
	}
}

interface IDatabaseEngine
{
    public function __construct();

    public function initialize();

    public function setConnectionString($connectionString='');

    public function ExecuteDataTable($query);

    public function ExecuteDataTableN($query);

    public function ExecuteScalar($query);

    public function ExecuteScalarN($query);

    public function ExecuteNonQuery($query);

    public function ExecuteNonQueryN($query);

    public function ExecuteInsertUpdate($query);

    public function ExecuteInsertUpdateN($query);

    public function WhileReader($query, $consumer);
}

class MysqlDatabaseEngine implements IDatabaseEngine
{
    private $_connectionString = '';
    private $_connector = null;
    private $_isInitialized = false;

    public function setConnectionString($connectionString='')
    {
        $this->_connectionString = $connectionString;
    }

    public function __construct()
    {
    }

    public function initialize()
    {
        if(!$this->_isInitialized)
        {
            // parse connection string
            $data = array();
            $pairs = explode(",", $this->_connectionString);
            foreach($pairs as $pair)
            {
                list($name, $value) = explode("=", $pair, 2);
                $data[$name] = $value;
            }

            // make the global connection object
            $this->_connector = new MysqlDatabaseConnector($data["host"], $data["database"], $data["user"], $data["password"]);
            $this->_isInitialized = true;
        }
    }


    public function ExecuteDataTable($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $dt = null;
        $result = mysqli_query($con,$query)or die("Error: ".mysqli_error($con));

        $dt = $this->getDataTableFromResult($result);
        mysqli_free_result($result);

        mysqli_close($con);
        return $dt;
    }

    public function ExecuteDataTableN($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $dts = array();
        if (mysqli_multi_query($con, $query)) {
            $dti=0;
            do {
                if ($result = mysqli_store_result($con)) {
                    $dts[$dti] = $this->getDataTableFromResult($result);
                    mysqli_free_result($result);
                }
                $dti++;
            } while (mysqli_more_results($con) && mysqli_next_result($con));
        }

        mysqli_close($con);
        return $dts;
    }



    public function ExecuteScalar($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $result = mysqli_query($con,$query)or die("Error: ".mysqli_error($con));

        $dt = new DataTable();

        $found = false;
        $res = 0;
        if($row = mysqli_fetch_row($result))
        {
            $res = $row[0];
            $found = true;
        }
        mysqli_free_result($result);

        mysqli_close($con);
        if($found)
            return $res;
        else
            die("Error: Query found no results");
    }

    public function ExecuteScalarN($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $dts = array();
        if (mysqli_multi_query($con, $query)) {
            $dti=0;
            do {
                if ($result = mysqli_store_result($con)) {
                    if($row = mysqli_fetch_array($result))
                    {
                        $dts[$dti] = $row[0];
                        $dti++;
                    }
                    mysqli_free_result($result);
                }
            } while (mysqli_more_results($con) && mysqli_next_result($con));
        }

        mysqli_close($con);
        return $dts;
    }

    public function ExecuteNonQuery($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $result = mysqli_query($con,$query)or die("Error: ".mysqli_error($con));
        if ($result = mysqli_store_result($con))
            mysqli_free_result($result);
        $affectedRows = mysqli_affected_rows($con);

        mysqli_close($con);
        return $affectedRows;
    }

    public function ExecuteNonQueryN($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $dts = array();
        if (mysqli_multi_query($con, $query)) {
            $dti=0;
            do {
                if ($result = mysqli_store_result($con))
                    mysqli_free_result($result);
                $dts[$dti] = mysqli_affected_rows($con);
                $dti++;
            } while (mysqli_more_results($con) && mysqli_next_result($con));
        }

        mysqli_close($con);
        return $dts;
    }


    public function ExecuteInsertUpdate($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $result = mysqli_query($con,$query)or die("Error: ".mysqli_error($con));
        if ($result = mysqli_store_result($con))
            mysqli_free_result($result);
        $id = mysqli_insert_id($con);

        mysqli_close($con);
        return $id;
    }

    public function ExecuteInsertUpdateN($query)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 1);

        $dts = array();
        if (mysqli_multi_query($con, $query)) {
            $dti=0;
            do {
                if ($result = mysqli_store_result($con))
                    mysqli_free_result($result);
                $dts[$dti] = mysqli_insert_id($con);
                $dti++;
            } while (mysqli_more_results($con) && mysqli_next_result($con));
        }

        mysqli_close($con);
        return $dts;
    }

    public function WhileReader($query, $consumer)
    {
        $con = $this->_connector->createConnection();

        $this->insertParameters($query, $con, func_get_args(), 2);

        $i=0;
        try{
            $result = mysqli_query($con,$query)or die("Error: ".mysqli_error($con));
            while($row = mysqli_fetch_array($result)) {
                $consumer($row);
                $i++;
            }
        }catch(Exception $ex)        {
            mysqli_free_result($result);
            mysqli_close($con);
            throw $ex;
        }
        mysqli_free_result($result);
        mysqli_close($con);
        return $i>0;
    }

    private function getDataTableFromResult($result)
    {
        $dt = new DataTable();

        $i=0;
        $dt->columns = array();
        while($col = mysqli_fetch_field($result))
        {
            $dt->columns[$i] = $col;
            $i++;
        }

        $i=0;
        $dt->rows = array();
        while($row = mysqli_fetch_array($result)) {
            $dt->rows[$i] = $row;
            $i++;
        }

        return $dt;
    }

    private function insertParameters(&$query, $connection, $params, $paramOffset)
    {
        if(count($params) <= $paramOffset)
            return;
        $params = array_slice($params,$paramOffset);
        $queryParts = explode("?", " ".$query." ");
        $result = $queryParts[0];
        $j=0;
        $l = count($params);
        if($l==0)
        {
            $params = array("");
            $l=1;
        }
        for($i=1; $i<count($queryParts); $i++)
        {
            $p = $j>$l-1 ? $params[$l-1] : $params[$j];
            $j++;
            $result = $result.mysqli_real_escape_string($connection, $p).$queryParts[$i];
        }
        $query = $result;
    }

}
