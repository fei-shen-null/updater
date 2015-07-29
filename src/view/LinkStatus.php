
<h5>WPN-XM Software Registry - Status<span class="pull-right"><?=date(DATE_RFC822)?></span></h5>
<h5>Components (<?=count($registry)?>)</h5>
<table class="table table-condensed table-hover" style="font-size: 12px; width: 1480px;">
<tr><th>Software Component</th><th>Version</th><th>Download URL<br/>(local wpnxm-software-registry.php)</th>
<th>Forwarding URL<br/>(server wpnxm-software-registry.php)</th></tr>

<?php

function renderTd($url)
{
    $color = isAvailable($url) === true ? 'green' : 'red';

    return '<td><a style="color:' . $color . ';" href="' . $url . '">' . $url . '</a></td>';
}

function isAvailable($url)
{
    global $urlsHttpStatus;
    // special handling for googlecode (now dl.google.com), because they don't like /HEAD requests via curl
    if (false !== strpos($url, 'google') or
        false !== strpos($url, 'github') or
        false !== strpos($url, 'microsoft')) {
        return (bool) StatusRequest::getHttpStatusCode($url);
    }

    return $urlsHttpStatus[$url];
}

// test latest version links (and not every version url)
// test forwarding links

foreach ($registry as $software => $keys) {
    echo '<tr><td style="padding: 1px 5px;"><b>' . $software . '</b></td>';

    // if software is a PHP Extension, we have a latest version with URLs for multiple PHP versions
    if (strpos($software, 'phpext_') !== false) {
        $bitsizes    = $keys['latest']['url'];
        $skipFirstTd = true;
        foreach ($bitsizes as $bitsize => $phpversions) {
            foreach ($phpversions as $phpversion => $url) {
                if ($skipFirstTd === false) {
                    echo '<td>&nbsp;</td>';
                } else {
                    $skipFirstTd = false;
                }
                echo '<td>' . $keys['latest']['version'] . ' - ' . $phpversion . ' - ' . $bitsize . '</td>' . renderTd($url);
                echo renderTd('http://wpn-xm.org/get.php?s=' . $software . '&p=' . $phpversion . '&bitsize=' . $bitsize);
                echo '</tr>';
            }
        }
    } else {
        echo '<td>' . $keys['latest']['version'] . '</td>' . renderTd($keys['latest']['url']);
        echo renderTd('http://wpn-xm.org/get.php?s=' . $software);
        echo '</tr>';
    }
}
?>
</table>
Used a total of <?=$crawlingTime?> seconds for crawling <?=$numberOfUrls?> URLs. Total Page Build Time <?=round((microtime(true) - TIME_STARTED), 2)?> secs.
