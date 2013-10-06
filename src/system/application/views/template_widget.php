<?php header("Content-type: text/html;charset=utf8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">
<head>
<?php
$title = menu_pagetitle();
$title[] = $this->config->item('site_name');
?>
    <title><?php echo implode(' - ', $title); ?></title>
    <link media="all" rel="stylesheet" type="text/css" href="/inc/css/site.css"/>
    <link media="all" rel="stylesheet" type="text/css" href="/inc/css/jquery-ui/jquery-ui-1.7.3.custom.css"/>
    <link media="all" rel="stylesheet" type="text/css" href="/inc/css/jquery-ui/theme/ui.all.css"/>

    <?php if ($css) { ?>
    <link media="all" rel="stylesheet" type="text/css" href="<?php echo $css; ?>"/>
    <?php } ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <script type="text/javascript" src="/inc/js/jquery.js"></script>
    <script type="text/javascript" src="/inc/js/jquery.pause.js"></script>
    <script type="text/javascript" src="/inc/js/jquery-ui.js"></script>
    <script type="text/javascript" src="/inc/js/tinynav.min.js"></script>
    <script type="text/javascript" src="/inc/js/site.js"></script>
    <script type="text/javascript" src="/inc/js/notifications.js" async></script>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
    if (!empty($feedurl)) {
        echo '<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="'.$feedurl.'" />';
    }
    if (isset($reqkey)) { echo "\n\t" . '<script type="text/javascript">var reqk="'.$reqkey.'";</script>'; }
    if (isset($seckey)) { echo "\n\t" . '<script type="text/javascript">var seck="'.$seckey.'";</script>'; }
    ?>
</head>
<body id="page-<?php echo menu_get_current_area(); ?>">
<!--
<div id="ctn">
    <div class="container_12 container">
        <div class="grid_8">
            <div class="main">
-->
                <?php echo $content?>
<!--
            </div>
        </div>
    </div>
</div>
-->
</body>
</html>
