<?php

$lor = 0;

// Main Controler LOR-Pages
if (isset($_GET['lor'])) {
    // # doors / imagemap entry: selects current day with JS, after loading all images
    $lor = $_GET['lor'];
    $lor = split(";", $lor); // checking input for a ";"
    if (count($lor) == 1) {
        // parameter without a ";", checking for url-encoded alias of ";"
        $pos = strpos("%3B", $lor);
        if ($pos === true) {
            echo ("Input to big, unwanted access.");
            throw new Exception(404);
        } else {
            // ok now, we use intval / lor to go on
            $lor = $lor[0];
        }
    } else {
        echo ("Input to big, unwanted access.");
        throw new Exception(404);
    }
}

$lor_names = get_lor_names($lor);
$migration_row = get_lor_migration_row($lor);
$citizen_row = get_lor_citizen_row($lor);
$age_row = get_lor_age_row($lor);
// $monitoring_data = 
// $social_data = 

// get inhabitants per age group
$lor_ages = array($age_row['00_01-avg'], $age_row['01_02-avg'], $age_row['02_03-avg'], $age_row['03_05-avg'], $age_row['05_06-avg'], $age_row['06_07-avg'], $age_row['07_08-avg'], $age_row['08_10-avg'], $age_row['10_12-avg'], $age_row['12_14-avg'], $age_row['14_15-avg'], $age_row['15_18-avg'], $age_row['18_21-avg'], $age_row['21_25-avg'], $age_row['25_27-avg'], $age_row['27_30-avg'], $age_row['30_35-avg'], $age_row['35_40-avg'], $age_row['40_45-avg'], $age_row['45_50-avg'], $age_row['50_55-avg'], $age_row['55_60-avg'], $age_row['60_63-avg'], $age_row['63_65-avg'], $age_row['65_67-avg'], $age_row['67_70-avg'], $age_row['70_75-avg'], $age_row['75_80-avg'], $age_row['80_85-avg'], $age_row['85_90-avg'], $age_row['90_95-avg'], $age_row['95_110-avg']);

$berlin_ages = array($age_row['00-01-avg'], $age_row['01-02-avg'], $age_row['02-03-avg'], $age_row['03-05-avg'], $age_row['05-06-avg'], $age_row['06-07-avg'], $age_row['07-08-avg'], $age_row['08-10-avg'], $age_row['10-12-avg'], $age_row['12-14-avg'], $age_row['14-15-avg'], $age_row['15-18-avg'], $age_row['18-21-avg'], $age_row['21-25-avg'], $age_row['25-27-avg'], $age_row['27-30-avg'], $age_row['30-35-avg'], $age_row['35-40-avg'], $age_row['40-45-avg'], $age_row['45-50-avg'], $age_row['50-55-avg'], $age_row['55-60-avg'], $age_row['60-63-avg'], $age_row['63-65-avg'], $age_row['65-67-avg'], $age_row['67-70-avg'], $age_row['70-75-avg'], $age_row['75-80-avg'], $age_row['80-85-avg'], $age_row['85-90-avg'], $age_row['90-95-avg'], $age_row['95-110-avg']);

print '<html><head><title>Die '.$lor_names['lor_name'].' LOR-Seite</title>'
        .'<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15">'
        .'<link rel="stylesheet" type="text/css" href="pages.css"></link>'
        .'<script src="/berlin/libs/raphael-min.js"></script>'
        .'<script src="/berlin/libs/g.raphael-min.js"></script>'
        .'<script src="/berlin/libs/g.line-min.js"></script>'
        .'<script src="/berlin/libs/OpenLayers.js"></script>'
        .'<script src="kml-layer.js"></script>'
        .'<script>'
            .'window.onload = function () {'
                .'var lor_id = '.json_encode($lor).';'
                .'var ages = [1,2,3,5,6,7,8,10,12,14,15,18,21,25,27,30,35,40,45,50,55,60,63,65,67,70,75,80,85,90,95,110];'
                .'var berlin_ages = '.json_encode($berlin_ages).';'
                .'var lor_ages = '.json_encode($lor_ages).';'
                .'var r = Raphael("aging-chart"), txtattr = { font: "14px sans-serif" };'
                .'var chart = r.linechart(15, 30, 700, 270, '
                    .'[ages], [lor_ages, berlin_ages], '
                    .'{smooth: true, axis: "0 0 1 1", symbol: "circle", axisxstep: 100 });' // colors: ["#FF0"]
                .'chart.hoverColumn(function () {'
                    .'this.tags = r.set();'
                    .'for (var i = 0, ii = this.y.length; i < ii; i++) {'
                        . 'this.tags.push(r.tag(this.x, this.y[i], this.values[i], 0, 8)'
                            .'.insertBefore(this).attr([{ fill: "#fff" }, { fill: this.symbols[i].attr("fill") }]));'
                    .'}'
                .'}, function () {'
                    .'this.tags && this.tags.remove();'
                .'});'
                .'chart.symbols.attr({ r: 5 });'
                .'setupMapNavigation("2013/06", lor_id);'
             .'}'
        .'</script>'
      .'</head><body>';


function render_migration_table($dataset) {

    // get meta data
    $lor_id = (int) $dataset['lor_id'];
    $timestamp = (int) $dataset['timestamp'];
    // get migration background data
    $migrants_number = (int) $dataset['migrants'];
    $gs_migrants_number = (int) $dataset['ger_with_migration'];
    $gws_migrants_number = (int) $dataset['ger_without_migration'];
    $inhabitants_overall = (int) $dataset['inhabitants'];
    // calculate migration background data avgs
    $migrants_key = round($migrants_number / $inhabitants_overall * 100, 1);
    $gs_migrants_key = round($gs_migrants_number / $inhabitants_overall * 100, 1);
    $gws_migrants_key = round($gws_migrants_number / $inhabitants_overall * 100, 1);
    // get berlin averages per group (calculated in get_migration_data)
    $b_avg_migrants = $dataset['migrants_avg'];
    $b_avg_ger_migrated = $dataset['ger_with_migration_avg'];
    $b_avg_ger_without = $dataset['ger_without_migration_avg'];

    $tab1 = "";
    $tab1 .= '<h3>1. Tabelle: Melderechtlich registrierte EinwohnerInnen nach Migrationshintergrund* '
        .'(Datenstand: Juni 2013)<a name="migration">&nbsp;</a></h3>';
    $tab1 .= '<table id="migration-data-1"><tr><thead><td>Migrationshintergrund</td><td>Absolute Zahl</td>'
        .'<td>Prozentwert</td><td>Berliner Durchschnitt</td></thead></tr>';
    $tab1 .= '<tr class="buffer"><td colspan="4"></td></tr>';
        $tab1 .= '<tr><td>Ausl&auml;ndische Staatsangeh&ouml;rigkeit</td><td>'.$migrants_number.'</td>'
            .'<td>'.$migrants_key.' %</td><td class="average">'.$b_avg_migrants.' %</td></tr>';
        $tab1 .= '<tr><td>Deutsche mit MHG</td><td>'.$gs_migrants_number.'</td>'
            .'<td>'.$gs_migrants_key.' %</td><td class="average">'.$b_avg_ger_migrated.'%</td></tr>';
        $tab1 .= '<tr><td>Deutsche ohne MHG</td><td>'.$gws_migrants_number.'</td>'
            .'<td>'.$gws_migrants_key.' %</td><td class="average">'.$b_avg_ger_without.'%</td></tr>';
    $tab1 .= '<tr class="average"><td>Gesamtzahl der Bewohner</td><td>'.$inhabitants_overall.'</td>'
        .'<td>&nbsp;</td><td>&nbsp;</td></tr>'
        .'<tr><td colspan="4" class="footer">Quelle: <a '
        .'href="http://www.statistik-berlin-brandenburg.de/home.asp">'
        .'Amt f&uuml;r Statistik Berlin-Brandenburg</a>, Abgestimmter Datenpool Juni 2013<br/></td></tr>';
    $tab1 .= '</table>';

    $tab1 .= '<div class="footer">(*) In der Einwohnerregisterstatistik werden als Personen mit Migrationshintergrund '
        .'ausgewiesen:<ol><li>Ausl&auml;ndische Staatsangeh&ouml;rige</li><li>Deutsche mit Migrationshintergrund<ul>'
        .'<li>Deutsche mit ausl&auml;ndischem Geburtsland oder Einb&uuml;rgerungskennzeichen oder Optionskennzeichen
(im Inland geborene Kinder ausl&auml;ndischer Eltern erhalten seit dem 1. Januar 2000 unter den in &sect; 4
Abs. 3 Staatangeh&ouml;rigkeitsgesetz (StAG) genannten Voraussetzungen zun&auml;chst die deutsche
Staatsangeh&ouml;rigkeit (Optionsregelung)).</li>'
        .'<li>Deutsche unter 18 Jahren ohne eigene Migrationsmerkmale mit ausl&auml;ndischem Geburtsland oder Ein-
b&ouml;rgerungskennzeichen zumindest eines Elternteils, wenn die Person an der Adresse der Eltern/des
Elternteils gemeldet ist.</li></ul></li>'
        .'</ol></div><p>&nbsp;</p>';
    return $tab1;

}

function render_citizen_table($dataset) {

    // get data
    $lor_id = (int) $dataset['lor_id'];
    $timestamp = (int) $dataset['timestamp'];
    $euFive = (int) $dataset['eu-05'];
    $euFifteen = (int) $dataset['eu-15'];
    $euPoland = (int) $dataset['eu-poland'];
    $euSeven = (int) $dataset['eu-2007'];
    $exYugoslavia = (int) $dataset['ex-yugoslavia'];
    $exSowjetunion = (int) $dataset['ex-sowjetunion'];
    $turkyie = (int) $dataset['turkyie'];
    $arabicCountries = (int) $dataset['arabic_countries'];
    $otherCountries = (int) $dataset['other_countries'];
    // $inhabitants = $dataset['inhabitants'];
    $inhabitants = (int) $dataset['inhabitants'];
    $inhabitants_migrated = (int) $dataset['inhabitants_migrated'];
            /** $euFive + $euFifteen + $euPoland + $euSeven + $exYugoslavia + $exSowjetunion + $exTurkyie
        + $arabicCountries + $otherCountries + $unsure; **/
    // keys
    $inhabitants_key = round( $inhabitants_migrated / $inhabitants * 100, 1);
    $euFive_key = round($euFive / $inhabitants * 100, 1);
    $euFifteen_key = round($euFifteen / $inhabitants * 100, 1);
    $euPoland_key = round($euPoland / $inhabitants * 100, 1);
    $arabic_migrants_key = round($arabicCountries / $inhabitants * 100, 1);
    $yugoslavian_migrants_key = round($exYugoslavia / $inhabitants * 100, 1);
    $sowjetunion_migrants_key = round($exSowjetunion / $inhabitants * 100, 1);
    $seven_migrants_key = round($euSeven / $inhabitants * 100, 1);
    $turkyie_key = round($turkyie / $inhabitants * 100, 1);
    $other_migrants_key = round($otherCountries / $inhabitants * 100, 1);
    $unsure_migrants_key = round($unsure / $inhabitants * 100, 1);
    // berlin wide avgs
    $euFive_avg = $dataset['eu-05-avg'];
    $euFifteen_avg = $dataset['eu-15-avg'];
    $euPoland_avg = $dataset['eu-poland-avg'];
    $arabic_migrants_avg = $dataset['arabic_countries-avg'];
    $yugoslavian_migrants_avg = $dataset['ex-yugoslavia-avg'];
    $sowjetunion_migrants_avg = $dataset['ex-sowjetunion-avg'];
    $seven_migrants_avg = $dataset['eu-2007-avg'];
    $turkyie_avg = $dataset['turkyie-avg'];
    $other_countries_avg = $dataset['other_countries-avg'];
    $inhabitants_migrated_avg = $dataset['inhabitants_migrated-avg'];

    $table = "";
    $table .= '<h3>2. Tabelle: Staatsangeh&ouml;rigkeit ausgew&auml;hlter L&auml;nder (Datenstand: Juni 2013)'
        .'<a name="staaten">&nbsp;</a></h3>';
    $table .= 'von insgesamt '.$inhabitants.' BewohnerInnen in diesem Planungsraum';
    $table .= '<table cellspacing="0px" cellpadding="0px" id="migration-data-1"><tr><thead>'
        .'<td>Herkunftsgebiet</td><td>Absolute Zahl</td>'
        .'<td>Prozentwert</td><td>Berliner Durchschnitt</td></thead></tr>';
    $table .= '<tr class="buffer"><td colspan="4"></td></tr>';
        $table .= '<tr><td>Ausl&auml;nderanteil insgesamt</td><td>'.$inhabitants_migrated.'</td>'
            .'<td>'.$inhabitants_key.' %</td><td class="average">'.$inhabitants_migrated_avg.' %</td></tr>';
        $table .= '<tr><td>Arabische L&auml;nder</td><td>'.$arabicCountries.'</td>'
            .'<td>'.$arabic_migrants_key.' %</td><td class="average">'.$arabic_migrants_avg.' %</td></tr>';
        $table .= '<tr><td>Ex-Jugoslavien</td><td>'.$exYugoslavia.'</td>'
            .'<td>'.$yugoslavian_migrants_key.' %</td><td class="average">'.$yugoslavian_migrants_avg.' %</td></tr>';
        $table .= '<tr><td>Russland</td><td>'.$exSowjetunion.'</td>'
            .'<td>'.$sowjetunion_migrants_key.' %</td><td class="average">'.$sowjetunion_migrants_avg.' %</td></tr>';
        $table .= '<tr><td>EU 15 (alt) *</td><td>'.$euFive.'</td>'
            .'<td>'.$euFive_key.' %</td><td class="average">'.$euFive_avg.' %</td></tr>';
        $table .= '<tr><td>EU 16-25 (Erweiterung 2004) **</td><td>'.$euFifteen.'</td>'
            .'<td>'.$euFifteen_key.' %</td><td class="average">'.$euFifteen_avg.' %</td></tr>';
        $table .= '<tr><td>EU 26-27 (Erweiterung 2007) **</td><td>'.$euSeven.'</td>'
            .'<td>'.$seven_migrants_key.' %</td><td class="average">'.$seven_migrants_avg.' %</td></tr>';
        $table .= '<tr><td>Polen</td><td>'.$euPoland.'</td>'
            .'<td>'.$euPoland_key.' %</td><td class="average">'.$euPoland_avg.' %</td></tr>';
        $table .= '<tr><td>T&uuml;rkei</td><td>'.$turkyie.'</td>'
            .'<td>'.$turkyie_key.' %</td><td class="average">'.$turkyie_avg.' %</td></tr>';
        $table .= '<tr><td>&uuml;brige Gebiete</td><td>'.$otherCountries.'</td>'
            .'<td>'.$other_migrants_key.' %</td><td class="average">'.$other_countries_avg.' %</td></tr>'
            .'<tr><td colspan="4" class="footer">Quelle: <a '
            .'href="http://www.statistik-berlin-brandenburg.de/home.asp">'
            .'Amt f&uuml;r Statistik Berlin-Brandenburg</a>, Abgestimmter Datenpool Juni 2013<br/></td></tr>';
    $table .= '<tr class="buffer"><td class="footer" colspan="4">(*) die ersten 15 Mitgliedsl&auml;nder der Europ&auml;ischen '
        .'Union<br/>(**) ohne Polen</td></tr>';
    $table .= '</table>';

    // var_dump($dataset);
    return $table;

}

function render_age_table($dataset, $lor) {

    // get meta data
    $timestamp = (int) $dataset['timestamp'];
    $lor_id = (int) $dataset['lor_id'];
    $inhabitants = (int) $dataset['e_g'];
    $m_inhabitants = (int) $dataset['e_m'];
    $w_inhabitants = (int) $dataset['e_w'];
    // get inhabitants per age group
    $zeroone = (int) $dataset['00_01'];
    $onetwo = (int) $dataset['01_02'];
    $twothree = (int) $dataset['02_03'];
    $threefive = (int) $dataset['03_05'];
    $fivesix = (int) $dataset['05_06'];
    $sixseven = (int) $dataset['06_07'];
    $seveneight = (int) $dataset['07_08'];
    $eightten = (int) $dataset['08_10'];
    $tentwelve = (int) $dataset['10_12'];
    $twelvefourteen = (int) $dataset['12_14'];
    $fourteenfifteen = (int) $dataset['14_15'];
    $eighteen = (int) $dataset['15_18'];
    $twentyone = (int) $dataset['18_21'];
    $twentyfive = (int) $dataset['21_25'];
    $twentyseven = (int) $dataset['25_27'];
    $thirty = (int) $dataset['27_30'];
    $thirtyfive = (int) $dataset['30_35'];
    $fourty = (int) $dataset['35_40'];
    $fourtyfive = (int) $dataset['40_45'];
    $fifty = (int) $dataset['45_50'];
    $fiftyfive = (int) $dataset['50_55'];
    $sixty = (int) $dataset['55_60'];
    $sixtythree = (int) $dataset['60_63'];
    $sixtyfive = (int) $dataset['63_65'];
    $sixtyseven = (int) $dataset['65_67'];
    $seventy = (int) $dataset['67_70'];
    $seventyfive = (int) $dataset['70_75'];
    $eighty = (int) $dataset['75_80'];
    $eightyfive = (int) $dataset['80_85'];
    $ninety = (int) $dataset['85_90'];
    $ninetyfive = (int) $dataset['90_95'];
    $hundredten = (int) $dataset['95_110'];

    $tab1 = "";
    $tab1 .= '<h3>3. Tabelle: Altersverteilung "'.$lor['lor_name'].'", Berlin-'.$lor['district'].' (Datenstand: Juni 2013) '
        .'<a name="altersverteilung">&nbsp;</a></h3>';
    $tab1 .= '<div id="aging-chart"></div><div id="aging-chart-labels">Die X-Achse zeigt die Altersgruppen, die Y-Achse %*'
        .'<div class="lor-bar"></div> in diesem Raum <div class="berlin-bar"></div> im Berliner Durschnitt</div>';
    $tab1 .= '<table id="migration-data-1"><tr><thead><td>Altersgruppe</td><td>Absolute Zahl</td>'
        .'<td>Prozentwert im<br/>LOR*</td><td>Prozentwert im<br/>Berliner Durchschnitt*</td></thead></tr>';
    $tab1 .= '<tr class="buffer"><td colspan="4"></td></tr>';

        $tab1 .= '<tr><td>00-01</td><td>'.$zeroone.'</td>'
            .'<td>'.$dataset['00_01-avg'].' %</td><td class="average">'.$dataset['00-01-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>01-02</td><td>'.$onetwo.'</td>'
            .'<td>'.$dataset['01_02-avg'].' %</td><td class="average">'.$dataset['01-02-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>02-03</td><td>'.$twothree.'</td>'
            .'<td>'.$dataset['02_03-avg'].' %</td><td class="average">'.$dataset['02-03-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>03-05</td><td>'.$threefive.'</td>'
            .'<td>'.$dataset['03_05-avg'].' %</td><td class="average">'.$dataset['03-05-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>05-06</td><td>'.$fivesix.'</td>'
            .'<td>'.$dataset['05_06-avg'].' %</td><td class="average">'.$dataset['05-06-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>06-07</td><td>'.$sixseven.'</td>'
            .'<td>'.$dataset['06_07-avg'].' %</td><td class="average">'.$dataset['06-07-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>07-08</td><td>'.$seveneight.'</td>'
            .'<td>'.$dataset['07_08-avg'].' %</td><td class="average">'.$dataset['07-08-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>08-10</td><td>'.$eightten.'</td>'
            .'<td>'.$dataset['08_10-avg'].' %</td><td class="average">'.$dataset['08-10-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>10-12</td><td>'.$tentwelve.'</td>'
            .'<td>'.$dataset['10_12-avg'].' %</td><td class="average">'.$dataset['10-12-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>12-14</td><td>'.$twelvefourteen.'</td>'
            .'<td>'.$dataset['12_14-avg'].' %</td><td class="average">'.$dataset['12-14-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>14-15</td><td>'.$fourteenfifteen.'</td>'
            .'<td>'.$dataset['14_15-avg'].' %</td><td class="average">'.$dataset['14-15-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>15-18</td><td>'.$eighteen.'</td>'
            .'<td>'.$dataset['15_18-avg'].' %</td><td class="average">'.$dataset['15-18-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>18-21</td><td>'.$twentyone.'</td>'
            .'<td>'.$dataset['18_21-avg'].' %</td><td class="average">'.$dataset['18-21-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>21-25</td><td>'.$twentyfive.'</td>'
            .'<td>'.$dataset['21_25-avg'].' %</td><td class="average">'.$dataset['21-25-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>25-27</td><td>'.$twentyseven.'</td>'
            .'<td>'.$dataset['25_27-avg'].' %</td><td class="average">'.$dataset['25-27-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>27-30</td><td>'.$thirty.'</td>'
            .'<td>'.$dataset['27_30-avg'].' %</td><td class="average">'.$dataset['27-30-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>30-35</td><td>'.$thirtyfive.'</td>'
            .'<td>'.$dataset['30_35-avg'].' %</td><td class="average">'.$dataset['30-35-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>35-40</td><td>'.$fourty.'</td>'
            .'<td>'.$dataset['35_40-avg'].' %</td><td class="average">'.$dataset['35-40-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>40-45</td><td>'.$fourtyfive.'</td>'
            .'<td>'.$dataset['40_45-avg'].' %</td><td class="average">'.$dataset['40-45-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>45-50</td><td>'.$fifty.'</td>'
            .'<td>'.$dataset['45_50-avg'].' %</td><td class="average">'.$dataset['45-50-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>50-55</td><td>'.$fiftyfive.'</td>'
            .'<td>'.$dataset['50_55-avg'].' %</td><td class="average">'.$dataset['50-55-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>55-60</td><td>'.$sixty.'</td>'
            .'<td>'.$dataset['55_60-avg'].' %</td><td class="average">'.$dataset['55-60-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>60-63</td><td>'.$sixtythree.'</td>'
            .'<td>'.$dataset['60_63-avg'].' %</td><td class="average">'.$dataset['60-63-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>63-65</td><td>'.$sixtyfive.'</td>'
            .'<td>'.$dataset['63_65-avg'].' %</td><td class="average">'.$dataset['63-65-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>65-67</td><td>'.$sixtyseven.'</td>'
            .'<td>'.$dataset['65_67-avg'].' %</td><td class="average">'.$dataset['65-67-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>67-70</td><td>'.$seventy.'</td>'
            .'<td>'.$dataset['67_70-avg'].' %</td><td class="average">'.$dataset['67-70-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>70-75</td><td>'.$seventyfive.'</td>'
            .'<td>'.$dataset['70_75-avg'].' %</td><td class="average">'.$dataset['70-75-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>75-80</td><td>'.$eighty.'</td>'
            .'<td>'.$dataset['75_80-avg'].' %</td><td class="average">'.$dataset['75-80-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>80-85</td><td>'.$eightyfive.'</td>'
            .'<td>'.$dataset['80_85-avg'].' %</td><td class="average">'.$dataset['80-85-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>85-90</td><td>'.$ninety.'</td>'
            .'<td>'.$dataset['85_90-avg'].' %</td><td class="average">'.$dataset['85-90-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>90-95</td><td>'.$ninetyfive.'</td>'
            .'<td>'.$dataset['90_95-avg'].' %</td><td class="average">'.$dataset['90-95-avg'].' %</td></tr>';
        $tab1 .= '<tr><td>95-110</td><td>'.$hundredten.'</td>'
            .'<td>'.$dataset['95_110-avg'].' %</td><td class="average">'.$dataset['95-110-avg'].' %</td></tr>';

    $tab1 .= '<tr class="average"><td>Gesamtzahl der Bewohner</td><td>'.$inhabitants.'</td>'
            .'<td>&nbsp;</td><td>&nbsp;</td></tr>'
            .'<tr><td colspan="4" class="footer">Quelle: <a '
            .'href="http://www.statistik-berlin-brandenburg.de/produkte/opendata/einwohnerOD.asp?Kat=6102">'
            .'Open Data Angebot</a>, Amt f&uuml;r Statistik Berlin-Brandenburg<br/></td></tr>'
            .'<tr><td colspan="4" class="footer">(*) Die Prozentwerte im LOR und Berliner Durschnitt sind gemittelt'
            .' je Jahrgang</td></tr>';
    $tab1 .= '</table>';

    $tab1 .= '<div class="footer"></div>';
    return $tab1;

}

function get_lor_migration_row($id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file
    $txt_file    = file_get_contents('data/EWRMIGRA201306_1_Kiezatlas_cleaned_rows.csv');
    $rows        = explode("\n", $txt_file);
    array_shift($rows);

    // return values
    $lorow = array();
    $overall = array('ger_without_migration' => 0.0, 'ger_with_migration' => 0.0, 'migrants' => 0.0);
    $line = 0;
    $startLine = 1;
    $numberOfLors = count($rows) - 1;
    foreach($rows as $row => $data) {
        // getting row data seperated by commas
        if ($data == 0) break;
        $row_data = explode(',', $data);
        $info[$row]['timestamp'] = $row_data[0];
        $info[$row]['lor_id'] = $row_data[1];
        $info[$row]['migrants'] = $row_data[2];
        $info[$row]['ger_with_migration'] = $row_data[3];
        $info[$row]['ger_without_migration'] = $row_data[4];
        $info[$row]['inhabitants'] = $row_data[5];
        if ($line >= $startLine) {
            // calculate migration background data avgs on the base of all lor inhabitants
            $base = $info[$row]['inhabitants'];
            $info[$row]['migrants_avg'] = round($info[$row]['migrants'] / $base * 100, 1);
            $info[$row]['ger_with_migration_avg'] = round($info[$row]['ger_with_migration'] / $base * 100, 1);
            $info[$row]['ger_without_migration_avg'] = round($info[$row]['ger_without_migration'] / $base * 100, 1);
            // accumulating calculated averages over all lors
            $overall['ger_with_migration_avg'] += $info[$row]['ger_with_migration_avg'];
            $overall['ger_without_migration_avg'] += $info[$row]['ger_without_migration_avg'];
            $overall['migrants_avg'] += $info[$row]['migrants_avg'];
        }
        // mark for later return the searched for lor-data-row
        if ($info[$row]['lor_id'] === $id) $lorow = $info[$row];
        $line++;
    }
    // return berlin wide avg-values as a part of the searched for lor-data-row
    $lorow['migrants_avg'] = round($overall['migrants_avg'] / $numberOfLors, 1);
    $lorow['ger_with_migration_avg'] = round($overall['ger_with_migration_avg'] / $numberOfLors, 1);
    $lorow['ger_without_migration_avg'] = round($overall['ger_without_migration_avg'] / $numberOfLors, 1);
    return $lorow;
}

function get_lor_citizen_row($id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file
    $txt_file    = file_get_contents('data/EWR_AUSL_Staat201306xls_clean_head.csv');
    $rows        = explode("\n", $txt_file);
    array_shift($rows);

    // return values
    $lorow = array();
    $overall = array('eu-05-avg' => 0.0, 'eu-15-avg' => 0.0, 'eu-poland-avg' => 0.0, 'eu-2007-avg' => 0.0,
        'ex-yugoslavia-avg' => 0.0, 'eu-sowjetunion-avg' => 0.0, 'eu-turkyie-avg' => 0.0, 
        'eu-arabic-countries-avg' => 0.0, 'other-countries-avg' => 0.0, 'not_sure-avg' => 0.0, 
        'inhabitants_migrated-avg' => 0.0);

    $line = 0;
    $startLine = 1;
    $numberOfLors = count($rows) - 1;
    foreach($rows as $row => $data) {
        // Data structure: Zeit,RAUMID,05 EU15 ohne Deutschland,10 EU Erweiterung 2004 ohne Polen,15 Polen,
        // 20 EU Erweiterung 2007,25 Ehem_ Jugoslawien und Nachfolge,30 Ehem_ Sowjetunion und Nachfolge,35 Türkei,
        // 40 Arabische Staaten,45 Übrige Gebiete,Ausländer,"Einwohner Insgesamt"
        if ($data == 0) break;
        // getting row data seperated by commas
        $row_data = explode(',', $data);
        $info[$row]['timestamp'] = $row_data[0];
        $info[$row]['lor_id'] = $row_data[1];
        $info[$row]['eu-05'] = $row_data[2];
        $info[$row]['eu-15'] = $row_data[3];
        $info[$row]['eu-poland'] = $row_data[4];
        $info[$row]['eu-2007'] = $row_data[5];
        $info[$row]['ex-yugoslavia'] = $row_data[6];
        $info[$row]['ex-sowjetunion'] = $row_data[7];
        $info[$row]['turkyie'] = $row_data[8];
        $info[$row]['arabic_countries'] = $row_data[9];
        $info[$row]['other_countries'] = $row_data[10];
        $info[$row]['inhabitants_migrated'] = $row_data[11];
        $info[$row]['inhabitants'] = $row_data[12];
        // calculate migration background data avgs for groups in relation to all lors inhabitants
        $percentageBase = $info[$row]['inhabitants'];
        // accumulating calculated averages over all lors
        if ($line >= $startLine) {
            $overall['eu-05-avg'] += $info[$row]['eu-05'] / $percentageBase * 100;
            $overall['eu-15-avg'] += $info[$row]['eu-15'] / $percentageBase * 100;
            $overall['eu-poland-avg'] += $info[$row]['eu-poland'] / $percentageBase * 100;
            $overall['eu-2007-avg'] += $info[$row]['eu-2007'] / $percentageBase * 100;
            $overall['ex-yugoslavia-avg'] += $info[$row]['ex-yugoslavia'] / $percentageBase * 100;
            $overall['ex-sowjetunion-avg'] += $info[$row]['ex-sowjetunion'] / $percentageBase * 100;
            $overall['turkyie-avg'] += $info[$row]['turkyie'] / $percentageBase * 100;
            $overall['arabic_countries-avg'] += $info[$row]['arabic_countries'] / $percentageBase * 100;
            $overall['other_countries-avg'] += $info[$row]['other_countries'] / $percentageBase * 100;
            // $overall['not_sure'] += $info[$row]['not_sure-avg'];
            $overall['inhabitants_migrated-avg'] += $info[$row]['inhabitants_migrated'] / $percentageBase * 100;
        }
        // mark for later return the searched for lor-data-row
        if ($info[$row]['lor_id'] === $id) $lorow = $info[$row];
        $line++;
    }
    // return berlin wide avg-values as a part of the searched for lor-data-row
    $lorow['eu-05-avg'] = round($overall['eu-05-avg'] / $numberOfLors, 1);
    $lorow['eu-15-avg'] = round($overall['eu-15-avg'] / $numberOfLors, 1);
    $lorow['eu-poland-avg'] = round($overall['eu-poland-avg'] / $numberOfLors, 1);
    $lorow['eu-2007-avg'] = round($overall['eu-2007-avg'] / $numberOfLors, 1);
    $lorow['ex-yugoslavia-avg'] = round($overall['ex-yugoslavia-avg'] / $numberOfLors, 1);
    $lorow['ex-sowjetunion-avg'] = round($overall['ex-sowjetunion-avg'] / $numberOfLors, 1);
    $lorow['turkyie-avg'] = round($overall['turkyie-avg'] / $numberOfLors, 1);
    $lorow['arabic_countries-avg'] = round($overall['arabic_countries-avg'] / $numberOfLors, 1);
    $lorow['other_countries-avg'] = round($overall['other_countries-avg'] / $numberOfLors, 1);
    // $lorow['not_sure-avg'] += round($overall['not_sure'] / $numberOfLors, 2);
    $lorow['inhabitants_migrated-avg'] = round($overall['inhabitants_migrated-avg'] / $numberOfLors, 1);
    return $lorow;
}

function get_lor_age_row($id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file

    $txt_file    = file_get_contents('data/EWR201306E_Matrix.csv');
    $rows        = explode("\n", $txt_file);
    array_shift($rows);
    // return values
    $lorow = array();
    $overall = array('e_m-avg' => 0.0, 'e_w-avg' => 0.0, '00-01-avg' => 0.0, '01-02-avg' => 0.0, '02-03-avg' => 0.0, 
        '03-05-avg' => 0.0, '05-06-avg' => 0.0, '06-07-avg' => 0.0, '07-08-avg' => 0.0, '08-10-avg' => 0.0, '10-12-avg'
        => 0.0, '12-14-avg' => 0.0, '14-15-avg' => 0.0, '15-18-avg' => 0.0, '18-21-avg' => 0.0, '21-25-avg' => 0.0,
        '25-27-avg' => 0.0, '27-30-avg' => 0.0, '30-35-avg' => 0.0, '35-40-avg' => 0.0, '40-45-avg' => 0.0,
        '45-50-avg' => 0.0, '50-55-avg' => 0.0, '55-60-avg' => 0.0, '65-70-avg' => 0.0, '70-75-avg' => 0.0,
        '75-80-avg' => 0.0, '80-85-avg' => 0.0, '85-90-avg' => 0.0, '90-110-avg' => 0.0);

    $line = 0;
    $startLine = 1;
    $numberOfLors = count($rows) - 1;
    foreach($rows as $row => $data) {
        if ($data == 0) break;
        // ZEIT;RAUMID;BEZ;PGR;BZR;PLR;STADTRAUM;E_E;E_EM;E_EW;E_E00_01;E_E01_02;E_E02_03;E_E03_05;E_E05_06;E_E06_07;
        // E_E07_08;E_E08_10;E_E10_12;E_E12_14;E_E14_15;E_E15_18;E_E18_21;E_E21_25;E_E25_27;E_E27_30;E_E30_35;E_E35_40;
        // E_E40_45;E_E45_50; E_E50_55;E_E55_60;E_E60_63;E_E63_65;E_E65_67;E_E67_70;E_E70_75;E_E75_80;E_E80_85;
        // E_E85_90; E_E90_95;E_E95_110; E_U1;E_1U6;E_6U15;E_15U18;E_18U25;E_25U55;E_55U65;E_65U80;E_80U110
        // getting row data seperated by semicolon
        $row_data = explode(';', $data);
        $info[$row]['timestamp'] = $row_data[0]; $info[$row]['lor_id'] = $row_data[1]; 
        $info[$row]['plr'] = $row_data[5]; $info[$row]['e_g'] = $row_data[7]; $info[$row]['e_m'] = $row_data[8];
        $info[$row]['e_w'] = $row_data[9]; $info[$row]['00_01'] = $row_data[10]; $info[$row]['01_02'] = $row_data[11];
        $info[$row]['02_03'] = $row_data[12]; $info[$row]['03_05'] = $row_data[13]; 
        $info[$row]['05_06'] = $row_data[14]; $info[$row]['06_07'] = $row_data[15]; 
        $info[$row]['07_08'] = $row_data[16]; $info[$row]['08_10'] = $row_data[17];
        $info[$row]['10_12'] = $row_data[18]; $info[$row]['12_14'] = $row_data[19];
        $info[$row]['14_15'] = $row_data[20]; $info[$row]['15_18'] = $row_data[21];
        $info[$row]['18_21'] = $row_data[22]; $info[$row]['21_25'] = $row_data[23];
        $info[$row]['25_27'] = $row_data[24]; $info[$row]['27_30'] = $row_data[25];
        $info[$row]['30_35'] = $row_data[26]; $info[$row]['35_40'] = $row_data[27];
        $info[$row]['40_45'] = $row_data[28]; $info[$row]['45_50'] = $row_data[29];
        $info[$row]['50_55'] = $row_data[30]; $info[$row]['55_60'] = $row_data[31];
        $info[$row]['60_63'] = $row_data[32]; $info[$row]['63_65'] = $row_data[33];
        $info[$row]['65_67'] = $row_data[34]; $info[$row]['67_70'] = $row_data[35];
        $info[$row]['70_75'] = $row_data[36]; $info[$row]['75_80'] = $row_data[37];
        $info[$row]['80_85'] = $row_data[38]; $info[$row]['85_90'] = $row_data[39];
        $info[$row]['90_95'] = $row_data[40]; $info[$row]['95_110'] = $row_data[41];

        // just for the current lor-data-row
        $percentageBase = $info[$row]['e_g']; 
        $info[$row]['e_w-avg'] = round($info[$row]['e_w'] / $percentageBase * 100, 1);
        $info[$row]['e_m-avg'] = round($info[$row]['e_m'] / $percentageBase * 100, 1);
        // print ' male: '.$info[$row]['e_m-avg'].' female: '.$info[$row]['e_w-avg'];
        $info[$row]['00_01-avg'] = round($info[$row]['00_01'] / $percentageBase * 100, 1);
        $info[$row]['01_02-avg'] = round($info[$row]['01_02'] / $percentageBase * 100, 1);
        $info[$row]['02_03-avg'] = round($info[$row]['02_03'] / $percentageBase * 100, 1);
        $info[$row]['03_05-avg'] = round($info[$row]['03_05'] / $percentageBase * 100 / 2, 1);
        $info[$row]['05_06-avg'] = round($info[$row]['05_06'] / $percentageBase * 100, 1);
        $info[$row]['06_07-avg'] = round($info[$row]['06_07'] / $percentageBase * 100, 1);
        $info[$row]['07_08-avg'] = round($info[$row]['07_08'] / $percentageBase * 100, 1);
        $info[$row]['08_10-avg'] = round($info[$row]['08_10'] / $percentageBase * 100 / 2, 1);
        $info[$row]['10_12-avg'] = round($info[$row]['10_12'] / $percentageBase * 100 / 2, 1);
        $info[$row]['12_14-avg'] = round($info[$row]['12_14'] / $percentageBase * 100 / 2, 1);
        $info[$row]['14_15-avg'] = round($info[$row]['14_15'] / $percentageBase * 100, 1);
        $info[$row]['15_18-avg'] = round($info[$row]['15_18'] / $percentageBase * 100 / 3, 1);
        $info[$row]['18_21-avg'] = round($info[$row]['18_21'] / $percentageBase * 100 / 3, 1);
        $info[$row]['21_25-avg'] = round($info[$row]['21_25'] / $percentageBase * 100 / 4, 1);
        $info[$row]['25_27-avg'] = round($info[$row]['25_27'] / $percentageBase * 100 / 3, 1);
        $info[$row]['27_30-avg'] = round($info[$row]['27_30'] / $percentageBase * 100 / 3, 1);
        $info[$row]['30_35-avg'] = round($info[$row]['30_35'] / $percentageBase * 100 / 5, 1);
        $info[$row]['35_40-avg'] = round($info[$row]['35_40'] / $percentageBase * 100 / 5, 1);
        $info[$row]['40_45-avg'] = round($info[$row]['40_45'] / $percentageBase * 100 / 5, 1);
        $info[$row]['45_50-avg'] = round($info[$row]['45_50'] / $percentageBase * 100 / 5, 1);
        $info[$row]['50_55-avg'] = round($info[$row]['50_55'] / $percentageBase * 100 / 5, 1);
        $info[$row]['55_60-avg'] = round($info[$row]['55_60'] / $percentageBase * 100 / 5, 1);
        $info[$row]['60_63-avg'] = round($info[$row]['60_63'] / $percentageBase * 100 / 3, 1);
        $info[$row]['63_65-avg'] = round($info[$row]['63_65'] / $percentageBase * 100 / 2, 1);
        $info[$row]['65_67-avg'] = round($info[$row]['65_67'] / $percentageBase * 100 / 2, 1);
        $info[$row]['67_70-avg'] = round($info[$row]['67_70'] / $percentageBase * 100 / 3, 1);
        $info[$row]['70_75-avg'] = round($info[$row]['70_75'] / $percentageBase * 100 / 5, 1);
        $info[$row]['75_80-avg'] = round($info[$row]['75_80'] / $percentageBase * 100 / 5, 1);
        $info[$row]['80_85-avg'] = round($info[$row]['80_85'] / $percentageBase * 100 / 5, 1);
        $info[$row]['85_90-avg'] = round($info[$row]['85_90'] / $percentageBase * 100 / 5, 1);
        $info[$row]['90_95-avg'] = round($info[$row]['90_95'] / $percentageBase * 100 / 5, 1);
        $info[$row]['95_110-avg'] = round($info[$row]['95_110'] / $percentageBase * 100 / 15, 1);
        // calculate migration background data avgs for groups in relation to all lors inhabitants
        // accumulating calculated averages over all lors
        if ($line >= $startLine) {
            $overall['00-01-avg'] += $info[$row]['00_01'] / $percentageBase * 100;
            $overall['01-02-avg'] += $info[$row]['01_02'] / $percentageBase * 100;
            $overall['02-03-avg'] += $info[$row]['02_03'] / $percentageBase * 100;
            $overall['03-05-avg'] += $info[$row]['03_05'] / $percentageBase * 100 / 2;
            $overall['05-06-avg'] += $info[$row]['05_06'] / $percentageBase * 100;
            $overall['06-07-avg'] += $info[$row]['06_07'] / $percentageBase * 100;
            $overall['07-08-avg'] += $info[$row]['07_08'] / $percentageBase * 100;
            $overall['08-10-avg'] += $info[$row]['08_10'] / $percentageBase * 100 / 2;
            $overall['10-12-avg'] += $info[$row]['10_12'] / $percentageBase * 100 / 2;
            $overall['12-14-avg'] += $info[$row]['12_14'] / $percentageBase * 100 / 2;
            $overall['14-15-avg'] += $info[$row]['14_15'] / $percentageBase * 100;
            $overall['15-18-avg'] += $info[$row]['15_18'] / $percentageBase * 100 / 3;
            $overall['18-21-avg'] += $info[$row]['18_21'] / $percentageBase * 100 / 3;
            $overall['21-25-avg'] += $info[$row]['21_25'] / $percentageBase * 100 / 4;
            $overall['25-27-avg'] += $info[$row]['25_27'] / $percentageBase * 100 / 3;
            $overall['27-30-avg'] += $info[$row]['27_30'] / $percentageBase * 100 / 3;
            $overall['30-35-avg'] += $info[$row]['30_35'] / $percentageBase * 100 / 5;
            $overall['35-40-avg'] += $info[$row]['35_40'] / $percentageBase * 100 / 5;
            $overall['40-45-avg'] += $info[$row]['40_45'] / $percentageBase * 100 / 5;
            $overall['45-50-avg'] += $info[$row]['45_50'] / $percentageBase * 100 / 5;
            $overall['50-55-avg'] += $info[$row]['50_55'] / $percentageBase * 100 / 5;
            $overall['55-60-avg'] += $info[$row]['55_60'] / $percentageBase * 100 / 5;
            $overall['60-63-avg'] += $info[$row]['60_63'] / $percentageBase * 100 / 3;
            $overall['63-65-avg'] += $info[$row]['63_65'] / $percentageBase * 100 / 2;
            $overall['65-67-avg'] += $info[$row]['65_67'] / $percentageBase * 100 / 2;
            $overall['67-70-avg'] += $info[$row]['67_70'] / $percentageBase * 100 / 3;
            $overall['70-75-avg'] += $info[$row]['70_75'] / $percentageBase * 100 / 5;
            $overall['75-80-avg'] += $info[$row]['75_80'] / $percentageBase * 100 / 5;
            $overall['80-85-avg'] += $info[$row]['80_85'] / $percentageBase * 100 / 5;
            $overall['85-90-avg'] += $info[$row]['85_90'] / $percentageBase * 100 / 5;
            $overall['90-95-avg'] += $info[$row]['90_95'] / $percentageBase * 100 / 5;
            $overall['95-110-avg'] += $info[$row]['95_110'] / $percentageBase * 100 / 15;
        }
        // mark for later return the searched for lor-data-row
        if ($info[$row]['lor_id'] === $id) $lorow = $info[$row];
        $line++;
    }
    // return berlin wide avg-values as a part of the searched for lor-data-row
    $lorow['00-01-avg'] += round($overall['00-01-avg'] / $numberOfLors, 1);
    $lorow['01-02-avg'] += round($overall['01-02-avg'] / $numberOfLors, 1);
    $lorow['02-03-avg'] += round($overall['02-03-avg'] / $numberOfLors, 1);
    $lorow['03-05-avg'] += round($overall['03-05-avg'] / $numberOfLors, 1);
    $lorow['05-06-avg'] += round($overall['05-06-avg'] / $numberOfLors, 1);
    $lorow['06-07-avg'] += round($overall['06-07-avg'] / $numberOfLors, 1);
    $lorow['07-08-avg'] += round($overall['07-08-avg'] / $numberOfLors, 1);
    $lorow['08-10-avg'] += round($overall['08-10-avg'] / $numberOfLors, 1);
    $lorow['10-12-avg'] += round($overall['10-12-avg'] / $numberOfLors, 1);
    $lorow['12-14-avg'] += round($overall['12-14-avg'] / $numberOfLors, 1);
    $lorow['14-15-avg'] += round($overall['14-15-avg'] / $numberOfLors, 1);
    $lorow['15-18-avg'] += round($overall['15-18-avg'] / $numberOfLors, 1);
    $lorow['18-21-avg'] += round($overall['18-21-avg'] / $numberOfLors, 1);
    $lorow['21-25-avg'] += round($overall['21-25-avg'] / $numberOfLors, 1);
    $lorow['25-27-avg'] += round($overall['25-27-avg'] / $numberOfLors, 1);
    $lorow['27-30-avg'] += round($overall['27-30-avg'] / $numberOfLors, 1);
    $lorow['30-35-avg'] += round($overall['30-35-avg'] / $numberOfLors, 1);
    $lorow['35-40-avg'] += round($overall['35-40-avg'] / $numberOfLors, 1);
    $lorow['40-45-avg'] += round($overall['40-45-avg'] / $numberOfLors, 1);
    $lorow['45-50-avg'] += round($overall['45-50-avg'] / $numberOfLors, 1);
    $lorow['50-55-avg'] += round($overall['50-55-avg'] / $numberOfLors, 1);
    $lorow['55-60-avg'] += round($overall['55-60-avg'] / $numberOfLors, 1);
    $lorow['60-63-avg'] += round($overall['60-63-avg'] / $numberOfLors, 1);
    $lorow['63-65-avg'] += round($overall['63-65-avg'] / $numberOfLors, 1);
    $lorow['65-67-avg'] += round($overall['65-67-avg'] / $numberOfLors, 1);
    $lorow['67-70-avg'] += round($overall['67-70-avg'] / $numberOfLors, 1);
    $lorow['70-75-avg'] += round($overall['70-75-avg'] / $numberOfLors, 1);
    $lorow['75-80-avg'] += round($overall['75-80-avg'] / $numberOfLors, 1);
    $lorow['80-85-avg'] += round($overall['80-85-avg'] / $numberOfLors, 1);
    $lorow['85-90-avg'] += round($overall['85-90-avg'] / $numberOfLors, 1);
    $lorow['90-95-avg'] += round($overall['90-95-avg'] / $numberOfLors, 1);
    $lorow['95-110-avg'] += round($overall['95-110-avg'] / $numberOfLors, 1);
    return $lorow;
}

function get_lor_names($id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file
    $txt_file    = file_get_contents('data/lor_names_fixed.csv');
    $rows        = explode("\n", $txt_file);
    array_shift($rows);
    foreach($rows as $row => $data) {
        // Data structure: lor_nr,lor_name,bezirk,prognoseraum,bezirksregion,
        if ($data == 0) break;
        // getting row data seperated by commas
        $row_data = explode(',', $data);
        $info[$row]['lor_id'] = $row_data[0];
        $info[$row]['lor_name'] = $row_data[1];
        $info[$row]['district'] = $row_data[2];
        $info[$row]['district_region'] = $row_data[4];
        if ($info[$row]['lor_id'] === $id) return $info[$row];
    }
}

function render_monitoring_data($dataset) {
    return '<h3>Sozialdaten aus dem Monitoring Soziale Stadtentwicklung 2010<a name="monitoring">&nbsp;</a></h3>'
        .'<div class="source">Quelle: Monitoring Soziale Stadtentwicklung 2010, bearbeitet v. Prof. H. '
        .'H&auml;u&szlig;ermann u.a., Berlin Dez. 2010 (i.A. der Senatsverwaltung f&uuml;r Stadtentwicklung Berlin, '
        .'Referat IA) - &copy; f&uuml;r diese Auswertung GskA, Projekt Network (2013)</div><br/><br/>';
}

function render_social_atlas_data($dataset) {
    return '<h3>Sozialdaten aus dem Sozialstrukturatlas 2009<a name="sozialstrukturatlas">&nbsp;</a></h3>'
        .'<div class="source">Quelle: Sozialstrukturatlas 2009, hrsg. v. Prof. Dr. G. Meinlschmidt '
        .'(i.A. der Senatsverwaltung f&uuml;r Gesundheit, Umwelt und Verbraucherschutz) - '
        .'&copy; f&uuml;r diese Auswertung GskA, Projekt Network (2013)</div><br/><br/>';
}

function render_history_list($id) {
    return '<div class="footer">'
        .'<h4>Vergleichszahlen aus den anderen Erhebungszeitr&auml;umen finden Sie hier:</h4>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2014/12" '
        .'href="http://mikromedia.de/berlin/2014/12/?lor='.$id.'">12/2014 (HTML)</a>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2014/06" '
        .'href="http://mikromedia.de/berlin/2014/06/?lor='.$id.'">06/2014 (HTML)</a>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2013/12" '
        .'href="http://mikromedia.de/berlin/2013/12/?lor='.$id.'">12/2013 (HTML)</a>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2012/12" '
        .'href="http://mikromedia.de/berlin/2012/12/?lor='.$id.'">12/2012 (HTML)</a>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2012/06" '
        .'href="http://mikromedia.de/berlin/2012/06/?lor='.$id.'">06/2012 (HTML)</a>'
        .'<a title="Direktlink: zu den Daten dieser LOR-Seite von 2011/12" '
        .'href="http://mikromedia.de/berlin/2011/12/?lor='.$id.'">12/2011 (HTML)</a>'
        .'<a title="Direktlink: PDF (~280 KByte)" alt="Direktlink: PDF (~280 KByte)" '
        .'href="http://mikromedia.de/berlin/analysen_06_2011/'.$id.'.pdf">06/2011 (PDF)</a>'
        .'<a title="Direktlink: PDF (~280 KByte)" alt="Direktlink: PDF (~280 KByte)" '
        .'href="http://mikromedia.de/berlin/analysen_02_2011/'.$id.'.pdf">12/2010 (PDF)</a>'
        .'<a title="Direktlink: PDF (~280 KByte)" alt="Direktlink: PDF (~280 KByte)" '
        .'href="http://mikromedia.de/berlin/analysen_2010/'.$id.'.pdf">2010 (PDF)</a>'
        .'<a title="Direktlink: PDF (~280 KByte)" alt="Direktlink: PDF (~280 KByte)" '
        .'href="http://mikromedia.de/berlin/analysen_2009/'.$id.'.pdf">2009 (PDF)</a>'
        .'<a title="Direktlink: PDF (~280 KByte)" alt="Direktlink: PDF (~280 KByte)" '
        .'href="http://mikromedia.de/berlin/analysen_2008/'.$id.'.pdf">2008 (PDF)</a></div>';

}

function render_city_navigation() {
    return '<h3 id="nav-header">Navigation und geografische Ansicht zu diesem Planungsraum</h3>'
        .' <div id="berlin-citymap"></div><p class="footer" style="padding-left: 60px;">'
        .' <a href="http://www.openstreetmap.org">OpenStreetMap.org</a> Kartenmaterial CC-BY-SA<br/><br/>'
        .' &nbsp;Erweiterte LOR-Geometrie (KML) basiert auf <a href="http://www.statistik-berlin-brandenburg.de/produkte/opendata/geometrienOD.asp?Kat=6301">07/2012</a> CC-BY,  Amt f&uuml;r Statistik Berlin-Brandenburg<br/></p>';
}

// --
// --- Rendering lor-page body
// --


$table1 = render_migration_table($migration_row);
$table2 = render_citizen_table($citizen_row);
$table3 = render_age_table($age_row, $lor_names);
// $monitoring = render_monitoring_data($monitoring_data);
// $social = render_social_atlas_data($social_data);

print '<div class="header">Bev&ouml;lkerungsstruktur im "Lebensweltlich orientierten Planungsraum" (LOR <i>'
    . $lor . '</i>) <b>'.$lor_names['lor_name'].'</b> in der Bezirksregion <b>'
    .$lor_names['district_region'].'</b> des Bezirks <b>Berlin-'.$lor_names['district'].'</b></div>';

print '<h2>Migration, Staatsangeh&ouml;rigkeit und Altersverteilung</h2>';

print $table1;

print $table2;

print $table3;

print render_history_list($lor);

print render_city_navigation();

print $monitoring;

print $social;

print '<div class="footer">'
    .'&copy; 2013 f&uuml;r diese Auswertung <a href="http://www.spinnenwerk.de/network/" '
    .'title="Website: Projekt Network" alt="Projekt Network, GskA">Projekt Network</a>, '
    .'<a href="http://www.gska-berlin.de/index.html" title="Website: GskA Berlin" alt="Website: GskA Berlin">'
    .'GskA Berlin</a><br/><br/>'
    .'Diese LOR-Seiten wurden realisiert in Zusammenarbeit mit dem '
    .'<a href="http://www.mikromedia.de" title="Website: mikromedia.de" '
    .'alt="Website: mikromedia.de">B&uuml;ro f&uuml;r Informationsarbeit</a> und dem <a href="http://www.statistik-berlin-brandenburg.de/">Amt f&uuml;r Statistik Berlin-Brandenburg</a>.<br/><br/></div>';

print '<BODY></HTML>';

?>
