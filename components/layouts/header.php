<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="t/static/images/logo.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="t/">Home</a>
                </li>
            </ul>

            <form id="profileForm" class="d-flex"></form>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function () {
        function getUser() {
            const user = {
                name: localStorage.getItem('userName'),
                id: localStorage.getItem('userId')
            };
            const html = (user.id) ? `
                    <div class='me-3 d-flex align-items-center notSelected'><img class='me-2 h-30' src='t/static/images/person.png'>${user.name}</div><button id='logoutBtn' type='button' class='btn btn-outline-info'>Log out</button>
                ` : `
                    <a class="nav-link active" aria-current="page" href="t/pages/login.php"><button
                            type="button" class="btn btn-outline-primary">log in</button>
                    </a>
                `;
            $("#profileForm").html(html);
        }
        getUser();
        $('#logoutBtn').click(function (event) {
            event.preventDefault();
            const data = {
                userId: localStorage.getItem('userId')
            }
            $.ajax({
                url: "/api/auth",
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify(data),
            })
                .done(response => {
                    localStorage.removeItem('userId');
                    location.reload();
                })
                .fail((xhr, status, error) => {
                    console.log(xhr.status);
                    xhr.responseJSON.error && console.log(xhr.responseJSON.error);
                })
        })
    })
</script>

<style>
    .navbar-brand img {
        width: 50px;
    }

    .h-30 {
        height: 30px;
    }

    .notSelected {
        user-select: none;
    }
</style>