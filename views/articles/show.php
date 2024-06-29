<div class="container">
    <h3 class="mb-4">Seznam novic</h3>
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($article->title); ?></h4>
                    <p class="card-text"><b>Povzetek:</b> <?php echo htmlspecialchars($article->abstract); ?></p>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($article->text)); ?></p>
                </div>

                <div class="card-footer text-muted">
                    Objavil: <a href="?controller=users&action=profile&id=<?php echo $article->user->id; ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($article->user->username); ?></a>, 
                    <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?>
                </div>

                <?php if(isset($_SESSION['USER_ID'])): ?>
                    <div class="card-body border-top">
                        <h3 class="card-title">Dodaj komentar</h3>
                        <form action="?controller=articles&action=addComment" method="post">
                            <div class="mb-3">
                                <label for="content" class="form-label">Vaš komentar</label>
                                <textarea class="form-control" id="content" name="content" required></textarea>
                            </div>
                            <input type="hidden" name="article_id" value="<?php echo $article->id; ?>">
                            <button type="submit" class="btn btn-primary">Objavi komentar</button>
                        </form>
                    </div>
                <?php endif; ?>
                
                <!-- Prikaz komentarjev -->
                <?php if(!empty($comments)): ?>
                    <div class="card-body border-top">
                        <h3 class="card-title" >Komentarji:</h3>
                            <?php foreach ($comments as $comment): ?>
                                <p>
                                    <strong>
                                        <!-- Link do user profila Komentiranca-->
                                        <a href="?controller=users&action=profile&id=<?php echo $comment['user_id']; ?>"class="text-decoration-none">
                                        <?php echo htmlspecialchars($comment['username']); ?>
                                        </a>:
                                    </strong> 
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </p>
                            <p class="text-muted"><?php echo date_format(date_create($comment['date']), 'd. m. Y \ob H:i:s'); ?></p>
                            <?php endforeach; ?>
                        </div>
                <?php endif; ?>
            </div>
            <a href="/" class="btn btn-primary mt-3">Nazaj</a>
        </div>
    </div>
</div>
