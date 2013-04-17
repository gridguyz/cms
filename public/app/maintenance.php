<?php

header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
header( 'Status: 503 Service Temporarily Unavailable' );
header( 'Retry-After: 3600' );
header( 'Content-type: text/html; charset=utf-8' );
header( 'Expires: -1' );
header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
header( 'Pragma: no-cache' );
header( 'Connection: close' );
header( 'X-Zork-Maintenance: 1' );

$q           = 0;
$lang        = 'en';
$acceptLangs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
$availLangs  = array( 'en', 'fr', 'nl', 'de', 'es', 'hu', 'ro' );

if ( ! empty( $acceptLangs ) )
{
    foreach ( $acceptLangs as $accept )
    {
        if ( strstr( $accept, ';q=' ) )
        {
            list( $al, $aq ) = explode( ';q=', $accept );
            $aq = (float) $aq;
        }
        else
        {
            $al = $accept;
            $aq = 1.0;
        }

        if ( $aq > $q )
        {
            $al = strstr( $al, '-', true );

            if ( in_array( $al, $availLangs ) )
            {
                $q = $aq;
                $lang = $al;
            }
        }
    }
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Expires" content="-1" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="googlebot" content="noindex, nofollow, noarchive" />
        <title>Maintenance work</title>
        <style type="text/css">
        /* <![CDATA[ */
        html, body
        {
            margin: 0px;
            padding: 0px;
            height: 100%;
            font-size: 14px;
            font-family: Tahoma;
            background: transparent;
        }

        #boxes
        {
            clear: both;
            position: relative;
        }

        #boxes .maintenanceBox
        {
            top: 0px;
            left: 50%;
            width: 553px;
            padding: 5px;
            position: absolute;
            margin-left: -280px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            background-color: #f5f6f9;
            border-radius: 3px;
            -ms-border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            text-shadow: 0px 0px 2px #fff;
            box-shadow: 0px 0px 5px #ccc;
            -ms-box-shadow: 0px 0px 5px #ccc;
            -moz-box-shadow: 0px 0px 5px #ccc;
            -webkit-box-shadow: 0px 0px 5px #ccc;
        }

        #boxes .maintenanceBox h1
        {
            color: #fff;
            margin: -6px;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 0px;
            background-color: #4e7dee;
            border-radius: 3px;
            -ms-border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            text-shadow: 0px 0px 2px #444;
        }

        #boxes .maintenanceBox p
        {
            color: #5c5c5c;
            font-size: 16px;
            margin-left: 5px;
            margin-right: 5px;
        }

        #boxes .maintenanceBox p.signature
        {
            color: #000000;
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
            font-style: italic;
            padding-right: 10px;
        }

        #boxes.en .maintenanceBox,
        #boxes.fr .maintenanceBox,
        #boxes.nl .maintenanceBox,
        #boxes.de .maintenanceBox,
        #boxes.es .maintenanceBox,
        #boxes.hu .maintenanceBox,
        #boxes.ro .maintenanceBox
        {
            opacity: 0;
            -moz-transition: 1s opacity;
            -webkit-transition: 1s opacity;
            -o-transition-property: opacity;
            -o-transition-duration: 1s;
            filter: alpha(opacity=0);
        }

        #boxes.en .maintenanceBox.en,
        #boxes.fr .maintenanceBox.fr,
        #boxes.nl .maintenanceBox.nl,
        #boxes.de .maintenanceBox.de,
        #boxes.es .maintenanceBox.es,
        #boxes.hu .maintenanceBox.hu,
        #boxes.ro .maintenanceBox.ro
        {
            opacity: 1;
            -moz-transition: 1s opacity;
            -webkit-transition: 1s opacity;
            -o-transition-property: opacity;
            -o-transition-duration: 1s;
            filter: alpha(opacity=100);
        }

        ul.langs
        {
            padding: 0px;
            display: block;
            text-align: center;
            margin: 60px auto 15px;
        }

        ul.langs li
        {
            display: inline;
            padding-left: 15px;
            padding-right: 15px;
            border-left: 1px dotted #333333;
        }

        ul.langs li:first-child
        {
            border-left: none;
        }

        ul.langs li a
        {
            color: #186ac4;
            text-decoration: none;
        }

        .header
        {
            height: 140px;
            background-repeat: repeat-x;
            background-position: top left;
            background-image: url("/images/maintenance/bg_top.png");
        }

        .content
        {
            height: 100%;
            width: 100%;
            position: absolute;
            background-repeat: repeat-x;
            background-position: bottom left;
            background-image: url("/images/maintenance/bg_bottom.png");
        }

        .logo
        {
            top: 30px;
            left: 50%;
            position: absolute;
            margin-left: -92px;
        }
        /* ]]> */
        </style>
        <script type="text/javascript">
        // <![CDATA[
        function setLang(lang)
        {
            document.getElementById('boxes').className = '' + lang;
            return false;
        }
        // ]]>
        </script>
    </head>
    <body>
        <div class="content">
            <div class="header">
                <div class="logo">&nbsp;</div>
            </div>
            <ul class="langs">
                <li><a onclick="return setLang('en');" href="#">English</a></li>
                <li><a onclick="return setLang('fr');" href="#">Français</a></li>
                <li><a onclick="return setLang('nl');" href="#">Nederlands</a></li>
                <li><a onclick="return setLang('de');" href="#">Deutsch</a></li>
                <li><a onclick="return setLang('es');" href="#">Español</a></li>
                <li><a onclick="return setLang('hu');" href="#">Magyar</a></li>
                <li><a onclick="return setLang('ro');" href="#">Română</a></li>
            </ul>
            <div id="boxes" class="<?php echo $lang; ?>">
                <div class="maintenanceBox en">
                    <h1>English</h1>
                    <p>Web Site is temporarily unavailable due to maintenance work or due to heavy traffic.</p>
                    <p>Please check back in a few minutes.</p>
                    <p class="signature">Grid's support team is sorry for the inconvenience.</p>
                </div>
                <div class="maintenanceBox fr">
                    <h1>Français</h1>
                    <p>Site Web est temporairement indisponible dû à des travaux d'entretien ou en raison de trafic sur le site.</p>
                    <p>Vérifiez de nouveau dans quelques minutes.</p>
                    <p class="signature">L'équipe support de Grid est désolé pour cet inconvénient.</p>
                </div>
                <div class="maintenanceBox nl">
                    <h1>Nederlands</h1>
                    <p>Deze web site kan op dit moment niet bereikt worden, wegens onderhoudswerken of te druk verkeer op de website.</p>
                    <p>Gelieve het binnen enkele ogenblikken nog eens te proberen.</p>
                    <p class="signature">Het Grid support team verontschuldigd zich voor deze ongemakken.</p>
                </div>
                <div class="maintenanceBox de">
                    <h1>Deutsch</h1>
                    <p>Web-Site ist vorübergehend nicht verfügbar wegen Wartungsarbeiten oder wegen schwere Datenverkehr auf der Website.</p>
                    <p>Bitte versuchen Sie es in ein paar Minuten noch einmal.</p>
                    <p class="signature">Das Grid Team bedauert die mögliche Unannehmlichkeiten.</p>
                </div>
                <div class="maintenanceBox es">
                    <h1>Español</h1>
                    <p>Sitio Web no está disponible temporalmente debido a trabajos de mantenimiento o el tráfico en el sitio.</p>
                    <p>Por favor, compruebe de nuevo.</p>
                    <p class="signature">El equipo de apoyo de Grid disculpe las molestias.</p>
                </div>
                <div class="maintenanceBox hu">
                    <h1>Magyar</h1>
                    <p>Az oldal karbantartási munkálatok miatt jelenleg nem elérhető.</p>
                    <p>Kérjük, látogasson vissza néhány perc múlva.</p>
                    <p class="signature">A Grid csapata elnézést kér az okozott kellemetlenségekért.</p>
                </div>
                <div class="maintenanceBox ro">
                    <h1>Română</h1>
                    <p>Situl momentan nu este disponibil din motive de intretinere.</p>
                    <p>Va rugam sa reveniti in cateva minute.</p>
                    <p class="signature">Echipa Grid va cere scuze pentru aceste neplaceri.</p>
                </div>
            </div>
        </div>
    </body>
</html>
