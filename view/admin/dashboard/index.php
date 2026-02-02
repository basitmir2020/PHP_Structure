<div style="padding: 20px;">
    <h1>Admin Dashboard</h1>
    <p class="lead">Welcome back, <?php echo htmlspecialchars($arg['user']); ?>!</p>

    <hr>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Stats</h5>
                    <p class="card-text">System is running efficiently.</p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <a href="<?php echo WEBPATH; ?>public/login/logout" class="btn btn-danger">Logout</a>
</div>