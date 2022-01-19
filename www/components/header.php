<header class="header sticky-top">
    <nav class="navbar">
        <div class="navbar-logo">
            <a class="navbar__logo-link" href="../home/">
                <img src="/images/logo.png" alt="logo" class="navbar__logo-img">
                <h3 class='d-none d-md-inline-block navbar__logo-name'>ICE TEA</h3>
            </a>
        </div>
        <ul class="navbar__list list-center">
            <?php
                require_once('../../api/header.php');
                $array = get_all_header($account['account_type']);
                foreach($array as $element) {
                    echo "\t<li class='navbar__list-item'>\n";
                    echo "\t\t<a href='$element->href' class='navbar__item-link' title='$element->name'>\n";
                    echo "\t\t\t<i class='navbar__list-icon $element->icon'></i>\n";
                    echo "\t\t\t<span class='d-none d-md-inline-block'>$element->name</span>\n";
                    echo "\t\t</a>\n";
                    echo "\t</li>\n";
                }
            ?>
        </ul>
        
        <div class="dropdown">
            <span
                    type="button" id="dropdownMenu" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-caret-down"></i>
            </span>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu">
                <a class="dropdown-item" href="../profile/">Tài khoản của tôi</a>
                <a class="dropdown-item" href="../profile/change-password.php">Đổi mật khẩu</a>
                <a class="dropdown-item" onclick="handleLogOut()">Đăng xuất</a>
            </div>
            </div>
    </nav>
</header>