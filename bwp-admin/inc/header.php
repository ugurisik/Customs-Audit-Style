<div class="kt-header kt-grid__item  kt-header--fixed " id="kt_header">
    <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
        </div>
    </div>
    <div class="kt-header__topbar">
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Merhaba,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile"><?php echo $userKont['name'];?></span>
                    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?php echo $yazi->harfAl($userKont['name'],1);?></span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(assets/media/misc/bg-1.jpg)">
                    <div class="kt-user-card__avatar">
                    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?php echo $yazi->harfAl($userKont['name'],1);?></span>
                    </div>
                    <div class="kt-user-card__name"><?php echo $userKont['name'];?> <?php echo $userKont['surname'];?></div>
                </div>
                <div class="kt-notification">
                    <a href="profile.php" class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                Hesabım
                            </div>
                        </div>
                    </a>
                    <div class="kt-notification__custom kt-space-between">
                        <form method="post">
                            <button name="close" type="submit" class="btn btn-label btn-label-brand btn-sm btn-bold">Oturumu Kapat</button>
                        </form>
                        <?php 
                            if(isset($_POST['close'])){
                                $data = Array (
                                    'login' => 0,
                                    'loginID' => NULL
                                );
                                $db->where ('id', $userKont['id']);
                                $db->update ('users', $data);
                                $security->signOut();
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>