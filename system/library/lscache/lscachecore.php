<?php

/**
 * Core function of communicating with LSWS Server for LSCache operations
 * The Core class works at site level, its operation will only affect a site in the server.
 *
 * @since      1.1.0
 * @author     LiteSpeed Technologies <info@litespeedtech.com>
 * @copyright  Copyright (c) 2017-2018 LiteSpeed Technologies, Inc. (https://www.litespeedtech.com)
 * @license    https://opensource.org/licenses/GPL-3.0
 */
class LiteSpeedCacheCore extends LiteSpeedCacheBase
{

    protected $site_only_tag = "";

    /**
     *
     *  set the specified tag for this site
     *
     * @since   1.0.0
     */
    public function __construct($tag = '')
    {
        if(!isset($tag) || ($tag=='')){
            $this->site_only_tag = substr(md5(__DIR__),0,4);
        }
        else{
            $this->site_only_tag = $tag;
        }
    }

    /**
     *
     * put tag into Array in the format for this site only.
     *
     * @since   1.0.0
     */
    protected function tagsForSite(Array &$tagArray, $rawTags, $prefix = "")
    {
        if (!isset($rawTags)) {
            return;
        }

        if (empty($rawTags)) {
            return;
        }

        if(is_array($rawTags)){
            $tags = $rawTags;
        } else {
            $tags = explode(",", $rawTags);
        }
        
        foreach ($tags as $tag) {
            if(trim($tag)==""){
                continue;
            }
            $tagStr = $prefix . $this->site_only_tag . trim($tag);
            if(!in_array($tagStr, $tagArray, false)){
                array_push($tagArray, $tagStr);
            }
        }
    }

    /**
     *
     *  purge all public cache of this site
     *
     * @since   1.0.0
     */
    public function purgeAllPublic()
    {
        $LSheader = self::CACHE_PURGE . 'public,' . $this->site_only_tag;
        $this->liteSpeedHeader($LSheader);
    }

    /**
     *
     *  purge all private cache of this session
     *
     * @since   0.1
     */
    public function purgeAllPrivate()
    {
        $LSheader = self::CACHE_PURGE . 'private,' . $this->site_only_tag;
        $this->liteSpeedHeader($LSheader);
    }

    /**
     *
     * Cache this page for public use if not cached before
     *
     * @since   1.0.1
     */
    public function cachePublic($publicTags, $esi=false)
    {
        $siteTags = Array();
        $this->tagsForSite($siteTags, $publicTags);
        if(count($siteTags)<=0){
            return;
        }
        
        $LSheader = self::PUBLIC_CACHE_CONTROL . $this->public_cache_timeout;
        if($esi){
            $LSheader .= ',esi=on';
        }        
        $this->liteSpeedHeader($LSheader);

        array_push($siteTags, $this->site_only_tag);
        $LSheader = self::CACHE_TAG . implode(',', $siteTags);
        $this->liteSpeedHeader($LSheader);
    }

    /**
     *
     * Cache this page for private session if not cached before
     *
     * @since   0.1
     */
    public function cachePrivate($publicTags, $privateTags = "", $esi=false)
    {
        $LSheader = self::PRIVATE_CACHE_CONTROL . $this->private_cache_timeout;
        if($esi){
            $LSheader .= ',esi=on';
        }
        $this->liteSpeedHeader($LSheader);

        $siteTags = Array();
        $this->tagsForSite($siteTags, $publicTags, "public:");
        if(count($siteTags)>0){
            array_push($siteTags, "public:" . $this->site_only_tag);
        }
        
        $this->tagsForSite($siteTags, $privateTags);
        array_push($siteTags, $this->site_only_tag);
        
        $LSheader = self::CACHE_TAG . implode(',', $siteTags);
        $this->liteSpeedHeader($LSheader);
        
    }

    public function getSiteOnlyTag(){
        return $this->site_only_tag;
    }
    
}
