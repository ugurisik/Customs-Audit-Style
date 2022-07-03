<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="dashboard.php">
                <img alt="Logo" src="assets/media/logos/logo-12.png">
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler"><span></span></button>
        </div>
    </div>
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav ">
            <?php 
                $pmEncode = json_encode($panelMenu);
                $pmDecode = json_decode($pmEncode);
                foreach($pmDecode as $k=>$v){
                    if(count($v->children) == 0){
                        if($v->active == $mpage){ $active= "kt-menu__item--active";}else{$active = "";}
                        echo '<li class="kt-menu__item '.$active.'" aria-haspopup="true">';
                            echo '<a href="'.$v->href.'" target="'.$v->target.'" class="kt-menu__link">';
                                echo '<i class="kt-menu__link-icon '.$v->icon.'"></i>';
                                echo '<span class="kt-menu__link-text">'.$v->text.'</span>';
                            echo '</a>';
                        echo '</li>';
                    }
                    else {
                        if($v->active == $mpage){ $active= "kt-menu__item--open kt-menu__item--active";}else{$active = "";}
                        echo '<li class="kt-menu__item kt-menu__item--submenu '.$active.'" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">';
                            echo '<a href="javascript:;" class="kt-menu__link kt-menu__toggle">';
                                echo '<i class="kt-menu__link-icon '.$v->icon.'"></i>';
                                echo '<span class="kt-menu__link-text">'.$v->text.'</span>';
                                echo '<i class="kt-menu__ver-arrow la la-angle-right"></i>';
                            echo '</a>';
                            echo '<div class="kt-menu__submenu"><span class="kt-menu__arrow"></span>';
                                echo '<ul class="kt-menu__subnav">';
                                    for ($i=0; $i < count($v->children); $i++) { 
                                        
                                        if(count($v->children[$i]->children) == 0){
                                            if($v->children[$i]->active == $maltpage){ $active= "kt-menu__item--active";}else{$active = "";}
                                            echo '<li class="kt-menu__item '.$active.'" aria-haspopup="true">';
                                                echo '<a href="'.$v->children[$i]->href.'" target="'.$v->children[$i]->target.'" class="kt-menu__link ">';
                                                    echo '<i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>';
                                                    echo '<span class="kt-menu__link-text">'.$v->children[$i]->text.'</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }else {
                                            if($v->children[$i]->active == $maltpage){ $active= "kt-menu__item--open kt-menu__item--active";}else{$active = "";}
                                            echo '<li class="kt-menu__item kt-menu__item--submenu '.$active.'" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">';
                                                echo '<a href="javascript:;" class="kt-menu__link kt-menu__toggle">';
                                                echo '<i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i>';
                                                    echo '<span class="kt-menu__link-text">'.$v->children[$i]->text.'</span>';
                                                    echo '<i class="kt-menu__ver-arrow la la-angle-right"></i>';
                                                echo '</a>';
                                                echo '<div class="kt-menu__submenu"><span class="kt-menu__arrow"></span>';
                                                    echo '<ul class="kt-menu__subnav">';
                                                        for ($ic=0; $ic < count($v->children[$i]->children); $ic++) { 
                                                            if($v->children[$i]->children[$ic]->active == $mlink){ $active= "kt-menu__item--active";}else{$active = "";}
                                                            echo '<li class="kt-menu__item '.$active.'" aria-haspopup="true">';
                                                                echo '<a href="'.$v->children[$i]->children[$ic]->href.'" target="'.$v->children[$i]->children[$ic]->target.'" class="kt-menu__link">';
                                                                    echo '<i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>';
                                                                    echo '<span class="kt-menu__link-text">'.$v->children[$i]->children[$ic]->text.'</span>';
                                                                echo '</a>';
                                                            echo '</li>';
                                                        }
                                                    echo '</ul>';
                                                echo '</div>';
                                            echo '</li>';
                                        }
                                    }
                                echo '</ul>';
                            echo '</div>';
                        echo '</li>';
                    }
                }
            ?>

            </ul>
        </div>
    </div>
</div>