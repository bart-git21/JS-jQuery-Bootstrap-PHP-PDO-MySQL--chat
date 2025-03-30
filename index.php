<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="static/style/style.css">

</head>

<body class="vh-100 d-flex flex-column">
    <header><?php include "components/layouts/header.php" ?></header>

    <div class="container p-5 flex-grow-1">
        <div class="d-flex flex-column justify-content-center align-items-center h-100">
            <h1 class="text-center">Log in</h1>
            <div class="w-400 h-100 d-flex align-items-center">
                <form class="w-100 p-5 rounded shadow">
                    <div class="mb-3 d-flex justify-content-center">
                        <img class="w-25" src="./static/images/person.png" alt="">
                    </div>
                    <div class="mb-3">
                        <label for="nameFormInput" class="form-label">User name</label>
                        <input type="text" class="form-control" id="nameFormInput">
                    </div>
                    <div class="mb-3">
                        <label for="passwordFormInput" class="form-label">Password</label>
                        <input type="password" class="form-control" id="passwordFormInput">
                    </div>
                    <div id="alertMessage" class="alert alert-warning invisibl" role="alert"></div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <small>New user?</small>
                            <a id="signUpBtn" href="#">Registration</a>
                        </div>
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="p-2">
        <div class="text-center">
            Icons by <a target="_blank" href="https://icons8.com">Icons8</a>
        </div>
    </footer>

    <script>
        $(document).ready(function () {
            (function clearForm() {
                $("#nameFormInput").val("");
                $("#alertMessage").removeClass('visibl');
            })()

            $('form').on("submit", function () {
                event.preventDefault();
                $("#alertMessage").removeClass('visibl');
                const data = {
                    login: $("#nameFormInput").val(),
                    password: $("#passwordFormInput").val(),
                };
                // check for not empty data
                if (!data.login || !data.password) {
                    document.querySelector("#alertMessage").classList.add("visibl");
                    $("#alertMessage").text('Name and password are required.');
                    return;
                }
                $.ajax({
                    url: "/api/auth/",
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    data: JSON.stringify(data),
                })
                    .done(response => {
                        console.log(response);
                    })
                    .fail((xhr, status, error) => {
                        console.log(xhr.status);
                        $("#alertMessage").addClass('visibl');
                        xhr.responseJSON.error && $("#alertMessage").text(xhr.responseJSON.error);
                    })
            })

            $("#signUpBtn").on("click", function () {
                const newUser = {
                    login: $("#nameFormInput").val(),
                    password: $("#passwordFormInput").val()
                };
                $("#alertMessage").removeClass('visibl');
                // check for not empty data
                if (!newUser.login || !newUser.password) {
                    $("#alertMessage").addClass('visibl');
                    $("#alertMessage").text('Name and password are required.');
                    return;
                }
                // check for valid password
                if (newUser.password.length < 6) {
                    $("#alertMessage").addClass('visibl');
                    $("#alertMessage").text('Password must be at least 6 characters long.');
                    return;
                }
                $.ajax({
                    url: "/api/user/",
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    data: JSON.stringify(newUser),
                })
                    .done(response => {
                        console.log(response);
                        // TO DO
                        // after registration send authentication request
                    })
                    .fail((xhr, status, error) => {
                        console.log(xhr.status);
                        $("#alertMessage").addClass('visibl');
                        xhr.responseJSON.error && $("#alertMessage").text(xhr.responseJSON.error);
                    })
            })
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>