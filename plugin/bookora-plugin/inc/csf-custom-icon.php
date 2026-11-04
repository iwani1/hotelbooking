<?php 
if( ! function_exists( 'goyto_csf_custom_icon' ) ) {

    function goyto_csf_custom_icon( $icons ) {
    
        // Adding new icons
        $icons[]  = array(
          'title' => 'Flaticon',
          'icons' => array(
            "flaticon-right-up","flaticon-right-arrow","flaticon-right-up-1","flaticon-award-symbol","flaticon-trophy","flaticon-quote","flaticon-rewards","flaticon-help-center","flaticon-software-as-a-service","flaticon-add","flaticon-customer-care","flaticon-contract","flaticon-irregular","flaticon-geometric","flaticon-3d","flaticon-time","flaticon-right-down","flaticon-star","flaticon-abstract-shape","flaticon-triangle","flaticon-isometric","flaticon-market-research","flaticon-idea","flaticon-info","flaticon-rocket","flaticon-calendar","flaticon-user","flaticon-send","flaticon-badge","flaticon-square-with-rounded-sides","flaticon-flower","flaticon-round","flaticon-play-button-arrowhead","flaticon-shape-design","flaticon-shape-design-1","flaticon-shape-design-2","flaticon-black-star-silhouette","flaticon-innovative","flaticon-team-management","flaticon-team-management-1","flaticon-team-member","flaticon-24-hours-support","flaticon-awards","flaticon-winner-award","flaticon-award-trophy","flaticon-arrow-right","flaticon-arrow","flaticon-consultant","flaticon-financial-analysis-1","flaticon-play","flaticon-plus-sign","flaticon-minus","flaticon-right-chevron","flaticon-arrow-pointing-to-down","flaticon-left-arrow","flaticon-ux-design","flaticon-pen-tool","flaticon-logo","flaticon-graphic","flaticon-digital-art","flaticon-vector-design","flaticon-vector","flaticon-development","flaticon-3d-movie","flaticon-3d-modeling","flaticon-earth","flaticon-cyber-security","flaticon-cyber-security-1","flaticon-service","flaticon-blockchain","flaticon-migration","flaticon-migrating","flaticon-cyber-security-2","flaticon-coding","flaticon-custom","flaticon-cyber-security-3","flaticon-breach","flaticon-coding-1","flaticon-system-administration","flaticon-management","flaticon-blockchain-1","flaticon-right","flaticon-placeholder","flaticon-money","flaticon-money-bag","flaticon-loan","flaticon-modern-house","flaticon-building","flaticon-bed","flaticon-washbasin","flaticon-bathtub","flaticon-double-bed","flaticon-bed-1","flaticon-beds","flaticon-rulers","flaticon-social","flaticon-pen-tool-1","flaticon-team-work-icon","flaticon-collaboration","flaticon-solution"
          )
        );
    
        // Move custom icons to top of the list.
        $icons = array_reverse( $icons );
    
        return $icons;
    
      }
      add_filter( 'csf_field_icon_add_icons', 'goyto_csf_custom_icon' );
    }