<?php

$lor = 0;

// Main Controler LOR-JSON Assembler
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
} else if (isset($_GET['agegroup_id'])) { //** isset?? */
    $lor = -1;
    $agegroup_id = $_GET['agegroup_id'];
}


$lor_names = get_lor_names($lor);
$age_row = get_lor_age_row($lor);
$berlin_ages = array($age_row['00-01-avg'], $age_row['01-02-avg'], $age_row['02-03-avg'], $age_row['03-05-avg'], $age_row['05-06-avg'], $age_row['06-07-avg'], $age_row['07-08-avg'], $age_row['08-10-avg'], $age_row['10-12-avg'], $age_row['12-14-avg'], $age_row['14-15-avg'], $age_row['15-18-avg'], $age_row['18-21-avg'], $age_row['21-25-avg'], $age_row['25-27-avg'], $age_row['27-30-avg'], $age_row['30-35-avg'], $age_row['35-40-avg'], $age_row['40-45-avg'], $age_row['45-50-avg'], $age_row['50-55-avg'], $age_row['55-60-avg'], $age_row['60-63-avg'], $age_row['63-65-avg'], $age_row['65-67-avg'], $age_row['67-70-avg'], $age_row['70-75-avg'], $age_row['75-80-avg'], $age_row['80-85-avg'], $age_row['85-90-avg'], $age_row['90-95-avg'], $age_row['95-110-avg']);

function get_age_data($dataset, $lor) {

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

    $meta = '{ "lor_name": "'.$lor['lor_name'].'", "total_inhabitants": '.$inhabitants.', "district": "Berlin-'.$lor['district'].'", "as_of" : "Juni 2014" }';

    $do = '[';
        $do .= '{';
            $do .= '"agegroup": "00-01",';
            $do .= '"absolute_nr": '.$zeroone.',';
            $do .= '"lor_percentage": "'.$dataset['00_01-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['00-01-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "01-02",';
            $do .= '"absolute_nr": '.$onetwo.',';
            $do .= '"lor_percentage": "'.$dataset['01_02-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['01-02-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "02-03",';
            $do .= '"absolute_nr": '.$twothree.',';
            $do .= '"lor_percentage": "'.$dataset['02_03-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['02-03-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "03-05",';
            $do .= '"absolute_nr": '.$threefive.',';
            $do .= '"lor_percentage": "'.$dataset['03_05-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['03-05-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "05-07",';
            $do .= '"absolute_nr": '.$fivesix.',';
            $do .= '"lor_percentage": "'.$dataset['05_06-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['05-06-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "06-07",';
            $do .= '"absolute_nr": '.$sixseven.',';
            $do .= '"lor_percentage": "'.$dataset['06_07-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['06-07-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "07-08",';
            $do .= '"absolute_nr": '.$seveneight.',';
            $do .= '"lor_percentage": "'.$dataset['07_08-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['07-08-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "08-10",';
            $do .= '"absolute_nr": '.$eightten.',';
            $do .= '"lor_percentage": "'.$dataset['08_10-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['08-10-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "10-12",';
            $do .= '"absolute_nr": '.$tentwelve.',';
            $do .= '"lor_percentage": "'.$dataset['10_12-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['10-12-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "12-14",';
            $do .= '"absolute_nr": '.$twelvefourteen.',';
            $do .= '"lor_percentage": "'.$dataset['12_14-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['12-14-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "14-15",';
            $do .= '"absolute_nr": '.$fourteenfifteen.',';
            $do .= '"lor_percentage": "'.$dataset['14_15-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['14-15-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "15-18",';
            $do .= '"absolute_nr": '.$eighteen.',';
            $do .= '"lor_percentage": "'.$dataset['15_18-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['15-18-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "18-21",';
            $do .= '"absolute_nr": '.$twentyone.',';
            $do .= '"lor_percentage": "'.$dataset['18_21-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['18-21-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "21-25",';
            $do .= '"absolute_nr": '.$twentyfive.',';
            $do .= '"lor_percentage": "'.$dataset['21_25-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['21-25-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "25-27",';
            $do .= '"absolute_nr": '.$twentyseven.',';
            $do .= '"lor_percentage": "'.$dataset['25_27-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['25-27-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "27-30",';
            $do .= '"absolute_nr": '.$thirty.',';
            $do .= '"lor_percentage": "'.$dataset['27_30-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['27-30-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "30-35",';
            $do .= '"absolute_nr": '.$thirtyfive.',';
            $do .= '"lor_percentage": "'.$dataset['30_35-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['30-35-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "35-40",';
            $do .= '"absolute_nr": '.$fourty.',';
            $do .= '"lor_percentage": "'.$dataset['35_40-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['35-40-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "40-45",';
            $do .= '"absolute_nr": '.$fourtyfive.',';
            $do .= '"lor_percentage": "'.$dataset['40_45-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['40-45-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "45-50",';
            $do .= '"absolute_nr": '.$fifty.',';
            $do .= '"lor_percentage": "'.$dataset['45_50-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['45-50-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "50-55",';
            $do .= '"absolute_nr": '.$fiftyfive.',';
            $do .= '"lor_percentage": "'.$dataset['50_55-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['50-55-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "55-60",';
            $do .= '"absolute_nr": '.$sixty.',';
            $do .= '"lor_percentage": "'.$dataset['55_60-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['55-60-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "60-63",';
            $do .= '"absolute_nr": '.$sixtythree.',';
            $do .= '"lor_percentage": "'.$dataset['60_63-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['60-63-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "63-65",';
            $do .= '"absolute_nr": '.$sixtyfive.',';
            $do .= '"lor_percentage": "'.$dataset['63_65-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['63-65-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "65-67",';
            $do .= '"absolute_nr": '.$sixtyseven.',';
            $do .= '"lor_percentage": "'.$dataset['65_67-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['65-67-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "67-70",';
            $do .= '"absolute_nr": '.$seventy.',';
            $do .= '"lor_percentage": "'.$dataset['67_70-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['67-70-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "70-75",';
            $do .= '"absolute_nr": '.$seventyfive.',';
            $do .= '"lor_percentage": "'.$dataset['70_75-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['70-75-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "75-80",';
            $do .= '"absolute_nr": '.$eighty.',';
            $do .= '"lor_percentage": "'.$dataset['75_80-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['75-80-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "80-85",';
            $do .= '"absolute_nr": '.$eightyfive.',';
            $do .= '"lor_percentage": "'.$dataset['80_85-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['80-85-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "85-90",';
            $do .= '"absolute_nr": '.$ninety.',';
            $do .= '"lor_percentage": "'.$dataset['85_90-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['85-90-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "90-95",';
            $do .= '"absolute_nr": '.$ninetyfive.',';
            $do .= '"lor_percentage": "'.$dataset['90_95-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['90-95-avg'].'%"';
        $do .= '}, ';
        $do .= '{';
            $do .= '"agegroup": "95-110",';
            $do .= '"absolute_nr": '.$hundredten.',';
            $do .= '"lor_percentage": "'.$dataset['95_110-avg'].'%",';
            $do .= '"city_percentage": "'.$dataset['95-110-avg'].'%"';
        $do .= '}';
    $do .= ']';
    return '{ "meta" : '.$meta. ', "data": '.$do.' }';
}

function get_lor_age_row($id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file

    $txt_file    = file_get_contents('data/EWR201406E_Matrix.csv');
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

function get_all_percentage_values ($agegroup_id) {

    // implementation partially copied from http://stackoverflow.com/questions/5299471/php-parsing-a-txt-file

    $txt_file    = file_get_contents('data/EWR201406E_Matrix.csv');
    $rows        = explode("\n", $txt_file);
    array_shift($rows);

    $line = 0;
    $startLine = 1;
    $numberOfLors = count($rows) - 1;
    $results = array(count($numberOfLors));
    foreach($rows as $row => $data) {
        if ($data == 0) break;

        // 1) --- getting row data seperated by semicolon, building up php-array from csv

        // ZEIT;RAUMID;BEZ;PGR;BZR;PLR;STADTRAUM;E_E;E_EM;E_EW;E_E00_01;E_E01_02;E_E02_03;E_E03_05;E_E05_06;E_E06_07;
        // E_E07_08;E_E08_10;E_E10_12;E_E12_14;E_E14_15;E_E15_18;E_E18_21;E_E21_25;E_E25_27;E_E27_30;E_E30_35;E_E35_40;
        // E_E40_45;E_E45_50; E_E50_55;E_E55_60;E_E60_63;E_E63_65;E_E65_67;E_E67_70;E_E70_75;E_E75_80;E_E80_85;
        // E_E85_90; E_E90_95;E_E95_110; E_U1;E_1U6;E_6U15;E_15U18;E_18U25;E_25U55;E_55U65;E_65U80;E_80U110

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

        // 2) --- calculate and store agegroup valuefor current row
        // just for the current lor-data-row
        $percentageBase = $info[$row]['e_g'];
        $info[$row][$agegroup_id] = round($info[$row][$agegroup_id] / $percentageBase * 100, 1);
        $results[$info[$row]['lor_id']] = $info[$row][$agegroup_id];
    }
    return $results;
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

if ($lor != -1) {

    $string_data = get_age_data($age_row, $lor_names);
    print $string_data;

} else {

    $areas = get_all_percentage_values($agegroup_id);
    $len = count($areas);
    $nr = 1;
    $do = '[';
    foreach($areas as $area => $data) {
        $do .= '{';
            $do .= '"lor_id": "'.$area.'", ';
            $do .= '"value": '.$data;
        $nr++;
        if ($nr === $len) {
            $do .= '}';
            break;
        } else {
            $do .= '}, ';
        }
    }
    $do .= ']';
    print $do;
}


?>
