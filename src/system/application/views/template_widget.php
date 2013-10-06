<?php header("Content-type: text/html;charset=utf8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">

<head>
<?php
$title = menu_pagetitle();
$title[] = $this->config->item('site_name');
?>
    <title><?php echo implode(' - ', $title); ?></title>
    <link media="all" rel="stylesheet" type="text/css" href="/inc/css/jquery-ui/jquery-ui-1.7.3.custom.css"/>
    <link media="all" rel="stylesheet" type="text/css" href="/inc/css/jquery-ui/theme/ui.all.css"/>

    <?php if ($css) { ?>
    <link media="all" rel="stylesheet" type="text/css" href="<?php echo $css; ?>"/>
    <?php } ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <script type="text/javascript" src="/inc/js/jquery.js"></script>
</head>
<body id="page-<?php echo menu_get_current_area(); ?>">
    <?php echo $content?>
</body>
</html>
