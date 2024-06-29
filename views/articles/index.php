<div class="container">
    <h2 class="mb-4">Seznam novic</h2>
    <div class="row">
        <?php foreach ($articles as $article): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($article->title); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($article->abstract); ?></p>
                    </div>
                    <div class="card-footer text-muted"> 
                        Objavil: <a href="?controller=users&action=profile&id=<?php echo $article->user->id; ?>" class="text-decoration-none"><?php echo htmlspecialchars($article->user->username); ?></a>,
                        <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?>
                    </div>
                    <div class="card-body">
                        <a href="/articles/show?id=<?php echo $article->id; ?>" class="btn btn-primary">Preberi več</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
