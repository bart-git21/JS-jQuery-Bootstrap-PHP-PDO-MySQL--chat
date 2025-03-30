<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="static/images/logo.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Home</a>
                </li>
            </ul>

            <form class="d-flex">
                <?php
                session_start();
                if (isset($_SESSION['userName'])) {
                    $name = $_SESSION['userName'];
                    echo "<div class='me-3 d-flex align-items-center notSelected'><img class='me-2 h-30' src='static/images/person.png'>$name</div><button type='button' class='btn btn-outline-info'>Log out</button>";
                } else { ?>
                    <a class="nav-link active" aria-current="page" href="pages/login.php">log
                        in</a>
                <?php } ?>
            </form>
        </div>
    </div>
</nav>

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