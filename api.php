<?php
if (isset($_REQUEST['app'])) {
    $phps = ['php5', 'php7.0', 'php7.1', 'php7.2', 'php7.3'];
    if (isset($_REQUEST['php'])) {
        if (in_array($_REQUEST['php'], $phps)) {
            $php = "unix:/var/run/php/" . $_REQUEST['php'] . '-fpm.sock';
        } else {
            $php = "unix:/var/run/php/php7.0" . '-fpm.sock';
        }
    }
    $cache = (@$_REQUEST['cache']) ? file_get_contents("templates/cache.txt") : '';
    $gzip = (@$_REQUEST['gzip']) ? file_get_contents("templates/gzip.txt") : '';
    $set_1 = ['#DOMAINS#', '#DOMAIN#', '#ROOT#', '#PHP#', '#CACHE#', '#GZIP#', '#PORT#'];
    $domain = '';
    $domains = $_REQUEST['domains'];
    if ($domains) {
        $domains = explode(" ", $domains);
        $domain = @$domains[0];
        foreach ($domains as $key => $value) {
            if (!preg_match("/www/", $value)) {
                $domains[] = 'www.' . $value;
            }
        }
        $domains = array_values(array_unique($domains));
        $domains = implode(" ", $domains);
    }
    $set_2 = [$domains, $domain, @$_REQUEST['root'], $php, $cache, $gzip, @$_REQUEST['port']];
    $confs = ['laravel', 'wordpress', 'js-front', 'node', 'django', 'durpal', 'php'];
    $type = (@$_REQUEST['type'] == 'sh') ? 'sh' : 'conf';
    if (in_array($_REQUEST['app'], $confs)) {
        $template = str_replace("\r", '', file_get_contents('templates/' . $_REQUEST['app'] . '.txt'));
        $template = str_replace($set_1, $set_2, $template);
        if ($type == 'conf') {
            echo $template;
        } else {
            $params = $_REQUEST;
            unset($params['type']);
            unset($params['cpsession']);
            unset($params['timezone']);
            $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $link = str_replace($_SERVER['QUERY_STRING'], '', $link);
            $link .= http_build_query($params);
            $template = file_get_contents('templates/auto.txt');
            $template = str_replace(['#DOMAIN#', '#ROOT#', '#URL#', "\r"], [$domain, @$_REQUEST['root'], $link, ''], $template);
            if (@$_REQUEST['ssl']) {
                $template = str_replace(["# certbot", '# sudo mkdir -p "/var/www/_letsencrypt"'], ["certbot", 'sudo mkdir -p "/var/www/_letsencrypt"'], $template);
            }
            echo $template;
        }
    }
}
