<div class="container">
    <h3 class="mb-3">Spremeni geslo</h3>
    <?php if (isset($error) && !empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form action="?controller=users&action=changePassword" method="POST">
        <div class="mb-3">
            <label for="oldPassword" class="form-label">Staro geslo</label>
            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
        </div>
        <div class="mb-3">
            <label for="newPassword" class="form-label">Novo geslo</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
        </div>
        <div class="mb-3">
            <label for="confirmNewPassword" class="form-label">Ponovi novo geslo</label>
            <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Spremeni geslo</button>
    </form>
</div>
