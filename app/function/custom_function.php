<?php


/**
 * Function to format date
 * @param $format
 * @param null $timestamp
 * @param null $echo
 * @return string
 */

function sky_date_french($format, $timestamp = null, $echo = null)
{
    $param_D = array('', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
    $param_l = array('', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    $param_F = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $param_M = array('', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc');
    $return = '';
    if (is_null($timestamp)) {
        $timestamp = mktime();
    }
    for ($i = 0, $len = strlen($format); $i < $len; $i++) {
        switch ($format[$i]) {
            case '\\': // fix.slashes
                $i++;
                $return .= isset($format[$i]) ? $format[$i] : '';
                break;
            case 'D':
                $return .= $param_D[date('N', $timestamp)];
                break;
            case 'l':
                $return .= $param_l[date('N', $timestamp)];
                break;
            case 'F':
                $return .= $param_F[date('n', $timestamp)];
                break;
            case 'M':
                $return .= $param_M[date('n', $timestamp)];
                break;
            default:
                $return .= date($format[$i], $timestamp);
                break;
        }
    }
    if (is_null($echo)) {
        return $return;
    } else {
        echo $return;
    }
}


/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash($name = '', $message = '', $class = 'uk-alert-success')
{
    //We can only do something if the name isn't empty
    if (!empty($name)) {
        //No message, create it
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        }
        //Message exists, display it
        elseif (!empty($_SESSION[$name]) && empty($message)) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : 'uk-alert-success';
            echo '<div class="' . $class . '" uk-alert> <a class="uk-alert-close" uk-close></a> <p>' . $_SESSION[$name] . '</p></div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}




/**
 *
 * Function to paginate query in fronted
 *
 * @param string $pages
 * @param int $range
 *
 *
 */
function kriesi_pagination($pages = '', $range = 4)
{
    $showitems = ($range * 2)+1;

    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }

    if(1 != $pages)
    {
        echo "<ul class=\"uk-pagination uk-flex-center\">";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'><span uk-pagination-previous></span><span uk-pagination-previous></span></a></li>";
        if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'><span uk-pagination-previous></span></a></li>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                echo ($paged == $i)? "<li class=\"uk-active\"><span>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' >".$i."</a></li>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'><span uk-pagination-next></span></a></li>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'><span uk-pagination-next></span><span uk-pagination-next></span></a></li>";
        echo "</ul>\n";
    }
}

/**
 * Function pour afficher le montant avec K et M a la fin
 * @param $val
 * @return float|int|string
 */
function displayMontant($val) {
    if( $val < 1000 ) return $val;
    $val = $val/1000;
    if( $val < 1000 ) return "${val} K";
    $val = $val/1000;
    return "${val} M";
}

/**
 *
 * Function to set number of views in post or page
 * @param $postID
 */

function SetPostViews($postID) {
    $meta_key = 'post_views_count'; //La clef, ou slug, de la méta-donnée
    $count = get_post_meta($postID, $meta_key, true); //Extraction de la valeur, qui est finalement un compteur
    if($count==''): //Si le compte est nul, la méta-donné n'existe pas, on va donc la créer
        $count = 0; //Initialisation à 0
        delete_post_meta($postID, $meta_key); //Simple précaution : si la méta-donnée existait déjà pour un autre usage exotique
        add_post_meta($postID, $meta_key, '1'); //On ajoute la méta-donné
    else:
        $count++; // Si la méta-donnée existe, on l'incrémente
        update_post_meta($postID, $meta_key, $count); //Et on met à jour
    endif;
}

/**
 * @param $date
 */

function date_naiss($date){
    list($jour, $mois, $annee) = preg_split('[/]', $date);
    $today['mois'] = date('n');
    $today['jour'] = date('j');
    $today['annee'] = date('Y');
    $annees = $today['annee'] - $annee;
    if ($today['mois'] <= $mois) {
        if ($mois == $today['mois']) {
            if ($jour > $today['jour'])
                $annees--;
        }
        else
            $annees--;
    }

    echo $annees;
}


function search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }

    return $results;
}


function generateParrainCode($length = 8) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $ret = '';
    for($i = 0; $i < $length; ++$i) {
        $random = str_shuffle($chars);
        $ret .= $random[0];
    }
    return $ret;
}

function random_password($string) {
    $pattern = " ";
    $firstPart = substr(strstr(strtolower($string), $pattern, true), 0, 3);
    $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
    $nrRand = strtolower(generateParrainCode(4));

    $username = trim($firstPart).trim($nrRand).trim($secondPart);
    return $username;
}

function random($car) {
    $string = "";
    $chaine = "1234567890";
    srand((double)microtime()*1000000);
    for($i=0; $i<$car; $i++) {
        $string .= $chaine[rand()%strlen($chaine)];
    }
    return $string;
}

function country () {
    return array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CD" => "Congo, the Democratic Republic of the",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'Ivoire",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and Mcdonald Islands",
        "VA" => "Holy See (Vatican City State)",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran, Islamic Republic of",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "Korea, Democratic People's Republic of",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macao",
        "MK" => "Macedonia, the Former Yugoslav Republic of",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States of",
        "MD" => "Moldova, Republic of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territory, Occupied",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome and Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan, Province of China",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic of",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands, British",
        "VI" => "Virgin Islands, U.s.",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe"
    );
}

function available_symbole(){

    return [
        'ZAR',
        'NGN',
        'KES',
        'UGX',
        'TZS',
        'GHC',
        'XAF',
        'XOF',
        'EGP',
        'ZWD',
        'ZMK',
        'ETB',
        'RWF',
        'MAD',
        'TND',
        'BWP',
        'NAD',
        'USD',
        'EUR',
        'GBP'
    ];
}

function symbole(){

    return array(
        'AED' => '&#1583;.&#1573;', // ?
        'AFN' => '&#65;&#102;',
        'ALL' => '&#76;&#101;&#107;',
        'AMD' => '&#1380;',
        'ANG' => '&#402;',
        'AOA' => '&#75;&#122;', // ?
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => '&#402;',
        'AZN' => '&#8380;',
        'BAM' => '&#75;&#77;',
        'BBD' => '&#36;',
        'BDT' => '&#2547;', // ?
        'BGN' => '&#1083;&#1074;',
        'BHD' => '.&#1583;.&#1576;', // ?
        'BIF' => '&#70;&#66;&#117;', // ?
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => '&#36;&#98;',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTN' => '&#78;&#117;&#46;', // ?
        'BWP' => '&#80;',
        'BYR' => '&#112;&#46;',
        'BZD' => '&#66;&#90;&#36;',
        'CAD' => '&#36;',
        'CDF' => '&#70;&#67;',
        'CHF' => '&#67;&#72;&#70;',
        'CLF' => '&#85;&#70;', // ?
        'CLP' => '&#36;',
        'CNY' => '&#165;',
        'COP' => '&#36;',
        'CRC' => '&#8353;',
        'CUP' => '&#8396;',
        'CVE' => '&#36;', // ?
        'CZK' => '&#75;&#269;',
        'DJF' => '&#70;&#100;&#106;', // ?
        'DKK' => '&#107;&#114;',
        'DOP' => '&#82;&#68;&#36;',
        'DZD' => '&#1583;&#1580;', // ?
        'EGP' => 'E&#163;',
        'ETB' => '&#66;&#114;',
        'EUR' => '&#8364;',
        'FJD' => '&#36;',
        'FKP' => '&#163;',
        'GBP' => '&#163;',
        'GEL' => '&#4314;', // ?
        'GHC' => '&#162;',
        'GIP' => '&#163;',
        'GMD' => '&#68;', // ?
        'GNF' => '&#70;&#71;', // ?
        'GTQ' => '&#81;',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => '&#76;',
        'HRK' => '&#107;&#110;',
        'HTG' => '&#71;', // ?
        'HUF' => '&#70;&#116;',
        'IDR' => '&#82;&#112;',
        'ILS' => '&#8362;',
        'INR' => '&#8377;',
        'IQD' => '&#1593;.&#1583;', // ?
        'IRR' => '&#65020;',
        'ISK' => '&#107;&#114;',
        'JEP' => '&#163;',
        'JMD' => '&#74;&#36;',
        'JOD' => '&#74;&#68;', // ?
        'JPY' => '&#165;',
        'KES' => '&#75;&#83;&#104;', // ?
        'KGS' => '&#1083;&#1074;',
        'KHR' => '&#6107;',
        'KMF' => '&#67;&#70;', // ?
        'KPW' => '&#8361;',
        'KRW' => '&#8361;',
        'KWD' => '&#1583;.&#1603;', // ?
        'KYD' => '&#36;',
        'KZT' => '&#8376;',
        'LAK' => '&#8365;',
        'LBP' => '&#163;',
        'LKR' => '&#8360;',
        'LRD' => '&#36;',
        'LSL' => '&#76;', // ?
        'LTL' => '&#76;&#116;',
        'LVL' => '&#76;&#115;',
        'LYD' => '&#1604;.&#1583;', // ?
        'MAD' => '&#1583;.&#1605;.', //?
        'MDL' => '&#76;',
        'MGA' => '&#65;&#114;', // ?
        'MKD' => '&#1076;&#1077;&#1085;',
        'MMK' => '&#75;',
        'MNT' => '&#8366;',
        'MOP' => '&#77;&#79;&#80;&#36;', // ?
        'MRO' => '&#85;&#77;', // ?
        'MUR' => '&#8360;', // ?
        'MVR' => '.&#1923;', // ?
        'MWK' => '&#77;&#75;',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => '&#77;&#84;',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => '&#67;&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#65020;',
        'PAB' => '&#66;&#47;&#46;',
        'PEN' => '&#83;&#47;&#46;',
        'PGK' => '&#75;', // ?
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PYG' => '&#71;&#115;',
        'QAR' => '&#65020;',
        'RON' => '&#108;&#101;&#105;',
        'RSD' => '&#1044;&#1080;&#1085;&#46;',
        'RUB' => '&#8381;',
        'RWF' => '&#1585;.&#1587;',
        'SAR' => '&#65020;',
        'SBD' => '&#36;',
        'SCR' => '&#8360;',
        'SDG' => '&#163;', // ?
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&#163;',
        'SLL' => '&#76;&#101;', // ?
        'SOS' => '&#83;',
        'SRD' => '&#36;',
        'STD' => '&#68;&#98;', // ?
        'SVC' => '&#36;',
        'SYP' => '&#163;',
        'SZL' => '&#76;', // ?
        'THB' => '&#3647;',
        'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
        'TMT' => '&#109;',
        'TND' => '&#1583;.&#1578;',
        'TOP' => '&#84;&#36;',
        'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => '&#84;&#83;&#104;',
        'UAH' => '&#8372;',
        'UGX' => '&#85;&#83;&#104;',
        'USD' => '&#36;',
        'UYU' => '&#36;&#85;',
        'UZS' => '&#1083;&#1074;',
        'VEF' => '&#66;&#115;',
        'VND' => '&#8363;',
        'VUV' => '&#86;&#84;',
        'WST' => '&#87;&#83;&#36;',
        'XAF' => '&#70;&#67;&#70;&#65;',
        'XCD' => '&#36;',
        'XDR' => '&#83;&#68;&#82;',
        'XOF' => '&#70;&#67;&#70;&#65;',
        'XPF' => '&#70;',
        'YER' => '&#65020;',
        'ZAR' => '&#82;',
        'ZMK' => '&#90;&#75;', // ?
        'ZWD' => '&#90;&#36;',
    );

}

function devise(){

    return [
         "AED" => "United Arab Emirates dirham" ,
         "AFN" => "Afghan afghani" ,
         "ALL" => "Albanian lek" ,
         "AMD" => "Armenian dram" ,
         "ANG" => "Netherlands Antillean guilder" ,
          "AOA"=> "Angolan kwanza" ,
         "ARS"=> "Argentine peso" ,
         "AUD"=>"Australian dollar" ,
        "AWG"=>"Aruban florin" ,
        "AZN"=>"Azerbaijani manat" ,
        "BAM"=>"Bosnia and Herzegovina convertible mark" ,
        "BBD"=>"Barbados dollar" ,
        "BDT"=>"Bangladeshi taka" ,
        "BGN"=>"Bulgarian lev" ,
        "BHD"=>"Bahraini dinar" ,
        "BIF"=>"Burundian franc" ,
        "BMD"=>"Bermudian dollar" ,
        "BND"=>"Brunei dollar" ,
        "BOB"=>"Boliviano" ,
        "BRL"=>"Brazilian real" ,
        "BSD"=>"Bahamian dollar" ,
        "BTN"=>"Bhutanese ngultrum" ,
        "BWP"=>"Botswana pula" ,
        "BYN"=>"New Belarusian ruble" ,
        "BYR"=>"Belarusian ruble" ,
        "BZD"=>"Belize dollar" ,
        "CAD"=>"Canadian dollar" ,
        "CDF"=>"Congolese franc" ,
        "CHF"=>"Swiss franc" ,
        "CLF"=>"Unidad de Fomento" ,
        "CLP"=>"Chilean peso" ,
        "CNY"=>"Renminbi|Chinese yuan" ,
        "COP"=>"Colombian peso" ,
        "CRC"=>"Costa Rican colon",
        "CUC"=>"Cuban convertible peso" ,
        "CUP"=>"Cuban peso" ,
        "CVE"=>"Cape Verde escudo" ,
        "CZK"=>"Czech koruna" ,
        "DJF"=>"Djiboutian franc" ,
        "DKK"=>"Danish krone" ,
        "DOP"=>"Dominican peso" ,
        "DZD"=>"Algerian dinar" ,
        "EGP"=>"Egyptian pound" ,
        "ERN"=>"Eritrean nakfa" ,
        "ETB"=>"Ethiopian birr" ,
        "EUR"=>"Euro" ,
        "FJD"=>"Fiji dollar" ,
        "FKP"=>"Falkland Islands pound" ,
        "GBP"=>"Pound sterling",
        "GEL"=>"Georgian lari" ,
        "GHC"=>"Ghanaian cedi" ,
        "GIP"=>"Gibraltar pound" ,
        "GMD"=>"Gambian dalasi" ,
        "GNF"=>"Guinean franc" ,
        "GTQ"=>"Guatemalan quetzal" ,
        "GYD"=>"Guyanese dollar" ,
        "HKD"=>"Hong Kong dollar" ,
        "HNL"=>"Honduran lempira" ,
        "HRK"=>"Croatian kuna" ,
        "HTG"=>"Haitian gourde" ,
        "HUF"=>"Hungarian forint" ,
        "IDR"=>"Indonesian rupiah" ,
        "ILS"=>"Israeli new shekel",
        "INR"=>"Indian rupee",
        "IQD"=>"Iraqi dinar" ,
        "IRR"=>"Iranian rial" ,
        "ISK"=>"Icelandic króna" ,
        "JMD"=>"Jamaican dollar" ,
        "JOD"=>"Jordanian dinar" ,
        "JPY"=>"Japanese yen" ,
        "KES"=>"Kenyan shilling" ,
        "KGS"=>"Kyrgyzstani som" ,
        "KHR"=>"Cambodian riel" ,
        "KMF"=>"Comoro franc" ,
        "KPW"=>"North Korean won" ,
        "KRW"=>"South Korean won",
        "KWD"=>"Kuwaiti dinar" ,
        "KYD"=>"Cayman Islands dollar" ,
        "KZT"=>"Kazakhstani tenge" ,
        "LAK"=>"Lao kip" ,
        "LBP"=>"Lebanese pound" ,
        "LKR"=>"Sri Lankan rupee" ,
        "LRD"=>"Liberian dollar" ,
        "LSL"=>"Lesotho loti" ,
        "LYD"=>"Libyan dinar" ,
        "MAD"=>"Moroccan dirham" ,
        "MDL"=>"Moldovan leu" ,
        "MGA"=>"Malagasy ariary" ,
        "MKD"=>"Macedonian denar" ,
        "MMK"=>"Myanmar kyat" ,
        "MNT"=>"Mongolian tögrög" ,
        "MOP"=>"Macanese pataca" ,
        "MRO"=>"Mauritanian ouguiya" ,
        "MUR"=>"Mauritian rupee" ,
        "MVR"=>"Maldivian rufiyaa" ,
        "MWK"=>"Malawian kwacha" ,
        "MXN"=>"Mexican peso" ,
        "MXV"=>"Mexican Unidad de Inversion" ,
        "MYR"=>"Malaysian ringgit" ,
        "MZN"=>"Mozambican metical" ,
        "NAD"=>"Namibian dollar" ,
        "NGN"=>"Nigerian naira",
        "NIO"=>"Nicaraguan córdoba" ,
        "NOK"=>"Norwegian krone" ,
        "NPR"=>"Nepalese rupee" ,
        "NZD"=>"New Zealand dollar" ,
        "OMR"=>"Omani rial" ,
        "PAB"=>"Panamanian balboa" ,
        "PEN"=>"Peruvian Sol" ,
        "PGK"=>"Papua New Guinean kina" ,
        "PHP"=>"Philippine peso",
        "PKR"=>"Pakistani rupee" ,
        "PLN"=>"Polish złoty",
        "PYG"=>"Paraguayan guaraní",
        "QAR"=>"Qatari riyal" ,
        "RON"=>"Romanian leu" ,
        "RSD"=>"Serbian dinar" ,
        "RUB"=>"Russian ruble" ,
        "RWF"=>"Rwandan franc" ,
        "SAR"=>"Saudi riyal" ,
        "SBD"=>"Solomon Islands dollar" ,
        "SCR"=>"Seychelles rupee" ,
        "SDG"=>"Sudanese pound" ,
        "SEK"=>"Swedish krona" ,
        "SGD"=>"Singapore dollar" ,
        "SHP"=>"Saint Helena pound" ,
        "SLL"=>"Sierra Leonean leone" ,
        "SOS"=>"Somali shilling" ,
        "SRD"=>"Surinamese dollar" ,
        "SSP"=>"South Sudanese pound" ,
        "STD"=>"São Tomé and Príncipe dobra" ,
        "SVC"=>"Salvadoran colón" ,
        "SYP"=>"Syrian pound" ,
        "SZL"=>"Swazi lilangeni" ,
        "THB"=>"Thai baht",
        "TJS"=>"Tajikistani somoni" ,
        "TMT"=>"Turkmenistani manat" ,
        "TND"=>"Tunisian dinar" ,
        "TOP"=>"Tongan paʻanga" ,
        "TRY"=>"Turkish lira" ,
        "TTD"=>"Trinidad and Tobago dollar" ,
        "TWD"=>"New Taiwan dollar" ,
        "TZS"=>"Tanzanian shilling" ,
        "UAH"=>"Ukrainian hryvnia",
        "UGX"=>"Ugandan shilling" ,
        "USD"=>"United States dollar",
        "UYI"=>"Uruguay Peso en Unidades Indexadas" ,
        "UYU"=>"Uruguayan peso" ,
        "UZS"=>"Uzbekistan som" ,
        "VEF"=>"Venezuelan bolívar" ,
        "VND"=>"Vietnamese đồng" ,
        "VUV"=>"Vanuatu vatu" ,
        "WST"=>"Samoan tala" ,
        "XAF"=>"Central African CFA franc" ,
        "XCD"=>"East Caribbean dollar" ,
        "XOF"=>"West African CFA franc" ,
        "XPF"=>"CFP franc" ,
        "XXX"=>"No currency" ,
        "YER"=>"Yemeni rial" ,
        "ZAR"=>"South African rand" ,
        "ZMK"=>"Zambian kwacha" ,
        "ZWD"=>"Zimbabwean dollar"
    ];
}

function nationalite(){
    return array(
        'fr' => 'Française',
        'ch' => 'Suisse',
        'de' => 'Allemande',
        'be' => 'Belge',
        'it' => 'Italienne',
        'af' => 'Afghane',
        'al' => 'Albanaise',
        'dz' => 'Algerienne',
        'us' => 'Americaine',
        'ad' => 'Andorrane',
        'ao' => 'Angolaise',
        'ag' => 'Antiguaise et barbudienne',
        'ar' => 'Argentine',
        'am' => 'Armenienne',
        'au' => 'Australienne',
        'at' => 'Autrichienne',
        'az' => 'Azerbaïdjanaise',
        'bs' => 'Bahamienne',
        'bh' => 'Bahreinienne',
        'bd' => 'Bangladaise',
        'bb' => 'Barbadienne',
        'bz' => 'Belizienne',
        'bj' => 'Beninoise',
        'bt' => 'Bhoutanaise',
        'by' => 'Bielorusse',
        'mm' => 'Birmane',
        'gw' => 'Bissau-Guinéenne',
        'bo' => 'Bolivienne',
        'ba' => 'Bosnienne',
        'bw' => 'Botswanaise',
        'br' => 'Bresilienne',
        'uk' => 'Britannique',
        'bn' => 'Bruneienne',
        'bg' => 'Bulgare',
        'bf' => 'Burkinabe',
        'bi' => 'Burundaise',
        'kh' => 'Cambodgienne',
        'cm' => 'Camerounaise',
        'ca' => 'Canadienne',
        'cv' => 'Cap-verdienne',
        'cf' => 'Centrafricaine',
        'cl' => 'Chilienne',
        'cn' => 'Chinoise',
        'cy' => 'Chypriote',
        'co' => 'Colombienne',
        'km' => 'Comorienne',
        'cg' => 'Congolaise',
        'cr' => 'Costaricaine',
        'hr' => 'Croate',
        'cu' => 'Cubaine',
        'dk' => 'Danoise',
        'dj' => 'Djiboutienne',
        'do' => 'Dominicaine',
        'dm' => 'Dominiquaise',
        'eg' => 'Egyptienne',
        'ae' => 'Emirienne',
        'gq' => 'Equato-guineenne',
        'ec' => 'Equatorienne',
        'er' => 'Erythreenne',
        'es' => 'Espagnole',
        'tl' => 'Est-timoraise',
        'ee' => 'Estonienne',
        'et' => 'Ethiopienne',
        'fj' => 'Fidjienne',
        'fi' => 'Finlandaise',
        'ga' => 'Gabonaise',
        'gm' => 'Gambienne',
        'ge' => 'Georgienne',
        'gh' => 'Ghaneenne',
        'gd' => 'Grenadienne',
        'gt' => 'Guatemalteque',
        'gn' => 'Guineenne',
        'gf' => 'Guyanienne',
        'ht' => 'Haïtienne',
        'gr' => 'Hellenique',
        'hn' => 'Hondurienne',
        'hu' => 'Hongroise',
        'in' => 'Indienne',
        'id' => 'Indonesienne',
        'iq' => 'Irakienne',
        'ie' => 'Irlandaise',
        'is' => 'Islandaise',
        'il' => 'Israélienne',
        'ci' => 'Ivoirienne',
        'jm' => 'Jamaïcaine',
        'jp' => 'Japonaise',
        'jo' => 'Jordanienne',
        'kz' => 'Kazakhstanaise',
        'ke' => 'Kenyane',
        'kg' => 'Kirghize',
        'ki' => 'Kiribatienne',
        'kn' => 'Kittitienne-et-nevicienne',
        'xk​' => 'Kossovienne',
        'kw' => 'Koweitienne',
        'la' => 'Laotienne',
        'ls' => 'Lesothane',
        'lv' => 'Lettone',
        'lb' => 'Libanaise',
        'lr' => 'Liberienne',
        'ly' => 'Libyenne',
        'li' => 'Liechtensteinoise',
        'lt' => 'Lituanienne',
        'lu' => 'Luxembourgeoise',
        'mk' => 'Macedonienne',
        'my' => 'Malaisienne',
        'mw' => 'Malawienne',
        'mv' => 'Maldivienne',
        'mg' => 'Malgache',
        'ml' => 'Malienne',
        'mt' => 'Maltaise',
        'ma' => 'Marocaine',
        'mh' => 'Marshallaise',
        'mu' => 'Mauricienne',
        'mr' => 'Mauritanienne',
        'mx' => 'Mexicaine',
        'fm' => 'Micronesienne',
        'md' => 'Moldave',
        'mc' => 'Monegasque',
        'mn' => 'Mongole',
        'me' => 'Montenegrine',
        'mz' => 'Mozambicaine',
        'na' => 'Namibienne',
        'nr' => 'Nauruane',
        'nl' => 'Neerlandaise',
        'nz' => 'Neo-zelandaise',
        'np' => 'Nepalaise',
        'ni' => 'Nicaraguayenne',
        'ng' => 'Nigeriane',
        'ne' => 'Nigerienne',
        'kp' => 'Nord-coréenne',
        'no' => 'Norvegienne',
        'om' => 'Omanaise',
        'ug' => 'Ougandaise',
        'uz' => 'Ouzbeke',
        'pk' => 'Pakistanaise',
        'pw' => 'Palau',
        'ps' => 'Palestinienne',
        'pa' => 'Panameenne',
        'pg' => 'Papouane-neoguineenne',
        'py' => 'Paraguayenne',
        'pe' => 'Peruvienne',
        'ph' => 'Philippine',
        'pl' => 'Polonaise',
        'pr' => 'Portoricaine',
        'pt' => 'Portugaise',
        'qa' => 'Qatarienne',
        'ro' => 'Roumaine',
        'ru' => 'Russe',
        'rw' => 'Rwandaise',
        'lc' => 'Saint-Lucienne',
        'sm' => 'Saint-Marinaise',
        'vc' => 'Saint-Vincentaise-et-Grenadine',
        'sb' => 'Salomonaise',
        'sv' => 'Salvadorienne',
        'ws' => 'Samoane',
        'st' => 'Santomeenne',
        'sa' => 'Saoudienne',
        'sn' => 'Senegalaise',
        'rs' => 'Serbe',
        'sc' => 'Seychelloise',
        'sl' => 'Sierra-leonaise',
        'sg' => 'Singapourienne',
        'sk' => 'Slovaque',
        'si' => 'Slovene',
        'so' => 'Somalienne',
        'sd' => 'Soudanaise',
        'lk' => 'Sri-lankaise',
        'za' => 'Sud-africaine',
        'kr' => 'Sud-coréenne',
        'se' => 'Suedoise',
        'sr' => 'Surinamaise',
        'ze' => 'Swazie',
        'sy' => 'Syrienne',
        'tj' => 'Tadjike',
        'tw' => 'Taiwanaise',
        'tz' => 'Tanzanienne',
        'td' => 'Tchadienne',
        'cz' => 'Tcheque',
        'th' => 'Thaïlandaise',
        'tg' => 'Togolaise',
        'ton' => 'Tonguienne',
        'tt' => 'Trinidadienne',
        'tn' => 'Tunisienne',
        'tm' => 'Turkmene',
        'tr' => 'Turque',
        'tv' => 'Tuvaluane',
        'ua' => 'Ukrainienne',
        'uy' => 'Uruguayenne',
        'vu' => 'Vanuatuane',
        've' => 'Venezuelienne',
        'vn' => 'Vietnamienne',
        'ye' => 'Yemenite',
        'zm' => 'Zambienne',
        'zw' => 'Zimbabweenne',
    );
}

function code_country() {
    return [
        'AD'=>array('name'=>'ANDORRA','code'=>'376'),
        'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
        'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
        'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
        'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
        'AL'=>array('name'=>'ALBANIA','code'=>'355'),
        'AM'=>array('name'=>'ARMENIA','code'=>'374'),
        'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
        'AO'=>array('name'=>'ANGOLA','code'=>'244'),
        'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
        'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
        'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
        'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
        'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
        'AW'=>array('name'=>'ARUBA','code'=>'297'),
        'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
        'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
        'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
        'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
        'BE'=>array('name'=>'BELGIUM','code'=>'32'),
        'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
        'BG'=>array('name'=>'BULGARIA','code'=>'359'),
        'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
        'BI'=>array('name'=>'BURUNDI','code'=>'257'),
        'BJ'=>array('name'=>'BENIN','code'=>'229'),
        'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
        'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
        'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
        'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
        'BR'=>array('name'=>'BRAZIL','code'=>'55'),
        'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
        'BT'=>array('name'=>'BHUTAN','code'=>'975'),
        'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
        'BY'=>array('name'=>'BELARUS','code'=>'375'),
        'BZ'=>array('name'=>'BELIZE','code'=>'501'),
        'CA'=>array('name'=>'CANADA','code'=>'1'),
        'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
        'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
        'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
        'CG'=>array('name'=>'CONGO','code'=>'242'),
        'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
        'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
        'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
        'CL'=>array('name'=>'CHILE','code'=>'56'),
        'CM'=>array('name'=>'CAMEROON','code'=>'237'),
        'CN'=>array('name'=>'CHINA','code'=>'86'),
        'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
        'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
        'CU'=>array('name'=>'CUBA','code'=>'53'),
        'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
        'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
        'CY'=>array('name'=>'CYPRUS','code'=>'357'),
        'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
        'DE'=>array('name'=>'GERMANY','code'=>'49'),
        'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
        'DK'=>array('name'=>'DENMARK','code'=>'45'),
        'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
        'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
        'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
        'EC'=>array('name'=>'ECUADOR','code'=>'593'),
        'EE'=>array('name'=>'ESTONIA','code'=>'372'),
        'EG'=>array('name'=>'EGYPT','code'=>'20'),
        'ER'=>array('name'=>'ERITREA','code'=>'291'),
        'ES'=>array('name'=>'SPAIN','code'=>'34'),
        'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
        'FI'=>array('name'=>'FINLAND','code'=>'358'),
        'FJ'=>array('name'=>'FIJI','code'=>'679'),
        'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
        'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
        'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
        'FR'=>array('name'=>'FRANCE','code'=>'33'),
        'GA'=>array('name'=>'GABON','code'=>'241'),
        'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
        'GD'=>array('name'=>'GRENADA','code'=>'1473'),
        'GE'=>array('name'=>'GEORGIA','code'=>'995'),
        'GH'=>array('name'=>'GHANA','code'=>'233'),
        'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
        'GL'=>array('name'=>'GREENLAND','code'=>'299'),
        'GM'=>array('name'=>'GAMBIA','code'=>'220'),
        'GN'=>array('name'=>'GUINEA','code'=>'224'),
        'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
        'GR'=>array('name'=>'GREECE','code'=>'30'),
        'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
        'GU'=>array('name'=>'GUAM','code'=>'1671'),
        'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
        'GY'=>array('name'=>'GUYANA','code'=>'592'),
        'HK'=>array('name'=>'HONG KONG','code'=>'852'),
        'HN'=>array('name'=>'HONDURAS','code'=>'504'),
        'HR'=>array('name'=>'CROATIA','code'=>'385'),
        'HT'=>array('name'=>'HAITI','code'=>'509'),
        'HU'=>array('name'=>'HUNGARY','code'=>'36'),
        'ID'=>array('name'=>'INDONESIA','code'=>'62'),
        'IE'=>array('name'=>'IRELAND','code'=>'353'),
        'IL'=>array('name'=>'ISRAEL','code'=>'972'),
        'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
        'IN'=>array('name'=>'INDIA','code'=>'91'),
        'IQ'=>array('name'=>'IRAQ','code'=>'964'),
        'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
        'IS'=>array('name'=>'ICELAND','code'=>'354'),
        'IT'=>array('name'=>'ITALY','code'=>'39'),
        'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
        'JO'=>array('name'=>'JORDAN','code'=>'962'),
        'JP'=>array('name'=>'JAPAN','code'=>'81'),
        'KE'=>array('name'=>'KENYA','code'=>'254'),
        'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
        'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
        'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
        'KM'=>array('name'=>'COMOROS','code'=>'269'),
        'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
        'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
        'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
        'KW'=>array('name'=>'KUWAIT','code'=>'965'),
        'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
        'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
        'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
        'LB'=>array('name'=>'LEBANON','code'=>'961'),
        'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
        'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
        'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
        'LR'=>array('name'=>'LIBERIA','code'=>'231'),
        'LS'=>array('name'=>'LESOTHO','code'=>'266'),
        'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
        'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
        'LV'=>array('name'=>'LATVIA','code'=>'371'),
        'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
        'MA'=>array('name'=>'MOROCCO','code'=>'212'),
        'MC'=>array('name'=>'MONACO','code'=>'377'),
        'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
        'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
        'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
        'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
        'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
        'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
        'ML'=>array('name'=>'MALI','code'=>'223'),
        'MM'=>array('name'=>'MYANMAR','code'=>'95'),
        'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
        'MO'=>array('name'=>'MACAU','code'=>'853'),
        'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
        'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
        'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
        'MT'=>array('name'=>'MALTA','code'=>'356'),
        'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
        'MV'=>array('name'=>'MALDIVES','code'=>'960'),
        'MW'=>array('name'=>'MALAWI','code'=>'265'),
        'MX'=>array('name'=>'MEXICO','code'=>'52'),
        'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
        'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
        'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
        'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
        'NE'=>array('name'=>'NIGER','code'=>'227'),
        'NG'=>array('name'=>'NIGERIA','code'=>'234'),
        'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
        'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
        'NO'=>array('name'=>'NORWAY','code'=>'47'),
        'NP'=>array('name'=>'NEPAL','code'=>'977'),
        'NR'=>array('name'=>'NAURU','code'=>'674'),
        'NU'=>array('name'=>'NIUE','code'=>'683'),
        'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
        'OM'=>array('name'=>'OMAN','code'=>'968'),
        'PA'=>array('name'=>'PANAMA','code'=>'507'),
        'PE'=>array('name'=>'PERU','code'=>'51'),
        'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
        'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
        'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
        'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
        'PL'=>array('name'=>'POLAND','code'=>'48'),
        'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
        'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
        'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
        'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
        'PW'=>array('name'=>'PALAU','code'=>'680'),
        'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
        'QA'=>array('name'=>'QATAR','code'=>'974'),
        'RO'=>array('name'=>'ROMANIA','code'=>'40'),
        'RS'=>array('name'=>'SERBIA','code'=>'381'),
        'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
        'RW'=>array('name'=>'RWANDA','code'=>'250'),
        'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
        'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
        'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
        'SD'=>array('name'=>'SUDAN','code'=>'249'),
        'SE'=>array('name'=>'SWEDEN','code'=>'46'),
        'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
        'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
        'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
        'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
        'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
        'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
        'SN'=>array('name'=>'SENEGAL','code'=>'221'),
        'SO'=>array('name'=>'SOMALIA','code'=>'252'),
        'SR'=>array('name'=>'SURINAME','code'=>'597'),
        'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
        'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
        'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
        'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
        'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
        'TD'=>array('name'=>'CHAD','code'=>'235'),
        'TG'=>array('name'=>'TOGO','code'=>'228'),
        'TH'=>array('name'=>'THAILAND','code'=>'66'),
        'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
        'TK'=>array('name'=>'TOKELAU','code'=>'690'),
        'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
        'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
        'TN'=>array('name'=>'TUNISIA','code'=>'216'),
        'TO'=>array('name'=>'TONGA','code'=>'676'),
        'TR'=>array('name'=>'TURKEY','code'=>'90'),
        'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
        'TV'=>array('name'=>'TUVALU','code'=>'688'),
        'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
        'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
        'UA'=>array('name'=>'UKRAINE','code'=>'380'),
        'UG'=>array('name'=>'UGANDA','code'=>'256'),
        'US'=>array('name'=>'UNITED STATES','code'=>'1'),
        'UY'=>array('name'=>'URUGUAY','code'=>'598'),
        'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
        'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
        'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
        'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
        'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
        'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
        'VN'=>array('name'=>'VIET NAM','code'=>'84'),
        'VU'=>array('name'=>'VANUATU','code'=>'678'),
        'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
        'WS'=>array('name'=>'SAMOA','code'=>'685'),
        'XK'=>array('name'=>'KOSOVO','code'=>'381'),
        'YE'=>array('name'=>'YEMEN','code'=>'967'),
        'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
        'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
        'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
        'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
    ];
}


function load_request($response) {
    try {
        $json = json_decode( $response['body'] );
    } catch ( Exception $ex ) {
        $json = null;
    }
    return $json;
}

function post_exists_by_slug( $post_slug, $type = 'post') {
    $args_posts = array(
        'post_type'      => $type,
        'name'           => $post_slug,
        'posts_per_page' => 1,
    );

    $loop_posts = new WP_Query( $args_posts );

    if ( ! $loop_posts->have_posts() ) {
        return false;
    } else {
        $loop_posts->the_post();

        return $loop_posts->post->ID;
    }
}
