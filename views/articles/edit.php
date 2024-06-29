<div class="container">
    <h2>Uredi novico</h2>
    <form action="?controller=articles&action=update&id=<?php echo $article->id; ?>" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Naslov</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article->title); ?>" required>
        </div>
        <div class="mb-3">
            <label for="abstract" class="form-label">Povzetek</label>
            <textarea class="form-control" id="abstract" name="abstract" required><?php echo htmlspecialchars($article->abstract); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Vsebina</label>
            <textarea class="form-control" id="text" name="text" required><?php echo htmlspecialchars($article->text); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Shrani spremembe</button>
    </form>
</div>
