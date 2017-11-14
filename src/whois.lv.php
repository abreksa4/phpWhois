<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

require_once('whois.parser.php');

if (! defined('__LV_HANDLER__')) {
    define('__LV_HANDLER__', 1);
}

class lv_handler
{

    function parse($data_str, $query)
    {
        $translate = [
            'contact nic-hdl:' => 'handle',
            'contact name:' => 'name'
        ];

        $items = [
            'admin' => 'Contact type:      Admin',
            'tech' => 'Contact type:      Tech',
            'zone' => 'Contact type:      Zone',
            'owner.name' => 'Registrar:',
            'owner.email' => 'Registrar email:',
            'domain.status' => 'Status:',
            'domain.created' => 'Registered:',
            'domain.changed' => 'Last updated:',
            'domain.nserver.' => 'NS:',
            '' => '%'
        ];

        $r = [];
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $translate);

        if (! empty($r['regrinfo']['domain']['status'])) {
            switch ($r['regrinfo']['domain']['status']) {
                case 'free':
                    $r['regrinfo']['registered'] = 'no';
                    break;
                case 'active':
                    $r['regrinfo']['registered'] = 'yes';
                    break;
                default:
                    $r['regrinfo']['registered'] = 'unknown';
            }
        } else {
            $r['regrinfo']['registered'] = 'unknown';
        }

        $r['regyinfo']['referrer'] = 'https://www.nic.lv/';
        $r['regyinfo']['registrar'] = 'Nic LV';
        return $r;
    }
}
