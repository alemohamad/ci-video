<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Video Library
 *
 * Library that get information from YouTube and Vimeo videos.
 *
 * @package		CodeIgniter
 * @category	Library
 * @author		Ale Mohamad
 * @link		alemohamad.com
 * @version 	1.0
 */

class Video {

    private $_video_id = FALSE;
    private $_video_type = FALSE;

    /**
     * Add Video
     *
     * This method automatically recognizes if the video is from YouTube or Vimeo.
     *
     * @access	public
     * @param	string	video url
     * @return	string	video ID
     */
    public function add_video($link)
    {
        if(!preg_match("/vimeo/", $link)) {
            $vendor = 'youtube';
        } else {
            $vendor = 'vimeo';
        }

        if($vendor == 'youtube') {
            if($id = $this->_get_youtube_id($link)) {
                $this->_video_id = $id;
                $this->_video_type = 'youtube';
                return $this->_video_id;
            } else {
                return FALSE;
            }
        } else if($vendor == 'vimeo') {
            if($id = $this->_get_vimeo_id($link)) {
                $this->_video_id = $id;
                $this->_video_type = 'vimeo';
                return $this->_video_id;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Return Video ID
     *
     * @access	public
     * @return	string	video ID
     */
    public function get_video_id() {
        return $this->_video_id;
    }

    /**
     * Return Video Type (youtube or vimeo)
     *
     * @access	public
     * @return	string	video type
     */
    public function get_video_type() {
        return $this->_video_type;
    }

    /**
     * Return url of video
     *
     * @access	public
     * @return	string	video url
     */
    public function get_video_url()
    {
        if(!$this->_verifyValidID()) { return FALSE; }

        $id = $this->get_video_id();

        if($this->_video_type == 'youtube') {
            return 'https://www.youtube.com/watch?v=' . $id;
        } else if($this->_video_type == 'vimeo') {
            return 'https://vimeo.com/' . $id;
        }
    }

    /**
     * Return embed url of video
     *
     * @access	public
     * @return	string	video embed url
     */
    public function get_video_embed_url()
    {
        if(!$this->_verifyValidID()) { return FALSE; }

        $id = $this->get_video_id();

        if($this->_video_type == 'youtube') {
            return 'http://www.youtube.com/embed/' . $id . '/';
        } else if($this->_video_type == 'vimeo') {
            return 'http://player.vimeo.com/video/' . $id . '?color=ffffff';
        }
    }

    /**
     * Return Video HTML embed code
     *
     * @access	public
     * @param	number	width
     * @param	number	height
     * @return	string	html code
     */
    public function get_embed_video($width = 500, $height = 280)
    {
        if(!$this->_verifyValidID()) { return FALSE; }

        $id = $this->get_video_id();

        if($this->_video_type == 'youtube') {
            return '<iframe width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $id . '/" frameborder="0" allowfullscreen></iframe>';
        } else if($this->_video_type == 'vimeo') {
            return '<iframe src="http://player.vimeo.com/video/' . $id . '?color=ffffff" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        }
    }

    /**
     * Return Video thumbs
     *
     * @access	public
     * @return	array	thumbs
     */
    public function get_thumbs()
    {
        if(!$this->_verifyValidID()) { return FALSE; }

        $id = $this->get_video_id();

        if($this->_video_type == 'youtube') {
            return array(
                'default' => 'http://img.youtube.com/vi/' . $id . '/default.jpg',
                '0' => 'http://img.youtube.com/vi/' . $id . '/0.jpg',
                '1' => 'http://img.youtube.com/vi/' . $id . '/1.jpg',
                '2' => 'http://img.youtube.com/vi/' . $id . '/2.jpg',
                '3' => 'http://img.youtube.com/vi/' . $id . '/3.jpg'
            );
        } else if($this->_video_type == 'vimeo') {
            $hash = json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $id . '.json'));

            return array(
                '0' => $hash[0]->thumbnail_small,
                '1' => $hash[0]->thumbnail_medium,
                '2' => $hash[0]->thumbnail_large,
                '3' => str_replace("_640.jpg", "_1280.jpg", $hash[0]->thumbnail_large)
            );
        }
    }

    /**
     * Return Video info
     *
     * @access	public
     * @return	array	info
     */
    public function get_video_info()
    {
        if(!$this->_verifyValidID()) { return FALSE; }

        $id = $this->get_video_id();

        if($this->_video_type == 'youtube') {
            $video_info = json_decode(file_get_contents('https://gdata.youtube.com/feeds/api/videos/' . $id . '?v=2&alt=json'));
            $info = $video_info->entry;

            return array(
                'title'         => (isset($info->{'media$group'}->{'media$title'}->{'$t'})) ? $info->{'media$group'}->{'media$title'}->{'$t'} : null,
                'description'   => (isset($info->{'media$group'}->{'media$description'}->{'$t'})) ? $info->{'media$group'}->{'media$description'}->{'$t'} : null,
                'author'        => (isset($info->{'media$group'}->{'media$credit'}[0]->{'yt$display'})) ? $info->{'media$group'}->{'media$credit'}[0]->{'yt$display'} : null,
                'mobile_url'    => 'http://m.youtube.com/watch?v=' . $id,
                'short_url'     => 'http://youtu.be/' . $id,
                'category'      => (isset($info->{'media$group'}->{'media$category'}[0]->label)) ? $info->{'media$group'}->{'media$category'}[0]->label : null,
                'duration'      => (isset($info->{'media$group'}->{'yt$duration'}->seconds)) ? $info->{'media$group'}->{'yt$duration'}->seconds : null,
                'likes'         => (isset($info->{'yt$rating'}->numLikes)) ? $info->{'yt$rating'}->numLikes : null,
                'dislikes'      => (isset($info->{'yt$rating'}->numDislikes)) ? $info->{'yt$rating'}->numDislikes : null,
                'favoriteCount' => (isset($info->{'yt$statistics'}->favoriteCount)) ? $info->{'yt$statistics'}->favoriteCount : null,
                'viewCount'     => (isset($info->{'yt$statistics'}->viewCount)) ? $info->{'yt$statistics'}->viewCount : null,
                'commentsCount' => (isset($info->{'gd$comments'}->{'gd$feedLink'}->countHint)) ? $info->{'gd$comments'}->{'gd$feedLink'}->countHint : null,
                'rating'        => (isset($info->{'gd$rating'}->average)) ? $info->{'gd$rating'}->average : null
            );
        } else if($this->_video_type == 'vimeo') {
            $info = json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $id . '.json'));

            return array(
                'title'                    => (isset($info[0]->title)) ? $info[0]->title : null,
                'description'              => (isset($info[0]->description)) ? $info[0]->description : null,
                'user_name'                => (isset($info[0]->user_name)) ? $info[0]->user_name : null,
                'user_url'                 => (isset($info[0]->user_url)) ? $info[0]->user_url : null,
                'mobile_url'               => (isset($info[0]->mobile_url)) ? $info[0]->mobile_url : null,
                'stats_number_of_likes'    => (isset($info[0]->stats_number_of_likes)) ? $info[0]->stats_number_of_likes : null,
                'stats_number_of_plays'    => (isset($info[0]->stats_number_of_plays)) ? $info[0]->stats_number_of_plays : null,
                'stats_number_of_comments' => (isset($info[0]->stats_number_of_comments)) ? $info[0]->stats_number_of_comments : null,
                'duration'                 => (isset($info[0]->duration)) ? $info[0]->duration : null,
                'tags'                     => (isset($info[0]->tags)) ? $info[0]->tags : null
            );
        }
    }

    /**
     * Get YouTube ID
     *
     * @access	private
     * @param	string	url
     * @return	string	Video ID
     */
    private function _get_youtube_id( $url = '')
    {
        if ( $url === '' ) { return FALSE; }
        if (!$this->_isValidURL( $url )) { return FALSE; }

        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        if(!$matches) { return FALSE; }

        if ( !$this->_isValidID( $matches[0] )) {
            return FALSE;
        } else{
            return $matches[0];
        }
    }

    /**
     * Get Vimeo ID
     *
     * @access	private
     * @param	string	url
     * @return	string	Video ID
     */
    private function _get_vimeo_id( $url = '')
    {
        if ( $url === '' ) { return FALSE; }
        if ($this->_isValidURL( $url )) {
            sscanf(parse_url($url, PHP_URL_PATH), '/%d', $vimeo_id);
        } else {
            $vimeo_id = $url;
        }

        return ($this->_isValidID($vimeo_id,TRUE)) ? $vimeo_id : FALSE;
    }

    /**
     * Verifies if the Video URL is valid
     *
     * @access	private
     * @param	string	url
     * @return	boolean
     */
    private function _isValidURL($url = '')
    {
        return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/i', $url);
    }

    /**
     * Verifies if the Video URL is valid
     *
     * @access	private
     * @param	string	id
     * @param	boolean	vimeo
     * @return	boolean
     */
    private function _isValidID($id = '', $vimeo=FALSE)
    {
        if ($vimeo)
            $headers = get_headers('http://vimeo.com/' . $id);
        else
            $headers = get_headers('http://gdata.youtube.com/feeds/api/videos/' . $id);

        if (!strpos($headers[0], '200')) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Verifies if the saved Video ID is valid
     *
     * @access	private
     * @return	string	id
     */
    private function _verifyValidID() {
        $type = ($this->_video_type == 'vimeo') ? TRUE : FALSE;
        $id = ($this->_isValidID($this->get_video_id(), $type)) ? $this->get_video_id() : FALSE;
        return $id;
    }

}

/* End of file Video.php */