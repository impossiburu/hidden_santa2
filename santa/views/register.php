<form action="/register" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_COOKIE['csrf_token'] ?? '' ?>">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button>Register</button>
</form>