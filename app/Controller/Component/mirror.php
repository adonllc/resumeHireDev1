<?php

/*
  --------------------------------------------------------------------------------------------
  Credits: Bit Repository
  Source URL: http://www.bitrepository.com/
  --------------------------------------------------------------------------------------------
 */

/* Image Mirror Class */

class MirrorComponent extends Component {

    var $source_image;
    var $new_image_name;
    var $save_to_folder;

    function make_mirror_image($flip = 1, $original = null, $save_to_folder = null, $newimg = null) {
        $this->source_image = $original;
        $this->save_to_folder = $save_to_folder;
        $this->new_image_name = $newimg;

        $info = GetImageSize($this->source_image);

        if (empty($info)) {
            exit("The file from the requested path doesn't see to be an image");
        }

        $width = $info[0];
        $height = $info[1];

        $mime = $info['mime'];

// What sort of image?

        $type = substr(strrchr($mime, '/'), 1);

        switch ($type) {
            case 'jpeg':
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
                $new_image_ext = 'jpg';
                $quality = 100; // best quality
                break;

            case 'png':
                $image_create_func = 'ImageCreateFromPNG';
                $image_save_func = 'ImagePNG';
                $new_image_ext = 'png';
                $quality = 0; // no compression
                break;

            case 'bmp':
                $image_create_func = 'ImageCreateFromBMP';
                $image_save_func = 'ImageBMP';
                $new_image_ext = 'bmp';
                break;

            case 'gif':
                $image_create_func = 'ImageCreateFromGIF';
                $image_save_func = 'ImageGIF';
                $new_image_ext = 'gif';
                break;

            case 'vnd.wap.wbmp':
                $image_create_func = 'ImageCreateFromWBMP';
                $image_save_func = 'ImageWBMP';
                $new_image_ext = 'bmp';
                break;

            case 'xbm':
                $image_create_func = 'ImageCreateFromXBM';
                $image_save_func = 'ImageXBM';
                $new_image_ext = 'xbm';
                break;

            default:
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
                $new_image_ext = 'jpg';
        }

// Source Image
        $image = $image_create_func($this->source_image);

        $new_image = ImageCreateTrueColor($width, $height);

// Set a White & Transparent Background Color (PHP 4 >= 4.3.2, PHP 5)
        $bg = ImageColorAllocateAlpha($new_image, 255, 255, 255, 127);
        ImageFill($new_image, 0, 0, $bg);

        if ($flip == 1) {
            $dst_y = 0;
            $src_y = 0;

            $coordinate = ($width - 1);

            foreach (range($width, 0) as $range) {
                $src_x = $range;
                $dst_x = $coordinate - $range;

                ImageCopy($new_image, $image, $dst_x, $dst_y, $src_x, $src_y, 1, $height);
            }
        } elseif ($flip == 2) {
            $dst_x = 0;
            $src_x = 0;

            $coordinate = ($height - 1);

            foreach (range($height, 0) as $range) {
                $src_y = $range;
                $dst_y = $coordinate - $range;

                ImageCopy($new_image, $image, $dst_x, $dst_y, $src_x, $src_y, $width, 1);
            }
        }

        if (isSet($this->save_to_folder)) {
            if ($this->new_image_name) {
                $new_name = $this->new_image_name . '.' . $new_image_ext;
            } else {
                $basename = basename($this->source_image);
                $new_name = $this->new_image_name($basename) . '_mirror.' . $new_image_ext;
            }

            $save_path = $this->save_to_folder . $new_name;
        } else {
            /* Set the right header for the image */
            header("Content-Type: " . $mime);

            $save_path = '';
        }

// Show/Save image 

        if (isSet($quality)) {
            $process = $image_save_func($new_image, $save_path, $quality);
        } else {
            $process = $save_path ? $image_save_func($new_image, $save_path) : $image_save_func($new_image);
        }

        return array('result' => $process, 'new_file_path' => $save_path);
    }

    function new_image_name($filename) {
        $ext = strrchr($filename, ".");

        if ($ext) {
            $strlen = strlen($ext);
            $filename = basename(substr($filename, 0, -$strlen));
        }

        $string = trim($filename);
        $string = strtolower($string);
        $string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
        $string = ereg_replace("[ \t\n\r]+", "_", $string);
        $string = str_replace(" ", '_', $string);
        $string = ereg_replace("[ _]+", "_", $string);

        return $string;
    }

    function center($newpath = null, $original = null, $out_w = null, $out_h = null, $time = null) {

        $error = false;
        if (!file_exists($original)) {
            throw new InvalidArgumentException('File "' . $original . '" not found.');
        }

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $src = imagecreatefromjpeg($original);
                break;
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                throw new InvalidArgumentException('File "' . $original . '" is not valid jpg, png or gif image.');
                break;
        }

        list($fabricbasic_w, $fabricbasic_h, $basicsrc_type) = getimagesize($original);


        if (!($out = imagecreatetruecolor($out_w, $out_h))) {
            $error = 'There was an error creating your true color image (gif).';
        }

        $white = imagecolorallocate($out, 255, 255, 255);
        imagefill($out, 0, 0, $white);

        $basicoutfile = $newpath . 'center-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $centeroutfilename = 'center-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        list($src_w, $src_h, $src_type) = getimagesize($original);

        $kp = '1';
        $top_margin = '0';
        $out_w = $out_w;
        $curr_x = 0;
        $i = '1';
        $out_width = $out_w;

        $xoffset = ($out_w - $src_w) / 2;
        $yoffset = ($out_h - $src_h) / 2;


        imagecopy($out, $src, $xoffset, $yoffset, 0, 0, $src_w, $src_h);


        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($out, $basicoutfile, 100);
                break;
            case 'png':
                imagepng($out, $basicoutfile);
                break;
            case 'gif':
                imagegif($out, $basicoutfile);
                break;
        }

        imagedestroy($src);
        imagedestroy($out);
        return $centeroutfilename;
    }

    function basic($newpath = null, $original = null, $out_w = null, $out_h = null, $time = null) {

        $error = false;
        if (!file_exists($original)) {
            throw new InvalidArgumentException('File "' . $original . '" not found.');
        }
        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $src = imagecreatefromjpeg($original);
                break;
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                throw new InvalidArgumentException('File "' . $original . '" is not valid jpg, png or gif image.');
                break;
        }

        list($fabricbasic_w, $fabricbasic_h, $basicsrc_type) = getimagesize($original);

        if (!($out = imagecreatetruecolor($out_w, $out_h))) {
            $error = 'There was an error creating your true color image (gif).';
        }

        $basicoutfile = $newpath . 'basic-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $basicoutfilename = 'basic-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        list($src_w, $src_h, $src_type) = getimagesize($original);

        $kp = '1';
        $top_margin = '0';
        $out_w = $out_w;
        $curr_x = 0;
        $i = '1';
        $out_width = $out_w;

        for ($main = 0; $main <= round($out_h / $src_h) + '2'; $main++) {
            $top = $top_margin - round($fabricbasic_h / 2) - '35';
            $curr_x = 0;
            $out_width = $out_w;
            while ($curr_x < $out_width) {
                imagecopy($out, $src, $curr_x, $top, 0, 0, $src_w, $src_h);
                $curr_x += $src_w;
            }
            $top_margin = $top_margin + $fabricbasic_h;
            $kp++;
        }

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($out, $basicoutfile, 100);
                break;
            case 'png':
                imagepng($out, $basicoutfile);
                break;
            case 'gif':
                imagegif($out, $basicoutfile);
                break;
        }

        imagedestroy($src);
        imagedestroy($out);
        return $basicoutfilename;
    }

    function mirrorrepeat($newpath = null, $original = null, $out_w = null, $out_h = null, $time = null) {

        $error = false;
        if (!file_exists($original)) {
            throw new InvalidArgumentException('File "' . $original . '" not found.');
        }
        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $src = imagecreatefromjpeg($original);
                break;
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                throw new InvalidArgumentException('File "' . $original . '" is not valid jpg, png or gif image.');
                break;
        }

        list($fabricbasic_w, $fabricbasic_h, $basicsrc_type) = getimagesize($original);

        if (!($out = imagecreatetruecolor($out_w, $out_h))) {
            $error = 'There was an error creating your true color image (gif).';
        }

        $mirroroutfile = $newpath . 'mirror-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $mirroroutfilename = 'mirror-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $this->make_mirror_image('1', $original, $newpath, 'filp_a_' . $time);
        $original2 = $newpath . 'filp_a_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $this->make_mirror_image('2', $original2, $newpath, 'filp_c_' . $time);
        $this->make_mirror_image('1', $original2, $newpath, 'filp_d_' . $time);
        $this->make_mirror_image('2', $original, $newpath, 'filp_b_' . $time);

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $image_v = imagecreatefromjpeg($newpath . 'filp_d_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_w = imagecreatefromjpeg($newpath . 'filp_a_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_x = imagecreatefromjpeg($newpath . 'filp_b_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_y = imagecreatefromjpeg($newpath . 'filp_c_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                break;
            case 'png':
                $image_v = imagecreatefrompng($newpath . 'filp_d_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_w = imagecreatefrompng($newpath . 'filp_a_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_x = imagecreatefrompng($newpath . 'filp_b_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_y = imagecreatefrompng($newpath . 'filp_c_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                break;
            case 'gif':
                $image_v = imagecreatefromgif($newpath . 'filp_d_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_w = imagecreatefromgif($newpath . 'filp_a_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_x = imagecreatefromgif($newpath . 'filp_b_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                $image_y = imagecreatefromgif($newpath . 'filp_c_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION)));
                break;
        }


        list($src_w, $src_h, $src_type) = getimagesize($original);
        $kp = '1';
        $top_margin = '0';
        $out_w = $out_w;
        $curr_x = 0;
        $out_width = $out_w;

        for ($main = 0; $main <= round($out_h / $src_h) + '2'; $main++) {
            $top = $top_margin - round($fabricbasic_h / 2);
            $curr_x = 0;
            $out_width = $out_w;
            $i = '0';
            while ($curr_x < $out_width) {
                if ($kp % 2 != '0') {
                    if ($i % 2 == '0') {
                        imagecopy($out, $image_v, $curr_x, $top, 0, 0, $src_w, $src_h);
                    } else {
                        imagecopy($out, $image_w, $curr_x, $top, 0, 0, $src_w, $src_h);
                    }
                } else {
                    if ($i % 2 == '0') {
                        imagecopy($out, $image_x, $curr_x, $top, 0, 0, $src_w, $src_h);
                    } else {
                        imagecopy($out, $image_y, $curr_x, $top, 0, 0, $src_w, $src_h);
                    }
                }
                $curr_x += $src_w;
                $i++;
            }
            $top_margin = $top_margin + $fabricbasic_h;
            $kp++;
        }

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($out, $mirroroutfile, 100);
                break;
            case 'png':
                imagepng($out, $mirroroutfile);
                break;
            case 'gif':
                imagegif($out, $mirroroutfile);
                break;
        }

        imagedestroy($out);
        imagedestroy($src);
        return $mirroroutfilename;
    }

    function halfdrop($newpath = null, $original = null, $out_w = null, $out_h = null, $time = null) {

        $error = false;
        list($fabricbasic_w, $fabricbasic_h, $basicsrc_type) = getimagesize($original);

        if (!file_exists($original)) {
            throw new InvalidArgumentException('File "' . $original . '" not found.');
        }
        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $src = imagecreatefromjpeg($original);
                break;
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                throw new InvalidArgumentException('File "' . $original . '" is not valid jpg, png or gif image.');
                break;
        }

        if (!($out = imagecreatetruecolor($out_w, $out_h))) {
            $error = 'There was an error creating your true color image (gif).';
        }

        $halfdropoutfile = $newpath . 'half-drop_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $halfdropoutfilename = 'half-drop_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $top = '0';
        list($src_w, $src_h, $src_type) = getimagesize($original);

        $kp = '1';
        $top_margin = '0';
        $top_margin2 = '0';
        $out_w = $out_w;
        $curr_x = 0;
        $out_width = $out_w;

        for ($main = 0; $main <= round($out_h / $src_h) + '2'; $main++) {
            $top = $top_margin - round($fabricbasic_h / 2);
            $top2 = $top_margin2 - round($fabricbasic_h / 2);
            $curr_x = 0;
            $out_width = $out_w;
            $i = '0';
            while ($curr_x < $out_width) {
                if ($i % 2 != '0') {
                    imagecopy($out, $src, $curr_x, $top2 + '20', 0, 0, $src_w, $src_h);
                } else {
                    imagecopy($out, $src, $curr_x, $top2, 0, 0, $src_w, $src_h);
                }
                $curr_x += $src_w;
                $i++;
            }
            $top_margin = $top_margin + $fabricbasic_h;
            $top_margin2 = $top_margin2 + $fabricbasic_h;
            $kp++;
        }

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($out, $halfdropoutfile, 100);
                break;
            case 'png':
                imagepng($out, $halfdropoutfile);
                break;
            case 'gif':
                imagegif($out, $halfdropoutfile);
                break;
        }
        imagedestroy($src);
        imagedestroy($out);
        return $halfdropoutfilename;
    }

    function halfbrick($newpath = null, $original = null, $out_w = null, $out_h = null, $time = null) {

        $error = false;

        list($fabricbasic_w, $fabricbasic_h, $basicsrc_type) = getimagesize($original);

        if (!file_exists($original)) {
            throw new InvalidArgumentException('File "' . $original . '" not found.');
        }
        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $src = imagecreatefromjpeg($original);
                break;
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                throw new InvalidArgumentException('File "' . $original . '" is not valid jpg, png or gif image.');
                break;
        }
        if (!($out = imagecreatetruecolor($out_w, $out_h))) {
            $error = 'There was an error creating your true color image (gif).';
        }

        $halfoutfile = $newpath . 'half-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $halfoutfilename = 'half-brick_' . $time . '.' . strtolower(pathinfo($original, PATHINFO_EXTENSION));

        $top = '0';
        list($src_w, $src_h, $src_type) = getimagesize($original);

        $kp = '1';
        $top_margin = '0';
        $left_margin = '0';

        $kp = '0';

        for ($main = 0; $main <= round($out_h / $src_h) + '2'; $main++) {
            $top = $top_margin - round($fabricbasic_h / 2);
            $left = $left_margin - round($fabricbasic_w / 2);

            //echo $kp . '<br />';
            if ($kp % 2 == '0') {
                // echo $top . '<br />';
                $out_width = $out_w;
                $curr_x = 0;
                while ($curr_x < $out_width) {
                    imagecopy($out, $src, $curr_x, $top, 0, 0, $src_w, $src_h);
                    $curr_x += $src_w;
                }
            } else {
                // echo $top . '<br />';
                $curr_x = 0;
                $out_width = $out_w - $left;
                while ($curr_x < $out_width) {
                    imagecopy($out, $src, $curr_x + $left, $top, 0, 0, $src_w, $src_h);
                    $curr_x += $src_w;
                }
            }
            $top_margin = $top_margin + $fabricbasic_h;

            $kp++;
        }

        switch (strtolower(pathinfo($original, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($out, $halfoutfile, 100);
                break;
            case 'png':
                imagepng($out, $halfoutfile);
                break;
            case 'gif':
                imagegif($out, $halfoutfile);
                break;
        }

        imagedestroy($src);
        imagedestroy($out);
        return $halfoutfilename;
    }

}

?>
