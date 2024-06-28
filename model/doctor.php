<?php
class Doctor
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

    public function createDoctor($id_user, $spesialis)
    {
        $query = "INSERT INTO doctors VALUES('','$id_user','$spesialis')";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDoctor($id_user, $spesialis)
    {
        $query = "UPDATE doctors SET Spesialis = '$spesialis' WHERE ID_user = '$id_user'";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getDoctor($id)
    {
        $query = "SELECT * FROM doctors WHERE ID_user = '$id'";
        $result = $this->conn->query($query);

        // Fetch all patients
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        // Return the array of patients
        return $users;
    }

    public function jadwalPatient()
    {
        $query = "SELECT patients_doctors.ID_PatientsDoctor, patients_doctors.ID_patient, patients_doctors.ID_doctor, patients_doctors.Visit_date, patients_doctors.is_finished, patients_doctors.prescription, patients_doctors.consultation, patients_doctors.nama_pasien FROM patients_doctors INNER JOIN doctors ON patients_doctors.ID_doctor = doctors.ID_doctor INNER JOIN patients ON patients_doctors.ID_patient = patients.ID_patient";

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
