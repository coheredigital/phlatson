<?php

if (isset($body) && is_string($body) && strlen($body)) {
  $string = $body;
  $body = [];
  $body[] = $string;
} else {
  $body = [];
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux (vers 25 March 2009), see www.w3.org" />
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <meta name="viewport" content="width=device-width" /><!--[if !mso]><!== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /><!--<![endif]-->
  <style type="text/css">
/*<![CDATA[*/
  <?php include '../../FormBuilder/email.css'; ?>
  /*]]>*/
  </style>
</head>
<body>
  <table class="bodytbl" style=
  "width: 100% !important; margin:0 auto; padding:0; background: #eee /*Background Color*/;"
  width="100%" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td background="" align="center">
          <table class="content" width="600" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td align="center">
                  <table width="100%" cellpadding="0" cellspacing="0" class=
                  "column-full">
                    <tbody>
                      <tr height="24">
                        <td class="preheader-wrap" width="100%" align="left" valign=
                        "top">
                          <div class="preheader" style=
                          "color: #e2e2e2; display: none; opacity: 0; margin-left: -10000px;">
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <table width="600" class="100p" cellpadding="0" cellspacing="0" style=
                  "background: #fff;">
                    <tbody>
                      <tr>
                        <td>
                          <table width="100%" class="column-full" cellpadding="0"
                          cellspacing="0">
                            <tbody>
                              <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;"
                                width="100%">
                                  <table class="100p" align="left" height="35" valign=
                                  "middle" width="" cellpadding="0" cellspacing="0">
                                    <tbody>
                                      <tr>
                                        <td class="100p">
                                          <table align="center" valign="middle"
                                          cellpadding="0" cellspacing="0">
                                            <tbody>
                                              <tr>
                                                <td align="center" width="240" valign=
                                                "middle" style=
                                                "padding: 1px 0px 0px 0px;"><a target=
                                                "_top" href=
                                                "https://www.niagarafallstourism.com/?utm_source=newsletter&amp;utm_medium=header">
                                                <img width="240" class="logo-image" src=
                                                "https://newsletters.niagarafallstourism.com/wp-content/uploads/mailster/templates/NFT/img/logo.png"
                                                alt="Niagara Falls Canada" border="0"
                                                label="Logo" /></a></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <table width="600" cellpadding="0" cellspacing="0" class="100p">
                    <tbody>
                      <tr>
                        <td style="padding: 20px; background: #fff;" valign="top" align=
                        "left">
                        <?php if ($subject): ?>
                        <h3 style="font-size: 20px; font-family: Arial, sans-serif; font-weight: bold; padding: 0; padding-bottom: 12px;">
                          <?= $subject ?>
                        </h3>
                        <?php endif; ?>

                        <?php foreach ($body as $string): ?>
                        <p>
                          <?= is_string($string) ? nl2br(htmlentities($string, ENT_QUOTES, 'UTF-8', false)) : ""; ?></p>
                        </p>
                        <?php endforeach; ?>

                        <?php if (count($values)): ?>
                        <table style='width: 100%; border-bottom: 1px solid #ccc;' cellspacing='0'>
                          <?php foreach ($values as $name => $value) : ?>
                          <tr>
                            <th class='label' style=
                            'width: 30%; text-align: right; font-weight: bold; padding: 10px 10px 10px 0; vertical-align: top; border-top: 1px solid #ccc;'>
                            <?= $labels[$name]; ?></th>
                            <td class='value' style=
                            'width: 70%; padding: 10px 0 10px 0; border-top: 1px solid #ccc;'>
                            <?= $value; ?></td>
                          </tr><?php endforeach; ?>
                        </table>
                        <?php endif; ?>

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

          <table style=" font-size: 12px;" align="center" width="600" cellpadding="0"
          cellspacing="0" class="100p">
            <tbody>
              <tr height="12">
                <td></td>
              </tr>

              <tr>
                <td style="padding: 20px" align="center">
                  <table cellpadding="0" cellspacing="0" class="">
                    <tbody>
                      <tr class="columns-half content-inner">
                        <td valign="top" width="600" align="center">
                          <table width="600" cellpadding="0" cellspacing="0" align=
                          "left">
                            <tbody>
                              <tr>
                                <td class="footer-column-content" align="center">
                                  <table width="600" style="max-width: 100%;"
                                  cellpadding="0" cellspacing="0">
                                    <tbody>
                                      <tr style="font-size: 12px;">
                                        <td style=
                                        "font-size: 13px; line-height: 16px;color: #777;"
                                        align="left" valign="middle"><strong style=
                                        "font-weight: bold;">Niagara Falls
                                        Tourism</strong><br />
                                        6815 Stanley Avenue<br />
                                        Niagara Falls ON, Canada L2G 3Y9</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>

              <tr>
                <td style="padding: 10px; font-size: 12px; color: #777;" align="center"
                height="36"><a target="_top" style="color: #bbb;" href=
                "https://www.niagarafallstourism.com/?utm_source=newsletter&amp;utm_medium=copyright">
                Niagara Falls Tourism &#169; <?= date("Y") ?> - All rights reserved</a></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>