<div class="container" style="padding: 20px;">
    <h1>Welcome to the Framework Demo</h1>
    <p class="lead">This framework is designed to be lightweight, secure, and easy to use.</p>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <h3>Core Features</h3>
            <ul class="list-group">
                <?php foreach ($arg['features'] as $feature => $desc): ?>
                    <li class="list-group-item">
                        <strong><?php echo \App\Util\Security::escape($feature); ?>:</strong>
                        <?php echo \App\Util\Security::escape($desc); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Explore Modules</h3>
            <div class="list-group">
                <a href="<?php echo WEBPATH; ?>public/demo/database" class="list-group-item list-group-item-action">
                    Database & Stored Procedures
                </a>
                <a href="<?php echo WEBPATH; ?>public/demo/security" class="list-group-item list-group-item-action">
                    Security Best Practices
                </a>
            </div>
        </div>
    </div>
</div>