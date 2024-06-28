<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        #wrapper {
            overflow-x: hidden;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            transition: margin 0.25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
            margin-left: 0;
        }

        #profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: auto;
            /* Align to the right */
            background-image: url(https://s.hdnux.com/photos/51/23/24/10827008/4/1200x0.jpg);
            /* Replace with the actual path to your image */
            background-size: cover;
            /* Ensure the image covers the button */
            background-position: center;
            /* Center the image within the button */
            background-repeat: no-repeat;
            /* Do not repeat the background image */
            border: none;
            /* Remove the button border */
            padding: 0;
            /* Remove any default padding */
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            body.sb-sidenav-toggled #wrapper #sidebar-wrapper {
                margin-left: -15rem;
            }
        }

        /* Style for the "Dropdown" image */

        .dropdown-image:hover {
            background-color: #777;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="home.php">Home</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="datauser.php">Data user</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="dokter.php">Dokter</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="pasien.php">Pasien</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-secondary" id="sidebarToggle">Menu</button>
                    <button id="profile-circle" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="visually-hidden">Toggle navigation</span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <div class="d-lg-none">
                                <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                            </div>
                            <div class="d-none d-lg-block">
                                <div class="dropdown">
                                    <button id="profile-circle" type="button" class="btn" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <h1 class="mt-4"></h1>
                <table id="datatable" class="display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Date_of_birth</th>
                            <th>Passwords</th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <!-- modal tambah -->
                <div class="modal fade" id="tambah" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Tambah data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addDataForm" enctype="multipart/form-data" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="col-form-label">Nama :</label>
                                        <input type="text" class="form-control" id="name" name="Name_user" placeholder="Masukkan nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gender" class="col-form-label">Kelamin : </label>
                                        <select name="Gender" id="gender" class="form-select" required>
                                            <option selected></option>
                                            <option value="laki-laki">laki-laki</option>
                                            <option value="perempuan">perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="col-form-label">Email : </label>
                                        <input type="email" class="form-control" id="email" name="Email" placeholder="Masukkan email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="col-form-label">Phone : </label>
                                        <input type="text" class="form-control" id="phone" name="Phone" placeholder="Masukkan telepon" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="col-form-label">Address : </label>
                                        <input type="text" class="form-control" id="address" name="Address" placeholder="Masukkan alamat" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="col-form-label">Date : </label>
                                        <input type="date" class="form-control" id="date" name="Date_of_birth" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwords" class="col-form-label">Passwords : </label>
                                        <input type="password" class="form-control" id="passwords" name="Passwords" placeholder="Masukkan password" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal edit -->
                <div class="modal fade" id="edit" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Edit data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editDataForm" enctype="multipart/form-data" method="POST">
                                    <input type="text" class="form-control" id="id" name="ID_user" hidden>
                                    <div class="mb-3">
                                        <label for="name" class="col-form-label">Nama :</label>
                                        <input type="text" class="form-control" id="name" name="Name_user" placeholder="Masukkan nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gender" class="col-form-label">Kelamin : </label>
                                        <select name="Gender" id="gender" class="form-select" required>
                                            <option selected></option>
                                            <option value="laki-laki">laki-laki</option>
                                            <option value="perempuan">perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="col-form-label">Email : </label>
                                        <input type="email" class="form-control" id="email" name="Email" placeholder="Masukkan email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="col-form-label">Phone : </label>
                                        <input type="text" class="form-control" id="phone" name="Phone" placeholder="Masukkan telepon" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="col-form-label">Address : </label>
                                        <input type="text" class="form-control" id="address" name="Address" placeholder="Masukkan alamat" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="col-form-label">Date : </label>
                                        <input type="date" class="form-control" id="date" name="Date_of_birth" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwords" class="col-form-label">Passwords : </label>
                                        <input type="password" class="form-control" id="passwords" name="Passwords" placeholder="Masukkan password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="col-form-label">Role : </label>
                                        <select name="Roles" id="role" class="form-select" required>
                                            <option selected></option>
                                            <option value="patient">patient</option>
                                            <option value="doctor">doctor</option>
                                            <option value="receptionist">receptionist</option>
                                            <option value="admin">admin</option>
                                            <option value="pharmacy">pharmacy</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#datatable").DataTable({
                responsive: true,
                ajax: {
                    url: "../../controller/proses.php",
                    type: "POST",
                    data: {
                        action: "read",
                        role: "all",
                    },
                    dataType: "json",
                },
                columns: [{
                        data: "Name_user"
                    },
                    {
                        data: "Gender"
                    },
                    {
                        data: "Email"
                    },
                    {
                        data: "Phone"
                    },
                    {
                        data: "Address"
                    },
                    {
                        data: "Date_of_birth"
                    },
                    {
                        data: "Passwords"
                    },
                    {
                        data: "Roles"
                    },
                ],
            });
        });

        $(document).ready(function() {
            // Toggle the side navigation
            $("#sidebarToggle").click(function(event) {
                event.preventDefault();
                $("body").toggleClass("sb-sidenav-toggled");
                localStorage.setItem("sb|sidebar-toggle", $("body").hasClass("sb-sidenav-toggled"));
            });
        });
    </script>
</body>

</html>