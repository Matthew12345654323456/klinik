<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        body {
            background-image: url(asset/hospital.png);
        }
    </style>

</head>

<body>

    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
            <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                    The best offer <br />
                    <span style="color: hsl(218, 81%, 75%)">for your business</span>
                </h1>
                <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    Temporibus, expedita iusto veniam atque, magni tempora mollitia
                    dolorum consequatur nulla, neque debitis eos reprehenderit quasi
                    ab ipsum nisi dolorem modi. Quos?
                </p>
            </div>

            <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div class="card bg-glass">
                    <div class="card-body px-4 py-5 px-md-5">
                        <form id="login" enctype="multipart/form-data" method="POST">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="Email">Email address</label>
                                <input type="email" id="Email" name="Email" class="form-control" />
                            </div>
                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="Password">Password</label>
                                <input type="password" id="Password" name="Password" class="form-control" />
                            </div>
                            <!-- Submit button -->
                            <input type="submit" class="btn btn-primary btn-block mb-4" value="Sign in">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#login").submit(function(event) {
                event.preventDefault();

                var email = $("#Email").val().trim();
                var password = $("#Password").val().trim();

                if (
                    email === "" ||
                    password === ""
                ) {
                    alert("data harus lengkap terisi");
                } else {
                    var form = $(this);
                    var data = new FormData(form[0]);

                    // Add action to FormData
                    data.append("action", "login");

                    $.ajax({
                        type: "POST",
                        enctype: "multipart/form-data",
                        url: "controller/proses.php",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: "json",
                        success: function(response) {
                            if (response.role == "doctor") {
                                location.href = "view/doctor/home.php";
                            } else if (response.role == "patient") {
                                location.href = "view/patient/home.php";
                            } else if (response.role == "admin") {
                                location.href = "view/admin/home.php";
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(response) {
                            alert(response);
                        },
                    });
                }
            });
        });
    </script>

</body>

</html>