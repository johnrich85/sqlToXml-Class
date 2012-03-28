<?php

/*
 * Classes used to query the database & return results.
 * Abstract class used to hold duplicate data for two child classes.
 */

require_once( 'db_conn.class.php' );

// abstract class used as base class to store functionality/data required by both children.
abstract class get_RecordSet {
    
    //properties.
    protected $db;
    protected $stmt;

    //constructor
    function __construct()
    {
        //store db connection in class property.
        $this->db = dbConn::getConnection();
    }
    
    /**
    * Methods
    */
    
    //standard function to retrieve database info using PDO.
    public function getRecordSet ($sql)
    {
        $this->stmt = $this->db->query($sql);
        return $this->stmt;
    }
}

// class to retrieve data in normal format, can be expanded as required in
// future.
class PDORecordSet extends get_RecordSet {
    public function getRecordSet($sql)
    {
        return parent::getRecordSet($sql);
    }
}

//class to return record set in xml format
class XMLRecordSet extends get_RecordSet {
	
    
    //overiding base class. Two parameters, first is sql statement, second is option and is
    //the name to be used for xml.
    public function getRecordSet($sql, $elementName = 'element')
    {
		header("Content-Type: text/xml");
		
        try {
            //execute parent function to run query & retrieve data.
            $stmt = parent::getRecordSet($sql);

            //var to store values
            $returnValue = "";
            //xml header
            $returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
            //xml parent node, or wrapper to be named according to 'element' parameter
            $returnValue .= "<{$elementName}s>\n"; 
            // fetch each record as an associative array
            while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //element name
                $returnValue .= "\t<$elementName>\n";
                // iterate through each field in the associative array,
                foreach ($book as $key => $value) {
                    // Store values as xml elements, using key as element name.
                    $returnValue .= "\t\t<$key>" . htmlspecialchars($value) ."</$key>\n";
                }
                //close each single element.
                $returnValue .= "\t</$elementName>\n";
            }
            //close xml wrapper element.
            $returnValue .= "</{$elementName}s>\n";

            //return data in xml format.
            return $returnValue;

        }
	catch (PDOException $e) {
            //catch and display error
            echo "Connection Error: " . $e->getMessage();
	}
    }
}

?>