<div class="container">
    <h2 class="mb-4">Moje novice</h2>
    <div class="row">
        <?php foreach ($articles as $article): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($article->title); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($article->abstract); ?></p>
                        <a href="?controller=articles&action=show&id=<?php echo $article->id; ?>" class="btn btn-primary">Preberi več</a>
                        <a href="?controller=articles&action=edit&id=<?php echo $article->id; ?>" class="btn btn-secondary">Uredi</a>
                        <a href="?controller=articles&action=delete&id=<?php echo $article->id; ?>" class="btn btn-dark">Izbriši</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
