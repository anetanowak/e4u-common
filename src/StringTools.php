<?php
namespace E4u\Common;

class StringTools
{
    /**
     * @param  string $name
     * @return string
     */
    public static function wolacz($name)
    {
        $vocativeRules = array
        (
            'a' => array
            (
                'świnia' => 'świnio',
                'śka' => 'śko',
                'sia' => 'siu',
                'zia' => 'ziu',
                'cia' => 'ciu',
                'nia' => 'niu',
                'aja' => 'aju',
                'lla' => 'llo',
                'ila' => 'ilo',
                'ula' => 'ulo',
                'nela' => 'nelo',
                'bela' => 'belo',
                'iela' => 'ielo',
                'mela' => 'melo',
                'la' => 'lu',
                'a' => 'o',
            ),
            'b' => array
            (
                'b' => 'bie',
            ),
            'c' => array
            (
                'ojciec' => 'ojcze',
                'starzec' => 'starcze',
                'ciec' => 'ćcu',
                'liec' => 'łcu',
                'niec' => 'ńcu',
                'siec' => 'ścu',
                'ziec' => 'źcu',
                'lec' => 'lcu',
                'c' => 'cu',
            ),
            'ć' => array
            (
                'gość' => 'gościu',
                'ść' => 'ściu',
                'ć' => 'cio',
            ),
            'd' => array
            (
                'łąd' => 'łędzie',
                'ód' => 'odzie',
                'd' => 'dzie',
            ),
            'f' => array
            (
                'f' => 'fie',
            ),
            'g' => array
            (
                'bóg' => 'boże',
                'g' => 'gu',
            ),
            'h' => array
            (
                'ph' => 'ph',
                'h' => 'hu',
            ),
            'j' => array
            (
                'ój' => 'oju',
                'j' => 'ju',
            ),
            'k' => array
            (
                'człek' => 'człeku',
                'ciek' => 'ćku',
                'liek' => 'łku',
                'niek' => 'ńku',
                'siek' => 'śku',
                'ziek' => 'źku',
                'wiek' => 'wieku',
                'ek' => 'ku',
                'k' => 'ku',
            ),
            'l' => array
            (
                'kornel' => 'kornelu',
                'sól' => 'solo',
                'mól' => 'mole',
                'awel' => 'awle',
                'al' => 'ale', // Michal -> Michale
                'l' => 'lu',
            ),
            'ł' => array
            (
                'zioł' => 'źle',
                'ół' => 'ole',
                'eł' => 'le',
                'ł' => 'le',
            ),
            'm' => array
            (
                'miriam' => 'miriam',
                'm' => 'mie',
            ),
            'n' => array
            (
                'nikola' => 'nikolo',
                'syn' => 'synu',
                'n' => 'nie',
            ),
            'ń' => array
            (
                'skroń' => 'skronio',
                'dzień' => 'dniu',
                'czeń' => 'czniu',
                'ń' => 'niu',
            ),
            'p' => array
            (
                'p' => 'pie',
            ),
            'r' => array
            (
                'per' => 'prze',
                'ór' => 'orze',
                'r' => 'rze',
            ),
            's' => array
            (
                'ines' => 'ines',
                'ies' => 'sie',
                's' => 'sie',
            ),
            'ś' => array
            (
                'gęś' => 'gęsio',
                'ś' => 'siu',
            ),
            't' => array
            (
                'st' => 'ście',
                't' => 'cie',
            ),
            'w' => array
            (
                'konew' => 'konwio',
                'sław' => 'sławie',
                'lew' => 'lwie',
                'łw' => 'łwiu',
                'ów' => 'owie',
                'w' => 'wie',
            ),
            'x' => array
            (
                'x' => 'ksie',
            ),
            'z' => array
            (
                'ksiądz' => 'księże',
                'dz' => 'dzu',
                'cz' => 'czu',
                'rz' => 'rzu',
                'sz' => 'szu',
                'óz' => 'ozie',
                'z' => 'zie',
            ),
            'ż' => array
            (
                'ąż' => 'ężu',
                'ż' => 'żu',
            )
        );

        $firstname = trim(strtolower(strtok($name, ' ')));

        $vocative = $firstname;
        if ($branch = @$vocativeRules[substr($firstname, -1)]) {
            while ($suffix = current($branch)) {

                if (preg_match('/(.*)'.key($branch).'$/i', $firstname, $reg)) {
                    $vocative = $reg[1].$suffix;
                    break;
                }

                next($branch);
            }
        }

        return self::ucFirst($vocative);
    }

    /**
     * @param  string $txt
     * @return string
     */
    public static function ucFirst($txt)
    {
        return mb_strtoupper(mb_substr($txt, 0, 1)) . mb_substr($txt, 1);
    }

    /**
     * @assert ('Łódź') == 'Lodz'
     * @param  string $txt
     * @return string
     */
    public static function toAscii($txt)
    {
        return transliterator_transliterate('Any-Latin; Latin-ASCII;', $txt);
    }

    /**
     * @assert ('ProductName') == 'product_name'
     * @param  string $txt
     * @return string
     */
    public static function underscore($txt)
    {
        $txt = preg_replace('/([A-Z\d]+)([A-Z][a-z])/', '$1_$2', $txt);
        $txt = preg_replace('/([a-z\d])([A-Z])/', '$1_$2', $txt);
        $txt = str_replace(['.', '-', ' '], '_', $txt);
        $txt = trim(preg_replace('/[^\w\d_]/si', '', $txt));
        $txt = preg_replace('/__+/', '_', $txt);
        $txt = trim($txt, '_');
        return strtolower($txt);
    }

    /**
     * @assert ('product_name') == 'ProductName'
     * @param  string $txt
     * @return string
     */
    public static function camelCase($txt)
    {
        $txt = str_replace(['.', '-', '_'], ' ', $txt);
        $txt = ucwords($txt);
        $txt = str_replace(' ', '', $txt);
        return $txt;
    }

    /**
     * http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     * @assert ('Łodź... bardzo SKOMPLIKOWANE?!') == 'łódź-bardzo-skomplikowane'
     * @param  string $txt
     * @param  bool $transliterate
     * @return string
     */
    public static function toUrl($txt, $transliterate = false)
    {
        // remove tags
        $txt = strip_tags($txt);

        // remove nbsp;
        $txt = str_replace('&nbsp;', '-', $txt);

        // replace non letter or digits by -
        $txt = preg_replace('~[^\\pL\d]+~u', '-', $txt);

        // trim
        $txt = trim($txt, '-');

        // remove duplicate -
        $txt = preg_replace('~-+~', '-', $txt);

        // remove unwanted characters
        $txt = preg_replace('~[^-\w]+~u', '', $txt);

        $txt = mb_strtolower($txt);

        if ($transliterate) {
            $txt = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $txt);
        }

        return !empty($txt)
            ? $txt : 'default';
    }

    /**
     * @deprecated
     * @param  string $txt
     * @return string
     */
    public static function lineToUl($txt)
    {
        if (empty($txt)) {
            return null;
        }

        $txt = trim($txt);
        $txt = str_ireplace("\r\n", "<li>", $txt);
        $txt = "<ul>\n<li>".$txt."</ul>\n";

        return $txt;
    }

    /**
     * @param  int $txt
     * @param  int $maxWords
     * @return string
     */
    public static function shortVersion($txt, $maxWords = 20)
    {
        $content = strip_tags($txt);
        $content = preg_replace('/\[\[.*\]\]/U', '', $content);
        $content = str_replace('&nbsp;', ' ', $content);
        $content = str_replace("\r\n", ' ', $content);
        $content = str_replace("\n", ' ', $content);
        $content = preg_replace('/\[\[.*\]\]/U', '', $content);
        $content = preg_replace('/ +/', ' ', $content);

        $short = strtok($content, ' ').' ';
        for ($i = 0; $i < $maxWords-1; $i++)
        {
            $token = strtok(' ');
            $short .= $token.' ';
        }

        return trim($short);
    }
}