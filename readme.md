# CodeIgniter Library: Internet video (YouTube + Vimeo)

**ci-video**

## About this library

This CodeIgniter's Library is used to get information from YouTube and Vimeo videos.

Its usage is recommended for CodeIgniter 2 or greater.

## Usage

```php
$this->load->library('Video');

$this->video->add_video('VIDEO_URL');

/**
 * Some examples:
 * 
 * http://www.youtube.com/watch?v=JRfuAukYTKg
 * http://youtu.be/JRfuAukYTKg
 * https://vimeo.com/34035823
 * 
 * In any case it must contain the protocol
 **/

$video_id = $this->video->get_video_id();

$video_type = $this->video->get_video_type(); // youtube or vimeo

$video_url = $this->video->get_video_url();

$video_embed_url = $this->video->get_video_embed_url();

$video_embed = $this->video->get_embed_video();

// you can also define the width and height of the embed html code (this is optional)
$video_embed = $this->video->get_embed_video(500, 280);

$video_thumbs = $this->video->get_thumbs(); // Array

$video_info = $this->video->get_video_info(); // Array
```

The thumbs array vary from the vendor:

```
// YouTube Thumbs
array(5) {
  ["default"] => string(49) "http://img.youtube.com/vi/JRfuAukYTKg/default.jpg"
  [0] => string(43) "http://img.youtube.com/vi/JRfuAukYTKg/0.jpg"
  [1] => string(43) "http://img.youtube.com/vi/JRfuAukYTKg/1.jpg"
  [2] => string(43) "http://img.youtube.com/vi/JRfuAukYTKg/2.jpg"
  [3] => string(43) "http://img.youtube.com/vi/JRfuAukYTKg/3.jpg"
}

// Vimeo Thumbs
array(4) {
  [0] => string(50) "http://b.vimeocdn.com/ts/431/559/431559530_100.jpg"
  [1] => string(50) "http://b.vimeocdn.com/ts/431/559/431559530_200.jpg"
  [2] => string(50) "http://b.vimeocdn.com/ts/431/559/431559530_640.jpg"
  [3] => string(51) "http://b.vimeocdn.com/ts/431/559/431559530_1280.jpg"
}
```

The info array vary from the vendor:

```
// YouTube Info
array(13) {
  ["title"] => string(31) "David Guetta - Titanium ft. Sia"
  ["description"] => string(643) "From the album Nothing But The Beat Ultimate (...)"
  ["author"] => string(15) "davidguettavevo"
  ["mobile_url"] => string(40) "http://m.youtube.com/watch?v=JRfuAukYTKg"
  ["short_url"] => string(27) "http://youtu.be/JRfuAukYTKg"
  ["category"] => string(5) "Music"
  ["duration"] => string(3) "246"
  ["likes"] => string(6) "793989"
  ["dislikes"] => string(5) "18993"
  ["favoriteCount"] => string(1) "0"
  ["viewCount"] => string(9) "206439645"
  ["commentsCount"] => int(173222)
  ["rating"] => float(4.9065514)
}

// Vimeo Info
array(10) {
  ["title"] => string(33) "David Guetta Feat. Sia - Titanium"
  ["description"] => string(669) "Music by David Guetta feat. Sia (...)"
  ["user_name"] => string(12) "David Wilson"
  ["user_url"] => string(34) "http://vimeo.com/thisisdavidwilson"
  ["mobile_url"] => string(27) "http://vimeo.com/m/34035823"
  ["stats_number_of_likes"] => int(882)
  ["stats_number_of_plays"] => int(109747)
  ["stats_number_of_comments"] => int(48)
  ["duration"] => int(246)
  ["tags"] => string(87) "David Guetta, Titanium, Sia (...)"
}
```

![Ale Mohamad](http://alemohamad.com/github/logo2012am.png)