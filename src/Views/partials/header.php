<div class="main-body">

<header class="header">
  <div class="header-left">
    <button id="menu-toggle" class="menu-btn">
      <i class="fa fa-bars"></i>
    </button>
  </div>

  <div class="header-right">
    <div class="user-menu">
      <i class="fa fa-user-circle user-avatar fa-2x" aria-hidden="true"></i>
      <span class="username">Hi, <?= $_SESSION['user']['username'] ?></span>
      <div class="dropdown">
        <a href="<?= routeTo('/auth/logout') ?>">Logout</a>
      </div>
    </div>
  </div>
</header>


