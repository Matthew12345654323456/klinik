<?php
class User
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

    // Method to authenticate a user
    public function authenticate($email, $password)
    {
        $query = "SELECT * FROM users WHERE Email = '$email' AND Passwords = '$password'";
        $result = $this->conn->query($query);

        // Check if the query was successful
        if (!$result) {
            die("Error in query: " . $this->conn->error);
        }

        // Check if a user with the given credentials exists
        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }

    // Method to get user data by email
    public function getUserData($email)
    {
        $query = "SELECT * FROM users WHERE Email = '$email'";
        $result = $this->conn->query($query);

        // Fetch the result
        $userData = $result->fetch_assoc();

        // Return user data
        return $userData;
    }

    public function getAllUser()
    {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);

        // Fetch all patients
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        // Return the array of patients
        return $users;
    }

    public function getFetchUser($id)
    {
        $query = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, doctors.Spesialis FROM doctors INNER JOIN users ON doctors.ID_user = users.ID_user WHERE doctors.ID_user = '$id'";
        $result = $this->conn->query($query);

        // Fetch all patients
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function getJoinUser($role)
    {
        if ($role == "doctor") {
            $query = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, doctors.Spesialis FROM doctors INNER JOIN users ON doctors.ID_user = users.ID_user WHERE users.Roles = '$role'";
        } else if ($role == "patient") {
            $query = "SELECT users.ID_user, users.Name_user, users.Gender, users.Email, users.Phone, users.Address, users.Date_of_birth, users.Passwords, users.Roles, patients.Asuransi FROM patients INNER JOIN users ON patients.ID_user = users.ID_user WHERE users.Roles = '$role'";
        } else if ($role == "all") {
            $query = "SELECT * FROM users";
        }
        $result = $this->conn->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function updateUser($id, $name, $gender, $email, $phone, $address, $date, $password, $role)
    {
        $query = "UPDATE users SET Name_user = '$name', Gender = '$gender', Email = '$email', Phone = '$phone', Address = '$address', Date_of_birth = '$date', Passwords = '$password', Roles = '$role' WHERE ID_user = '$id'";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE ID_user='$id'";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function createUser($name, $gender, $email, $phone, $address, $date, $password, $role)
    {
        $query = "INSERT INTO users VALUES('','$name','$gender','$email','$phone','$address','$date','$password','$role')";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
