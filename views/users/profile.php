<div class="container mt-5">
    <div class="card">
        <div class="card-header">
        <h2 class="card-title">Profil uporabnika: <a href="?controller=users&action=profile&id=<?php echo $user->id; ?>" 
            class="text-decoration-none"><?php echo htmlspecialchars($user->username); ?></a>
        </h2>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Email: <?php echo htmlspecialchars($user->email); ?></li>
            <li class="list-group-item">Število objavljenih novic: <?php echo $num_articles; ?></li>
            <li class="list-group-item">Število komentarjev: <?php echo $num_comments; ?></li>
        </ul>
    </div>
</div>
