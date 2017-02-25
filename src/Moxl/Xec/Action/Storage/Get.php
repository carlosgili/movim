<?php
/*
 * Get.php
 *
 * Copyright 2012 edhelas <edhelas@edhelas-laptop>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

namespace Moxl\Xec\Action\Storage;

use Moxl\Xec\Action;
use Moxl\Stanza\Storage;

use Movim\User;

class Get extends Action
{
    private $_xmlns;

    public function request()
    {
        $this->store();
        Storage::get($this->_xmlns);
    }

    public function setXmlns($xmlns)
    {
        $this->_xmlns = $xmlns;
        return $this;
    }

    public function handle($stanza, $parent = false)
    {
        if($stanza->query->data) {
            $data = unserialize(trim((string)$stanza->query->data));

            $user = new User;
            if(is_array($data)) {
                $data = array_merge($data, ['config' => true]);
                $user->setConfig($data);

                $this->pack($data);
                $this->deliver();
            }
            else {
                $this->errorFeatureNotImplemented($stanza);
            }
        } else {
            $this->errorFeatureNotImplemented($stanza);
        }
    }

    public function errorFeatureNotImplemented($stanza)
    {
        $user = new User;
        $user->setConfig(['config' => false]);
        $this->deliver();
    }
}
