<style>
    .form-change {
        margin-top: 40px;
        margin-bottom: 40px;
        
    }

</style>
<form class="border rounded p-4 w-50 mx-auto form-change" method="POST" action="./handlers/handle_change_username.php">
    <h3 class="text-center">Change username</h3>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="username" class="form-control" id="username" name="username" value="<?php echo $flash['data']['username'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="current_password" class="form-label">Current password</label>
        <input type="password" class="form-control" id="current_password" name="current_password">
    </div>
    <button type="submit" class="btn btn-primary mx-auto">Change username</button>
</form>

<form class="border rounded p-4 w-50 mx-auto form-change" method="POST" action="./handlers/handle_change_password.php">
    <h3 class="text-center">Change password</h3>
    <div class="mb-3">
        <label for="old_password" class="form-label">Old password</label>
        <input type="password" class="form-control" id="old_password" name="old_password">
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">New password</label>
        <input type="password" class="form-control" id="new_password" name="new_password">
    </div>
    <button type="submit" class="btn btn-primary mx-auto">Change password</button>
</form>