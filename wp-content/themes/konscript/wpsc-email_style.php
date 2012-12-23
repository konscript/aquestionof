<?php
/**
 * Template file that applies styling around all emails, like gift wrapping.
 * Content is generated elsewhere, and inserted using ecse_get_email_content()
 */
?>
<html>
    <head>
        <title><?php echo ecse_get_email_subject(); ?></title>
        <style>
        html {
            width:100%; height:100%;
        }
        </style>
    </head>
    <body style="font-family:verdana, sans-serif; font-size:12px; margin:0; width:100%; height:100%;">
        <style>
        body, p, td {
          font-family:verdana, sans-serif;
          font-size:12px;
        }
        </style>
        <table width="100%" cellspacing="0" cellpadding="10" style="background-color: #ffffff; color:#999999; height:100%">
            <tr>
                <td align="center" valign="middle">
                    <h2><a href="<?php echo site_url() ?>" style="color:#999999; text-decoration:none"><img src="<?php echo site_url() ?>/wp-content/themes/konscript/resources/images/wpsc-email-logo.png" alt="<?php bloginfo('name') ?>" /></a></h2>
                    <table width="600" cellspacing="0" cellpadding="50" style="background-color:white; border:solid 1px #CCCCCC; font-size:12px; color:#444444">
                        <tr>
                            <td colspan="2" align="left" style="font-family:verdana; font-size:12px;">
                            <h3 style="text-transform:uppercase;"><?php echo ecse_get_email_subject(); ?></h3>
                            <?php echo ecse_get_email_content(); ?>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <a href="<?php echo site_url() ?>" style="color:#999999; text-decoration:none"><?php echo site_url() ?></a>
                </td>
            </tr>
        </table>
    </body>
</html>