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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Common\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel classes
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

// Laravel facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonModel extends Eloquent
{
    /**
     * Wash the URL. Called from model events, so this method is static
     *
     * @param  string  $url
     * @return string
     */
    public static function washUrl($url)
    {
        if ((substr($url,0,7) == "http://")  ||
            (substr($url,0,8) == "https://") ||
            (is_null($url))                  ||
            (trim($url) == '')
        ) {
            return $url;
        }

        return "http://" . $url;
    }

    /**
     * stripCharactersFromText1.
     *
     * Called from model events, so this method is static
     *
     * Named "1" (stripCharactersFromText1) because it is bespoke, and maybe I will want another variation later (that
     * I can then call "stripCharactersFromText2")
     *
     * Originally created for Lasallesoftware\Librarybackend\Profiles\Models\Telephone
     *
     * @param  string  $text
     * @return string
     */
    public static function stripCharactersFromText1($text)
    {
        return str_replace(['(', ')', '-', '_', '{', '}', '[', ']'], '', $text);
    }


    /**
     * Create an excerpt from a given string.
     *
     * @param  string   $excerpt   An existing excerpt, if it exists
     * @param  string   $content   The text that you want excerpted
     * @return string
     */
    public static function makeExcerpt($excerpt, $content)
    {
        $text = (($excerpt == "") || (is_null($excerpt))) ? $content : $excerpt;
        $text = self::deepWashText($text);

        return mb_substr($text, 0, config('lasallesoftware-librarybackend.how_many_initial_chars_of_content_field_for_excerpt'));
    }

    /**
     * Create an excerpt from a given string.
     *
     * @param  string   $metadescription   An existing meta_description, if it exists
     * @param  string   $content           The text that you want excerpted
     * @return string
     */
    public static function makeMetadescription($metadescription, $content)
    {
        $text = (($metadescription == "") || (is_null($metadescription))) ? $content : $metadescription;
        $text = self::deepWashText($text);

        // The meta_description database field is type "string", so only 255 chars max.
        return mb_substr($text, 0, 255);
    }

    /**
     * Get this app's installed_domain_id
     *
     * @return mixed
     */
    public static function getCurrentInstalleddomainId()
    {
        $lasalle_app_domain_name= app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name');

        return DB::table('installed_domains')
            ->where('title', $lasalle_app_domain_name)
            ->value('id')
        ;
    }

    /**
     * Make a slug from the title
     *
     * @param  string  $slug    The slug's value
     * @param  string  $title   The title's value. Going to make a slug out of it!
     * @param  string  $table   The database table where the slug field resides
     * @param  int     $id      The ID of the current database record, if we're doing an update
     * @return string
     */
    public static function makeSlug($slug, $title, $table, $id = 0)
    {
        // If there is a slug, then let's start with that. Otherwise, let's base the slug on the title
        $text = (($slug == "") || (is_null($slug))) ? $title : $slug;

        // remove the encoded blank chars
        $text = str_replace("\xc2\xa0", '', $text);

        // remove encoded apostrophe
        $text = str_replace("&#39;", '', $text);

        // general wash
        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = filter_var($text, FILTER_SANITIZE_STRING);

        // https://github.com/laravel/framework/blob/5.8/src/Illuminate/Support/Str.php#L438
        $text = str::slug($text);

        // The meta_description database field is type "string", so only 255 chars max.
        $text = mb_substr($text, 0, 255);

        // the slug must be unique
        $counter = 1;
        while ((!self::isSlugUnique($text, $table, $id)) && ($counter < 10)) {

            // not unique, so append a single digit number to the slug. Nine tries ought to be enough to
            // achieve a unique slug. If not, a cryptic MySQL "integrity" message displays.
            $text = mb_substr($text, 0, 254);
            $text = $text . $counter;

            $counter++;
        }

        return $text;
    }

    /**
     * Is the slug unique in the specified database table?
     *
     * @param  string  $slug    The slug's value
     * @param  string  $table   The database table where the slug field resides
     * @param  int     $id      The ID of the current database record, if we're doing an update
     * @return bool
     */
    public static function isSlugUnique($slug, $table, $id)
    {
        $rowCount = DB::table($table)
            ->where('slug',  $slug)
            ->where('id', '<>', $id)
            ->count()
        ;

        return ($rowCount == 0) ? true : false;
    }

    /*
     * Wash the content field
     *
     * This method added just in case there is a need for further content field processing.
     * Right now, it is not designed to do anything.
     *
     * @param  string $text  Text (content) to wash
     * @return string
     */
    public static function washContent($text)
    {
        return trim($text);
    }

    /**
     * Deep wash the specified text
     *
     * @param  string  $text   Text to deep wash
     * @return string
     */
    public static function deepWashText($text)
    {
        // Convert HTML entities to their corresponding characters
        // https://www.php.net/manual/en/function.html-entity-decode.php
        $text = html_entity_decode($text);

        //  Strip HTML and PHP tags from a string
        // https://www.php.net/manual/en/function.strip-tags.php
        $text = strip_tags($text);

        // Filters a variable with a specified filter
        // https://www.php.net/manual/en/function.filter-var.php
        // https://www.php.net/manual/en/filter.filters.sanitize.php
        $text = filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        // remove the encoded blank chars
        $text = str_replace("\xc2\xa0",'', $text);

        return trim($text);
    }


    /**
     * Perform slug processing on the given fields from the Nova resource form
     *
     * @param  model  $model                Object from the Nova resource form
     * @param  array  $fields               Array of database/form fields requiring processing
     * @param  string $databaseTableName    Name of the database the fields belong to
     * @return void
     */
    public static function performSlugProcessing($model, $fields, $databaseTableName)
    {
        foreach ($fields as $field) {
            $model->$field = self::makeSlug($model->$field, $model->title, $databaseTableName, $model->id);
        }
    }

    /**
     * Perform a text deep wash on the given fields from the Nova resource form
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $fields     Array of database/form fields requiring processing
     * @return void
     */
    public static function performTextDeepWash($model, $fields)
    {
        foreach ($fields as $field) {
            $model->$field = self::deepWashText($model->$field);
        }
    }

    /**
     * Do the URL wash on the given fields from the Nova resource form
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $fields     Array of database/form fields requiring processing
     * @return void
     */
    public static function performUrlWash($model, $fields)
    {
        foreach ($fields as $field) {

            if (($model->$field != "") && (!is_null($model->$field))) {
                $model->$field = self::washUrl($model->$field);
            }
        }
    }

    /**
     * Wash content on the given fields from the Nova resource form
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $fields     Array of database/form fields requiring processing
     * @return void
     */
    public static function performWashContent($model, $fields)
    {
        foreach ($fields as $field) {
            $model->$field = self::washContent($model->$field);
        }
    }

    /**
     * Perform excerpt processing on the given fields from the Nova resource form
     *
     * @param  model   $model                  Object from the Nova resource form
     * @param  string  $fieldToExcerpt         Database/form field with the excerpt
     * @param  string  $fieldToBaseExcerptOn   Database/form field the excerpt is based on
     * @return void
     */
    public static function performExcerptProcessing($model, $fieldToExcerpt, $fieldToBaseExcerptOn)
    {
        $model->$fieldToExcerpt = self::makeExcerpt($model->$fieldToExcerpt, $model->$fieldToBaseExcerptOn);
    }

    /**
     * Perform meta description processing on the given fields from the Nova resource form
     *
     * @param  model   $model                         Object from the Nova resource form
     * @param  string  $fieldToMetadescription        Database/form field with the meta_description
     * @param  string  $fieldToBaseMetadescriptionOn  Database/form field the meta_description is based on
     * @return void
     */
    public static function performMetadescriptiopnProcessing($model, $fieldToMetadescription, $fieldToBaseMetadescriptionOn)
    {
        $model->$fieldToMetadescription = self::makeMetadescription($model->$fieldToMetadescription, $model->$fieldToBaseMetadescriptionOn);
    }

     /**
     * Convert a form text field's "yes" or "no" to the database field's type boolean, 
     * for the given fields from the Nova resource form
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $fields     Array of database/form fields requiring processing
     * @return void
     */
    public static function performConvertYesNoTextToBooleanProcessing($model, $fields)
    {
        foreach ($fields as $field) {
            $model->$field = (substr(strtolower($model->$field), 0, 3) == 'yes') ? true : false;
        }
    }

    /**
     * Perform date field type processing on the given fields from the Nova resource form
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $fields     Array of database/form fields requiring processing
     * @return void
     */
    public static function performDateFieldsProcessing($model, $fields)
    {
        foreach ($fields as $field) {
            $model->$field = (($model->$field == "") || (is_null($model->$field))) ? Carbon::now(null) : $model->$field;
        }
    }

    /**
     * Populate the give field with the client_id from the personbydomain_client pivot table.
     *
     * @param  model  $model      Object from the Nova resource form
     * @param  array  $field      Field requiring populating
     * @return void
     */
    public static function populateClientIDField($model, $field)
    {
        $personbydomain = new Personbydomain;
        $model->$field = ($personbydomain->getClientId(Auth::id()) == 0) ? null : $personbydomain->getClientId(Auth::id());
    }

    /**
     * Create a GUID.
     *
     * @return string
     */
    public static function createGUID()
    {
        $separator = '-';
        $segment1  = STR::random(8);
        $segment2  = STR::random(4);
        $segment3  = STR::random(4);
        $segment4  = STR::random(4);
        $segment5  = STR::random(12);

        return $segment1 . $separator . $segment2 . $separator . $segment3 . $separator . $segment4 . $separator . $segment5;
    }
}