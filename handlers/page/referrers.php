<?php
/*
referrers.php
Copyright (c) 2002, Hendrik Mans <hendrik@mans.de>
Copyright 2002, 2003 David DELON
Copyright 2002, 2003 Charles NEPOTE
Copyright  2003  Eric FELDSTEIN
Copyright  2003  Jean-Pascal MILCENT
All rights reserved.
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
notice, this list of conditions and the following disclaimer in the
documentation and/or other materials provided with the distribution.
3. The name of the author may not be used to endorse or promote products
derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

// Vérification de sécurité
if (!defined("WIKINI_VERSION")) {
    die("acc&egrave;s direct interdit");
}

ob_start();
?>
<div class="page">
<?php
// Valeur par défaut du paramétre "global"
$global = isset($_REQUEST["global"]) && $_REQUEST["global"];
// Si le paramétre "global" a été spécifié
if ($global) {
    $title = str_replace(
        ["{beginLink}","{endLink}"],
        ["<a href=\"{$this->href("referrers_sites", "", "global=1")}\">","</a>"],
        _t('LINK_TO_REFERRERS_SITES')
    );
    $referrers = $this->LoadReferrers();
} else {
    $since = $this->GetConfigValue("referrers_purge_time")
        ? " (".str_replace(
            "{time}",
            $this->GetConfigValue("referrers_purge_time") == 1
                ? _t('REFERRERS_SITES_24_HOURS')
                : str_replace("{nb}", $this->GetConfigValue("referrers_purge_time"), _t('REFERRERS_SITES_X_DAYS')),
            _t('REFERRERS_SITES_SINCE')
        ).")"
        : "";
    $title = str_replace(
        ["{tag}","{since}","{beginLink}","{endLink}"],
        [$this->ComposeLinkToPage($this->GetPageTag()),$since,"<a href=\"{$this->href("referrers_sites")}\">","</a>"],
        _t('LINK_TO_REFERRERS_NO_GLOBAL')
    );
    $referrers = $this->LoadReferrers($this->GetPageTag());
}

echo "<b>$title</b><br /><br />\n" ;
if ($referrers) {
    {
        echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" ;
        foreach ($referrers as $referrer) {
            echo "<tr>" ;
            echo "<td width=\"30\" align=\"right\" valign=\"top\" style=\"padding-right: 10px\">",$referrer["num"],"</td>" ;
            echo "<td valign=\"top\"><a href=\"",htmlspecialchars($referrer["referrer"], ENT_COMPAT, YW_CHARSET),"\">",htmlspecialchars($referrer["referrer"], ENT_COMPAT, YW_CHARSET),"</a></td>" ;
            echo "</tr>\n" ;
        }
        echo "</table>\n" ;
    }
} else {
    echo "<i>Aucune <acronym title=\"Uniform Resource Locator (adresse web)\">URL</acronym> ne fait r&eacute;f&eacute;rence &agrave; cette page.</i><br />\n" ;
}

if ($global) {
    echo "<br />[<a href=\"{$this->href("referrers_sites")}\">".
        str_replace("{tag}", $this->GetPageTag(), _t('LINK_TO_REFERRERS_SITES_ONLY_TAG')).
        "</a> | <a href=\"",$this->href("referrers"),"\">".
        str_replace("{tag}", $this->GetPageTag(), _t('LINK_TO_REFERRERS_SITES_PAGES_ONLY_TAG'))."</a>]" ;
} else {
    echo "<br />[<a href=\"{$this->href("referrers_sites", "", "global=1")}\">"
        ._t('LINK_TO_REFERRERS_ALL_DOMAINS')."</a> | <a href=\"{$this->href("referrers", "", "global=1")}\">" . _t('LINK_TO_REFERRERS_ALL_REFS') . "</a>]" ;
}


?>
</div>
<?php

$content = ob_get_clean();
echo $this->Header();
echo $content;
echo $this->Footer();

?>