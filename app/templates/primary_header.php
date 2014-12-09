<header>
  <h1>Lære</h1>
  <?php if(UserLogin::getUserID() !== null): ?>
    <nav>
      <a href="/api/logout">Log Out</a>
    </nav>
  <?php endif ?>
</header>
