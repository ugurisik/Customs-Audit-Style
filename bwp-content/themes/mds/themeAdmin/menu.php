<li class="kt-menu__item  kt-menu__item--submenu <?php if($menu == "theme-set"){echo 'kt-menu__item--open';}?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon"><i class="flaticon-settings"></i></span>
        <span class="kt-menu__link-text">Tema Ayarları</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item  kt-menu__item--parent <?php if($menu == "theme-set"){echo 'kt-menu__item--active';}?>" aria-haspopup="true">
                <span class="kt-menu__link">
                    <span class="kt-menu__link-text">Ayarlar</span>
                </span>
            </li>
            <li class="kt-menu__item <?php if($menuPage == "theme-setting"){echo 'kt-menu__item--active';}?>">
                <a  href="theme.php?modul=settings" class="kt-menu__link">
                    <i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i>
                    <span class="kt-menu__link-text">Tema Ayarları</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?php if($altMenu == "theme-modul"){echo 'kt-menu__item--active kt-menu__item--open';}?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                        <span></span>
                    </i>
                    <span class="kt-menu__link-text">Modüller</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item <?php if($menuPage == "theme-ads"){echo 'kt-menu__item--active';}?>" aria-haspopup="true">
                            <a href="theme.php?modul=ads" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i>
                                <span class="kt-menu__link-text">Reklamlar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</li>