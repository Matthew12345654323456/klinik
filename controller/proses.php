<?php
session_start();

include_once '../model/user.php';
include_once '../model/doctor.php';
include_once '../model/patient.php';

function login($email, $password)
{
    $user = new User('localhost', 'root', '', 'klinik');

    $auth_user = $user->authenticate($email, $password);

    if ($auth_user) {
        // Get user data
        $data_user = $user->getUserData($email);

        return ["id" => $data_user['ID_user'], "nama" => $data_user['Name_user'], "role" => $data_user['Roles']];
    }
    return false; // Authentication failed
}

function read($roles)
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $userRole = $_SESSION['user']['role'];

    switch ($userRole) {
        case 'admin':
            $user = new User('localhost', 'root', '', 'klinik');
            $userData = $user->getJoinUser($roles);

            echo json_encode(["data" => $userData]);
            exit();
            break;
        default:
            echo json_encode(["message" => "invalid_role"]);
            exit();
    }
}

function fetch($id)
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $userRole = $_SESSION['user']['role'];

    switch ($userRole) {
        case 'admin':
            $user = new User('localhost', 'root', '', 'klinik');
            $userData = $user->getFetchUser($id);
            echo json_encode($userData);
            exit();
            break;
        default:
            echo json_encode(["message" => "invalid_role"]);
            exit();
    }
}

function create($akun)
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $name = $_POST['Name_user'];
    $gender = $_POST['Gender'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];
    $date = $_POST['Date_of_birth'];
    $password = $_POST['Passwords'];
    $role = $_POST['Roles'];

    if ($akun == "doctor") {
        $optional = $_POST['Spesialis'];
    } else if ($akun == "patient") {
        $optional = $_POST['Asuransi'];
    }

    $user = new User('localhost', 'root', '', 'klinik');
    $userData = $user->createUser($name, $gender, $email, $phone, $address, $date, $password, $role);
    $data_user = $user->getUserData($email);

    if ($akun == "doctor") {
        $dokter = new Doctor('localhost', 'root', '', 'klinik');
        $data = $dokter->createDoctor($data_user['ID_user'], $optional);
    } else if ($akun == "patient") {
        $patient = new Patient('localhost', 'root', '', 'klinik');
        $data = $patient->createPatient($data_user['ID_user'], $optional);
    }

    if ($userData && $data) {
        echo json_encode(["message" => "berhasil ditambah"]);
        exit();
    }
}

function update($id)
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $name = $_POST['Name_user'];
    $gender = $_POST['Gender'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];
    $date = $_POST['Date_of_birth'];
    $password = $_POST['Passwords'];
    $role = $_POST['Roles'];
    $spesialis = $_POST['Spesialis'];

    $user = new User('localhost', 'root', '', 'klinik');
    $userData = $user->updateUser($id, $name, $gender, $email, $phone, $address, $date, $password, $role);

    $dokter = new Doctor('localhost', 'root', '', 'klinik');
    $dokterData = $dokter->updateDoctor($id, $spesialis);

    if ($userData && $dokterData) {
        echo json_encode(["message" => "berhasil diupdate"]);
        exit();
    }
}

function delete($id)
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $user = new User('localhost', 'root', '', 'klinik');
    $result = $user->deleteUser($id);

    if ($result) {
        echo json_encode(["message" => "berhasil delete"]);
    } else {
        echo json_encode(["message" => "gagal delete"]);
    }
}

function jadwal()
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(["message" => "not_authenticated"]);
        exit();
    }

    $userRole = $_SESSION['user']['role'];

    switch ($userRole) {
        case 'doctor':
            $doctor = new Doctor('localhost', 'root', '', 'klinik');
            $jadwal_patient = $doctor->jadwalPatient();
            echo json_encode($jadwal_patient);
            exit();
            break;
        default:
            echo json_encode(["message" => "invalid_role"]);
            exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'login':
            $email = $_POST['Email'];
            $password = $_POST['Password'];

            $account = login($email, $password);

            if ($account) {
                $_SESSION['user'] = $account;
                echo json_encode($account);
                exit();
            } else {
                echo json_encode(["message" => "username atau password salah"]); // Authentication failed
                exit();
            }
            break;
        case 'read':
            $roles = $_POST['role'];
            read($roles);
            exit();
            break;
        case 'create':
            $roles = $_POST['role'];
            create($roles);
            exit();
            break;
        case 'update':
            $userID = $_POST['id'];
            update($userID);
            exit();
            break;
        case 'delete':
            $userID = $_POST['id'];
            delete($userID);
            exit();
            break;
        case 'fetch':
            $userID = $_POST['id'];
            fetch($userID);
            exit();
            break;
        case 'jadwalPatient':
            jadwal();
            exit();
            break;
        default:
            echo json_encode(["message" => "invalid_action"]);
            break;
    }
}
