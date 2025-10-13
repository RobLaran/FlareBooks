<div class="main-body">

<header class="header">
  <div class="header-left">
    <button id="menu-toggle" class="menu-btn">
      <i class="fa fa-bars"></i>
    </button>
  </div>

  <div class="header-right">
    <div class="user-menu">
      <img src="<?= getFile("public/img/" . $_SESSION['user']['image']) ?>" alt="">
      <span class="username">Hi, <?= $_SESSION['user']['name'] ?> </span>
      <a href="<?= routeTo('/auth/logout') ?>" id="logout"> Logout</a>
    </div>
  </div>
</header>


