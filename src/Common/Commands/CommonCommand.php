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
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Common\Commands;

// Laravel classes
use Illuminate\Console\Command;

/**
 * Class CommonCommand
 *
 * @package Lasallesoftware\Librarybackend\Common\Commands\Command
 */
class CommonCommand extends Command
{
    /**
     * Delete the given file
     *
     * @param  text  $file   The path and filename of the file to be deleted
     * @return void
     */
    protected function deleteFile($file)
    {
        if ($this->fileExists($file)) {
            $this->files->delete($file);
            $this->comment('Deleted ' . $file);
        }
    }

    /**
     * Determine if the file already exists.
     *
     * @param  string  $fileName
     * @return bool
     */
    protected function fileExists($fileName)
    {
        return $this->files->exists($fileName);
    }

    /**
     * Drop all of the database tables.
     *
     * @param  string  $database
     * @return void
     */
    protected function dropAllTables($database)
    {
        $this->laravel['db']
            ->connection($database)
            ->getSchemaBuilder()
            ->dropAllTables()
        ;
    }

    /**
     * Drop all of the database views.
     *
     * @param  string  $database
     * @return void
     */
    protected function dropAllViews($database)
    {
        $this->laravel['db']->connection($database)
            ->getSchemaBuilder()
            ->dropAllViews();
    }

    /**
     * Return the pattern (being searched) for the preg_replace
     *
     * @param  string  $patternToSearchFor  The text being searched
     * @return string
     */
    protected function pattern($patternToSearchFor)
    {
        $delimiter = '/';

        return $delimiter . $patternToSearchFor . $delimiter;
    }
}
