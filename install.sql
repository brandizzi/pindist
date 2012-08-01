-- PIN Distributor (aka pindist): a FreePBX module for registering who has 
-- received which PIN from various PIN sets.
-- Copyright (C) 2012  Adam Victor Nazareth Brandizzi <brandizzi@gmail.com>
-- 
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
-- 
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
-- 
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.
--
-- You can get the latest version of this file at 
-- http://bitbucket.org/brandizzi/pindist
CREATE TABLE IF NOT EXISTS pin_association (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    pinset INTEGER REFERENCES pinsets(pinsets_id),
    pin VARCHAR(10),
    name VARCHAR(100)
);
