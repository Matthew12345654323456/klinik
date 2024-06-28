<?php
class Patient
{
    private $conn;

    public function __construct($host, $username, $password, $database)
    {
        // Establish database connection
        $this->conn = new mysqli($host, $username, $password, $database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function createPatient($id_user, $asuransi)
    {
        $query = "INSERT INTO patients VALUES('','$id_user','$asuransi')";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePatient($id_user, $asuransi)
    {
        $query = "UPDATE patients SET Asuransi = '$asuransi' WHERE ID_user = '$id_user'";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getPatient($id)
    {
        $query = "SELECT * FROM patients WHERE ID_user = '$id'";
        $result = $this->conn->query($query);

        // Fetch all patients
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        // Return the array of patients
        return $users;
    }
}
