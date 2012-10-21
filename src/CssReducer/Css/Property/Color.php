<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Css\Property;


class Color extends Property
{
    /**
     * converts a css color (hex) to rgb
     *
     * @param string $code
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function hex2rgb($code)
    {
        if ($code[0] == '#')
        {
            $code = substr($code, 1);
        }

        if (strlen($code) == 6)
        {
            list($r, $g, $b) = array(
                $code[0] . $code[1],
                $code[2] . $code[3],
                $code[4] . $code[5],
            );
        }
        elseif (strlen($code) == 3)
        {
            list($r, $g, $b) = array(
                $code[0] . $code[0],
                $code[1] . $code[1],
                $code[2] . $code[2],
            );
        }
        else
        {
            throw new \InvalidArgumentException('The hex value "' . $code . '" as color is invalid.');
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }

    /**
     * converts a rgb value to a hex value
     *
     * @param integer $r
     * @param integer $g
     * @param integer $b
     * @return string
     */
    public static function rgb2hex($r, $g = -1, $b = -1)
    {
        if (is_array($r) && sizeof($r) == 3)
        {
            list($r, $g, $b) = $r;
        }

        $r = intval($r);
        $g = intval($g);
        $b = intval($b);

        $r = dechex($r < 0 ? 0 : ($r > 255 ? 255 : $r));
        $g = dechex($g < 0 ? 0 : ($g > 255 ? 255 : $g));
        $b = dechex($b < 0 ? 0 : ($b > 255 ? 255 : $b));

        $color = (strlen($r) < 2 ? '0' : '') . $r;
        $color .= (strlen($g) < 2 ? '0' : '') . $g;
        $color .= (strlen($b) < 2 ? '0' : '') . $b;

        return '#' . $color;
    }

    /**
     * @return array
     */
    public static function getColornames()
    {
        return array(
            //  Colors  as  they  are  defined  in  HTML  3.2
            "black" => '000000',
            "bisque" => 'FFE4C4',
            "blanchedalmond" => 'FFEBCD',
            "maroon" => '800000',
            "green" => '008000',
            "olive" => '808000',
            "navy" => '000080',
            "purple" => '800080',
            "teal" => '008080',
            "gray" => '808080',
            "lightslategrey" => '778899',
            "silver" => 'C0C0C0',
            "red" => 'FF0000',
            "cyan" => '00FFFF',
            "darkgrey" => 'A9A9A9',
            "magenta" => 'FF00FF',
            "darkslategrey" => '2F4F4F',
            "dimgrey" => '696969',
            "grey" => '808080',
            "lime" => '00FF00',
            "lightgray" => 'D3D3D3',
            "slategrey" => '708090',
            "yellow" => 'FFFF00',
            "blue" => '0000FF',
            "fuchsia" => 'FF00FF',
            "aqua" => '00FFFF',
            "white" => 'FFFFFF',
            //  Additional  colors  as  they  are  used  by  Netscape  and  IE
            "aliceblue" => 'F0F8FF',
            "antiquewhite" => 'FAEBD7',
            "aquamarine" => '7FFFD4',
            "azure" => 'F0FFFF',
            "beige" => 'F5F5DC',
            "blueviolet" => '8A2BE2',
            "brown" => 'A52A2A',
            "burlywood" => 'DEB887',
            "cadetblue" => '5F9EA0',
            "chartreuse" => '7FFF00',
            "chocolate" => 'D2691E',
            "coral" => 'FF7F50',
            "cornflowerblue" => '6495ED',
            "cornsilk" => 'FFF8DC',
            "crimson" => 'DC143C',
            "darkblue" => '00008B',
            "darkcyan" => '008B8B',
            "darkgoldenrod" => 'B8860B',
            "darkgray" => 'A9A9A9',
            "darkgreen" => '006400',
            "darkkhaki" => 'BDB76B',
            "darkmagenta" => '8B008B',
            "darkolivegreen" => '556B2F',
            "darkorange" => 'FF8C00',
            "darkorchid" => '9932CC',
            "darkred" => '8B0000',
            "darksalmon" => 'E9967A',
            "darkseagreen" => '8FBC8F',
            "darkslateblue" => '483D8B',
            "darkslategray" => '2F4F4F',
            "darkturquoise" => '00CED1',
            "darkviolet" => '9400D3',
            "deeppink" => 'FF1493',
            "deepskyblue" => '00BFFF',
            "dimgray" => '696969',
            "dodgerblue" => '1E90FF',
            "firebrick" => 'B22222',
            "floralwhite" => 'FFFAF0',
            "forestgreen" => '228B22',
            "gainsboro" => 'DCDCDC',
            "ghostwhite" => 'F8F8FF',
            "gold" => 'FFD700',
            "goldenrod" => 'DAA520',
            "greenyellow" => 'ADFF2F',
            "honeydew" => 'F0FFF0',
            "hotpink" => 'FF69B4',
            "indianred" => 'CD5C5C',
            "indigo" => '4B0082',
            "ivory" => 'FFFFF0',
            "khaki" => 'F0E68C',
            "lavender" => 'E6E6FA',
            "lavenderblush" => 'FFF0F5',
            "lawngreen" => '7CFC00',
            "lemonchiffon" => 'FFFACD',
            "lightblue" => 'ADD8E6',
            "lightcoral" => 'F08080',
            "lightcyan" => 'E0FFFF',
            "lightgoldenrodyellow" => 'FAFAD2',
            "lightgreen" => '90EE90',
            "lightgrey" => 'D3D3D3',
            "lightpink" => 'FFB6C1',
            "lightsalmon" => 'FFA07A',
            "lightseagreen" => '20B2AA',
            "lightskyblue" => '87CEFA',
            "lightslategray" => '778899',
            "lightsteelblue" => 'B0C4DE',
            "lightyellow" => 'FFFFE0',
            "limegreen" => '32CD32',
            "linen" => 'FAF0E6',
            "mediumaquamarine" => '66CDAA',
            "mediumblue" => '0000CD',
            "mediumorchid" => 'BA55D3',
            "mediumpurple" => '9370D0',
            "mediumseagreen" => '3CB371',
            "mediumslateblue" => '7B68EE',
            "mediumspringgreen" => '00FA9A',
            "mediumturquoise" => '48D1CC',
            "mediumvioletred" => 'C71585',
            "midnightblue" => '191970',
            "mintcream" => 'F5FFFA',
            "mistyrose" => 'FFE4E1',
            "moccasin" => 'FFE4B5',
            "navajowhite" => 'FFDEAD',
            "oldlace" => 'FDF5E6',
            "olivedrab" => '6B8E23',
            "orange" => 'FFA500',
            "orangered" => 'FF4500',
            "orchid" => 'DA70D6',
            "palegoldenrod" => 'EEE8AA',
            "palegreen" => '98FB98',
            "paleturquoise" => 'AFEEEE',
            "palevioletred" => 'DB7093',
            "papayawhip" => 'FFEFD5',
            "peachpuff" => 'FFDAB9',
            "peru" => 'CD853F',
            "pink" => 'FFC0CB',
            "plum" => 'DDA0DD',
            "powderblue" => 'B0E0E6',
            "rosybrown" => 'BC8F8F',
            "royalblue" => '4169E1',
            "saddlebrown" => '8B4513',
            "salmon" => 'FA8072',
            "sandybrown" => 'F4A460',
            "seagreen" => '2E8B57',
            "seashell" => 'FFF5EE',
            "sienna" => 'A0522D',
            "skyblue" => '87CEEB',
            "slateblue" => '6A5ACD',
            "slategray" => '708090',
            "snow" => 'FFFAFA',
            "springgreen" => '00FF7F',
            "steelblue" => '4682B4',
            "tan" => 'D2B48C',
            "thistle" => 'D8BFD8',
            "tomato" => 'FF6347',
            "turquoise" => '40E0D0',
            "violet" => 'EE82EE',
            "wheat" => 'F5DEB3',
            "whitesmoke" => 'F5F5F5',
            "yellowgreen" => '9ACD32',
        );
    }
}
