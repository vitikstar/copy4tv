/* <?php if ($SC_VERSION > 15) { ?> */
img.close {  display: none; }
/* <?php } else { ?> */
button.close { 	display: none; }
/* <?php } ?> */

.record-grid a {
	text-decoration: none;
}
.blog-small-record img {
    vertical-align: top;
}
.blog-small-record {
    padding-left: 0px;
    height: 20px;
}
.blog-small-record li {
    float: left;
    padding: 0 15px 0 0;
}

.blog-small-record ul li {
    left: 0;
    list-style: none outside none;
}
.blog-small-record ul {
    padding: 0;
    margin: 0;
}

.blog-small-record li:first-child {
	margin-left: 0;
	left: 0;
	padding-left: 0;
}


a #ascp_list:after {
    content: '\f00b\0020';
    font-family: FontAwesome;
}

a #ascp_grid:after {
    content: '\f00a\0020';
    font-family: FontAwesome;

}

#ascp_list, #ascp_grid {
	color: #aaa;

	font-weight: normal;
	font-size: 1.5em;
}

a:hover #ascp_list, a:hover #ascp_grid, #ascp_list.ascp_list_active, #ascp_grid.ascp_grid_active  {
    color: #555;
}

ul.ascp_list_info li {
    width: auto;
    max-width: 100%;
    margin-right: 8px;
}

ul.ascp_list_info li:first-child {
	margin-left: 0;
	left: 0;
	padding-left: 0;
}

.blog-data-record {
    color: #aaa;
}
.blog-data-record:before {
    content: '\f017\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}

.blog-comments-record {
    color: #aaa;
}

.blog-comments-record:before {
    content: '\f0e5\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}


.blog-comments-karma {
    color: #aaa;
}

.blog-comments-karma:before {
    content: '\f087\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}


.blog-viewed-record {
    color: #aaa;
}

.blog-viewed-record:before {
    content: '\f06e\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}

.blog-category-record {
    color: #aaa;
}

.blog-category-record:before {
    content: '\f114\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}

.blog-author-record {
    color: #aaa;
}

.blog-author-record:before {
    content: '\f007\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}

.blog-list-category {
    color: #aaa;
}

.blog-list-category:before {
    content: '\f114\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}


.blog-list-record {
    color: #aaa;
}

.blog-list-record:before {
    content: '\f016\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}


.blog-list-manufacturer {
    color: #aaa;
}

.blog-list-manufacturer:before {
    content: '\f11d\0020';
    font-family: FontAwesome;
    font-size: 1.1em;
}


h9.blog-icon {
    height: 16px;
    width: 16px;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    font-size: 90%;
    font-weight: normal;
}

.record_content {
   /* <?php if (isset($settings_general['css']['record-content']) && $settings_general['css']['record-content'] != '') { ?> */
    background-color: /* <?php if (1==1) echo $agoo_content_before_47.$settings_general['css']['record-content'].$agoo_content_after_47; else { ?> */ #000 /* <?php } ?> */ !important;
   /* <?php } ?> */
}

.blog-content {
   /* <?php if (isset($settings_general['css']['blog-content']) && $settings_general['css']['blog-content']!='') { ?> */
    background-color: /* <?php if (1==1) echo $agoo_content_before_47.$settings_general['css']['blog-content'].$agoo_content_after_47; else { ?> */ #000 /* <?php } ?> */ !important;
   /* <?php } ?> */
}
.ascp-list-title {
    	/* <?php if (isset($settings_general['css']['ascp-list-title-color']) && $settings_general['css']['ascp-list-title-color']!='') { ?> */
    	color: <?php echo $settings_general['css']['ascp-list-title-color']; ?> !important;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-size']) && $settings_general['css']['ascp-list-title-size']!='') { ?> */
        font-size: <?php echo $settings_general['css']['ascp-list-title-size']; ?> !important;
        /* <?php  } else { ?> */
        font-size: 120%;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-line']) && $settings_general['css']['ascp-list-title-line']!='') { ?> */
        line-height: <?php echo $settings_general['css']['ascp-list-title-line']; ?> !important;
        /* <?php  } else { ?> */
        line-height: 140%;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-decoration']) && $settings_general['css']['ascp-list-title-decoration']!='') { ?> */
        text-decoration: <?php echo $settings_general['css']['ascp-list-title-decoration']; ?> !important;
        /* <?php }  else {  ?> */
        text-decoration: none;
        /* <?php } ?> */
        /* <?php if (isset($settings_general['css']['ascp-list-title-weight']) && $settings_general['css']['ascp-list-title-weight']!='') { ?> */
        font-weight: <?php echo $settings_general['css']['ascp-list-title-weight'];  ?> !important;
        /* <?php  }  else {  ?> */
        font-weight: normal;
        /* <?php } ?> */
}
.ascp-list-title-widget {
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-color']) && $settings_general['css']['ascp-list-title-widget-color']!='') { ?> */
    color: <?php echo $settings_general['css']['ascp-list-title-widget-color']; ?> !important;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-size']) && $settings_general['css']['ascp-list-title-widget-size']!='') { ?> */
    font-size: <?php echo $settings_general['css']['ascp-list-title-widget-size'];  ?> !important;
    /* <?php  }  else {  ?> */
    font-size: 110%;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-line']) && $settings_general['css']['ascp-list-title-widget-line']!='') { ?>*/
    line-height: <?php echo $settings_general['css']['ascp-list-title-widget-line']; ?> !important;
    /* <?php }  else { ?> */
    line-height: 120%;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-decoration']) && $settings_general['css']['ascp-list-title-widget-decoration']!='') { ?> */
    text-decoration: <?php echo $settings_general['css']['ascp-list-title-widget-decoration']; ?> !important;
    /* <?php } else {  ?> */
    text-decoration: none;
    /* <?php  }  ?> */
    /* <?php if (isset($settings_general['css']['ascp-list-title-widget-weight']) && $settings_general['css']['ascp-list-title-widget-weight']!='') {  ?> */
    font-weight: <?php echo $settings_general['css']['ascp-list-title-widget-weight']; ?> !important;
    /* <?php }  else { ?> */
    font-weight: normal;
    /* <?php } ?> */
}
/* <?php if (isset($settings_general['css']['css']) && $settings_general['css']['css']!='') {
    echo $agoo_content_before_47.$agoo_seocmscss.$agoo_content_after_47;
} ?> */