<?php

class Database
{

    private static $INSTANCE = null;
    private $mysqli,
    $HOST = "HOST",
    $USER = "USER",
    $PASS = "PASSWORD",
    $DATABASE = "DATABASE";

    public function __construct()
    {
        $this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASS, $this->DATABASE);

        if (mysqli_connect_error()) {
            Redirect::to("500");
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$INSTANCE)) {
            self::$INSTANCE = new Database();
        }

        return self::$INSTANCE;
    }

    // Insert data to Database
    public function insert($table, $fields = array())
    {

        // Get Colum from $field and separate with comma
        $column = implode(", ", array_keys($fields));

        // Get Value
        $valueArrays = array();
        $i = 0;
        foreach ($fields as $key => $values) {

            // If type from $values = int, then remove '' from this.
            if (is_int($values)) {
                $valueArrays[$i] = $this->escape($values);
            } else {
                $valueArrays[$i] = "'" . $this->escape($values) . "'";
            }

            $i++;
        }

        // Add comma to the Value
        $values = implode(", ", $valueArrays);

        // Insert data into database
        $query = "INSERT INTO $table ($column) VALUES ($values)";

        return $this->run_query($query, "Failed to make insert to database");
    }

    // Update data in database
    public function update($table, $fields, $column, $value)
    {

        $valueArrays = array();
        $i = 0;

        foreach ($fields as $key => $values) {
            // Add equals to value with integer type 
            if (is_int($values)) {
                $valueArrays[$i] = $key . "=" . $this->escape($values);
            }
            else {
                $valueArrays[$i] = $key . "='" . $this->escape($values) . "'";
            }

            $i++;
        }

        $values = implode(", ", $valueArrays);

        // If type from value is integer, then update the data without '' 
        if (is_int($value)) {
            $query = "UPDATE $table SET $values WHERE $column = $value";
        }
        else {
            $query = "UPDATE $table SET $values WHERE $column = '$value'";
        }

        return $this->run_query($query, "Failed to make update to database");
    }

    // Delete data in Database
    public function delete($table, $column, $value)
    {

        // If type from value is integer, then update the data without ''
        if (is_int($value)) {
            $query = "DELETE FROM $table WHERE $column = $value";
        }
        else {
            $query = "DELETE FROM $table WHERE $column = '$value'";
        }

        return $this->run_query($query, "Failed to delete request to database");
    }

    // Function for send query to Database
    public function run_query($query, $message)
    {
        if ($this->mysqli->query($query))
            return true;
        else
            echo($message);
    }

    //Separate character sql, for secure sql query.
    public function escape($name)
    {
        return $this->mysqli->real_escape_string(stripslashes(htmlspecialchars($name)));
    }



    // Get info from database with specific paramater
    public function get_info($fields, $table, $column = '', $value = '')
    {

        if (!is_int($value)) {
            $value = "'" . $value . "'";
        }

        if ($table != '') {

            $valueArrays = array();
            $i = 0;
            foreach ($fields as $key => $values) {
                $valueArrays[$i] = $key;
                $i++;
            }
            $row = implode(", ", $valueArrays);

            $query = "SELECT $row FROM $table WHERE $column = $value";

            $result = $this->mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                return $row;
            }

        }
       
    }

}