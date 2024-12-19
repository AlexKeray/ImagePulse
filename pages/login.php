<?php

?>



<form class="border rounded p-4 w-50 mx-auto" method="POST" action="./handlers/handle_login.php">
    <h3 class="text-center">Log in</h3>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="username" class="form-control" id="username" name="username" value="<?php echo $flash['data']['username'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary mx-auto">Log in</button>
</form>