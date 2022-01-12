<?php

/**
 * This file is part of the Lasalle Software library back-end package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2022 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel classes
// use the Laravel string class, which in turn calls "uuid:uuid4" from
// https://github.com/ramsey/uuid/blob/master/src/Uuid.php#L713
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;


/**
 * Class UuidGenerator
 *
 * How can we trace what happens in the database, and elsewhere, when someone fills out a form? Or when
 * whatever process is initiated? It will help if that request/process has its own identifier, and this
 * identifier is in the database tables and elsewhere.
 *
 * The uuid is just a way to trace things within after-the-fact. It does not provide any functionality
 * per se, nor is it used as a database table primary key.
 *
 * The uuid is a purely internal system thing. There is no UI form that creates it.
 *
 * A uuid is never deleted. So the database record is not "update"-able and not "delete"-able. *
 *
 * @package Lasallecms\Lasallecmsadmin\Http\Controllers
 */

class UuidGenerator
{
    /**
     * Create a UUID
     *
     * @param  int    $lookup_lasallesoftwareevent_id  The lookup table's ID
     * @param  string $comment                         Optional comment
     * @param  int    $created_by                      Optional user ID
     * @return string
     */
    public function createUuid(
        int    $lookup_lasallesoftwareevent_id = 1,
        string $comment = null,
        int    $created_by = 1
    )
    {
        // STEP 1: create a fresh UUID
        $uuid4 = $this->newUuid();

        // STEP 2: test for uniqueness
        // The probability of two UUIDs being the same is so rare that I do not have to test for it!

        // STEP 3: insert a new record into the UUIDS table
        $this->insertUuidIntoDatabase(
            $lookup_lasallesoftwareevent_id,
            $uuid4,
            $comment,
            $created_by
        );

        // STEP 4: store the UUID and the lookup_lasallesoftware_event to the global scope
        $this->storeToGlobal($uuid4, $lookup_lasallesoftwareevent_id);

        // STEP 5: Return the new UUID
        return $uuid4;
    }

    /**
     * Spin up a new UUID
     *
     * @return string
     */
    public function newUuid()
    {
        return (string) Str::uuid();
    }

    /**
     * Save the new UUID into the uuid database.
     *
     * Very convenient to put this method in this class and not in the model.
     *
     * @param  int    $lookup_lasallesoftwareevent_id  The lookup table's ID
     * @param  string $comment                         Optional comment
     * @param  int    $created_by                      Optional user ID
     *
     * @return void
     */
    //public function insertUuidIntoDatabase($lasallesoftware_event_id = 1, $newUuid, $comments = null, $created_by = 1)
    public function insertUuidIntoDatabase($lasallesoftware_event_id, $newUuid, $comments, $created_by = 1)
    {
        $uuid = new Uuid;


        // START: quick and dirty way to deal with PHP8 throwing error. I just upgraded to PHP8.
        if (! isset($comments)) {
            $comments = "";
        }

        if (is_null($comments)) {
            $comments = "";
        } else {
            $comments = mb_substr(trim($comments), 0, 255);
        }
        // END: quick and dirty way to deal with PHP8 throwing error. I just upgraded to PHP8.        


        // START: quick and dirty way to deal with PHP8 throwing error. I just upgraded to PHP8.
        if (! isset($lasallesoftware_event_id)) {
            $lasallesoftware_event_id = 1;
        }

        if (is_null($lasallesoftware_event_id)) {
            $lasallesoftware_event_id = 1;
        }

        if ($lasallesoftware_event_id == "") {
            $lasallesoftware_event_id = 1;
        }
        // END: quick and dirty way to deal with PHP8 throwing error. I just upgraded to PHP8.


        $uuid->lasallesoftware_event_id = $lasallesoftware_event_id;
        $uuid->uuid                     = $newUuid;
        $uuid->comments                 = $comments;
        $uuid->created_at               = Carbon::now(null);
        $uuid->created_by               = $created_by;

        if ($uuid->save()) {
            // Return the new ID
            return $uuid->id;
        }
        return false;
    }

    /**
     * Store UUID and Lasallesoftware_Event_ID to the global scope.
     *
     * Resorting to using $GLOBALS for now.
     *
     * @param string $uuid
     * @param int    $lookup_lasallesoftwareevent_id
     */
    public function storeToGlobal($uuid, $lookup_lasallesoftwareevent_id)
    {
        //$this->request->session()->put('uuid_generator_uuid', $uuid);
        //$this->request->session()->put('uuid_generator_lookup_lasallesoftware_event_id', $lookup_lasallesoftwareevent_id);
        $GLOBALS['uuid_generator_uuid'] = $uuid;
        $GLOBALS['uuid_generator_lookup_lasallesoftware_event_id'] = $lookup_lasallesoftwareevent_id;
    }
}
