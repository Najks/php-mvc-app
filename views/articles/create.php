<div class="container">
    <h2>Objavi novico</h2>
    <form action="?controller=articles&action=store" method="post">
        <div class="form-group">
            <label for="title">Naslov</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="abstract">Povzetek</label>
            <textarea class="form-control" id="abstract" name="abstract" required></textarea>
        </div>
        <div class="form-group">
            <label for="text">Vsebina</label>
            <textarea class="form-control" id="text" name="text" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Objavi</button>
    </form>
</div>
