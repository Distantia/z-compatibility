<?php

class ZCompatibility
{
    const MIN_PHP_VERSION = '5.5.0';
    const MIN_MEMORY_LIMIT = '128M';
    const MIN_POST_MAX_SIZE = '64M';
    const MIN_UPLOAD_MAX_FILESIZE = '64M';
    const MIN_MAX_FILE_UPLOADS = 10;

    protected $requirements = [];
    protected $recommendations = [];

    protected function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.

        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    public function getRequirements()
    {
        $this->addRequirement(
            version_compare(phpversion(), self::MIN_PHP_VERSION, '>='),
            'PHP version must be >= <strong>'.self::MIN_PHP_VERSION.'</strong>, currently is <strong>'.phpversion().'</strong>'
        );

        $this->addRequirement(
            ini_get('date.timezone') != '',
            '<strong>php.ini:date.timezone</strong> must be set '
        );

        $this->addRequirement(
            $this->parse_size(ini_get('memory_limit')) >= $this->parse_size(self::MIN_MEMORY_LIMIT),
            '<strong>php.ini:memory_limit</strong> must be >= <strong>'.self::MIN_MEMORY_LIMIT.'</strong>, currently is <strong>'.ini_get('memory_limit').'</strong>'
        );

        $this->addRequirement(
            $this->parse_size(ini_get('post_max_size')) >= $this->parse_size(self::MIN_POST_MAX_SIZE),
            '<strong>php.ini:post_max_size</strong> must be >= <strong>'.self::MIN_POST_MAX_SIZE.'</strong>, currently is <strong>'.ini_get('post_max_size').'</strong>'
        );

        $this->addRequirement(
            $this->parse_size(ini_get('upload_max_filesize')) >= $this->parse_size(self::MIN_UPLOAD_MAX_FILESIZE),
            '<strong>php.ini:upload_max_filesize</strong> must be >= <strong>'.self::MIN_UPLOAD_MAX_FILESIZE.'</strong>, currently is <strong>'.ini_get('upload_max_filesize').'</strong>'
        );

        $this->addRequirement(
            ini_get('file_uploads') == true,
            '<strong>php.ini:file_uploads</strong> must be enabled'
        );

        $this->addRequirement(
            ini_get('max_file_uploads') >= self::MIN_MAX_FILE_UPLOADS,
            '<strong>php.ini:max_file_uploads</strong> must be >= <strong>'.self::MIN_MAX_FILE_UPLOADS.'</strong>, currently is <strong>'.ini_get('max_file_uploads').'</strong>'
        );

        $this->addRequirement(
            function_exists('bzopen'),
            'Extension <strong>b2z</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('curl_init'),
            'Extension <strong>curl</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('finfo_open'),
            'Extension <strong>fileinfo</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('imagecreatefrompng'),
            'Extension <strong>gd2</strong> must be installed'
        );

        $this->addRequirement(
            class_exists('Locale'),
            'Extension <strong>intl</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('mb_strlen'),
            'Extension <strong>mbstring</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('exif_imagetype'),
            'Extension <strong>exif</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('mysql_query'),
            'Extension <strong>mysql</strong> must be installed'
        );

        $this->addRequirement(
            function_exists('openssl_encrypt'),
            'Extension <strong>openssl</strong> must be installed'
        );

        $this->addRequirement(
            extension_loaded('pdo'),
            'Extension <strong>pdo</strong> must be installed'
        );

        if (extension_loaded('pdo')) {
            $this->addRequirement(
                in_array('mysql', \PDO::getAvailableDrivers()),
                'Extension <strong>pdo_mysql</strong> must be installed'
            );
        }

        return $this->requirements;
    }

    public function getRecommendations()
    {
        $this->addRecommendation(
            false,
            'Default charset is <strong>'.ini_get('default_charset').'</strong>, change if needed'
        );

        $this->addRecommendation(
            false,
            'Verify that you can connect to MySQL'
        );

        $this->addRecommendation(
            false,
            'Verify that required Apache modules are activated: (<strong>auth_basic</strong>, <strong>deflate</strong>, <strong>env</strong>, <strong>expires</strong>, <strong>filter</strong>, <strong>headers</strong>, <strong>mime</strong>, <strong>rewrite</strong>, <strong>setenvif</strong>, <strong>ssl</strong>)'
        );

        $this->addRecommendation(
            !empty($_SERVER['HTTPS']),
            'If necessary, verify that there is an SSL certificate, (running this test under https will hide this recommendation)'
        );

        $this->addRecommendation(
            ini_get('date.timezone') == 'America/Montreal',
            'Timezone should be set to <strong>America/Montreal</strong>'
        );

        return $this->recommendations;
    }

    protected function addRequirement($passed, $message)
    {
        if (!$passed) {
            $this->requirements[] = $message;
        }
    }

    protected function addRecommendation($passed, $message)
    {
        if (!$passed) {
            $this->recommendations[] = $message;
        }
    }
}

$ZCompatibility = new ZCompatibility();

$requirements = $ZCompatibility->getRequirements();
$recommendations = $ZCompatibility->getRecommendations();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Z Compatibility</title>
</head>
<body>
<h1>Z Compatibility</h1>
<h2>Requirements</h2>
<?php
if ($requirements) {
    echo '<ul id="requirements">';

    foreach ($requirements as $requirement) {
        echo '<li>'.$requirement.'</li>';
    }

    echo '</ul>';
}
?>
<h2>Recommendations</h2>
<?php
if ($recommendations) {
    echo '<ul id="recommendations">';

    foreach ($recommendations as $recommendation) {
        echo '<li>'.$recommendation.'</li>';
    }

    echo '</ul>';
}
?>
</body>
</html>
