<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class ProjectsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Projects');
    }

    public function it_prints_a_table_on_all()
    {
        $output = new BufferedOutput();
        $this->all($output, [
            [
                'id' => '1337',
                'project_key' => 'digitalkaoz_versioneye-php_1',
                'name' => 'digitalkaoz/versioneye-php',
                'project_type' => 'composer',
                'public' => false,
                'dep_number' => 47,
                'out_number' => 13,
                'updated_at' => '25.05.1981',
            ],
        ]);

        expect($output->fetch())->toBe(<<<EOS
+------+------------------------------+----------------------------+----------+--------+--------------+----------+------------+
| Id   | Key                          | Name                       | Type     | Public | Dependencies | Outdated | Updated At |
+------+------------------------------+----------------------------+----------+--------+--------------+----------+------------+
| 1337 | digitalkaoz_versioneye-php_1 | digitalkaoz/versioneye-php | composer |        | 47           | 13       | 25.05.1981 |
+------+------------------------------+----------------------------+----------+--------+--------------+----------+------------+

EOS
        );
    }

    public function it_prints_a_table_on_licenses()
    {
        $output = new BufferedOutput();
        $this->licenses($output, [ 'licenses' => [
            'MIT' => [[
                'name' => 'digitalkaoz/versioneye-php',
            ]],
            'unknown' => [[
                'name' => 'symfony/symfony',
            ]],
        ]]);

        expect($output->fetch())->toBe(<<<EOS
+---------+----------------------------+
| license | name                       |
+---------+----------------------------+
| MIT     | digitalkaoz/versioneye-php |
| unknown | symfony/symfony            |
+---------+----------------------------+

EOS
        );
    }

    public function it_prints_a_list_and_table_on_create()
    {
        $this->defaultOutput('create');
    }

    public function it_prints_a_list_and_table_on_show()
    {
        $this->defaultOutput('show');
    }

    public function it_prints_a_list_and_table_on_update()
    {
        $this->defaultOutput('update');
    }

    public function it_prints_a_boolean_on_delete()
    {
        $output = new BufferedOutput();

        $this->delete($output, ['success' => true, 'message' => 'foo']);

        expect($output->fetch())->toBe(<<<EOS
foo

EOS
        );
    }

    private function defaultOutput($method)
    {
        $output = new BufferedOutput();
        $this->{$method}($output, [
            'name' => 'digitalkaoz/versioneye-php',
            'id' => '1337',
            'project_key' => 'digitalkaoz_versioneye-php_1',
            'project_type' => 'composer',
            'public' => true,
            'out_number' => 7,
            'updated_at' => '25.05.1981',
            'dependencies' => [[
                'name' => 'symfony/symfony',
                'stable' => true,
                'outdated' => false,
                'version_current' => '2.5.0',
                'version_requested' => '2.5.0',
            ]]
        ]);

        expect($output->fetch())->toBe(<<<EOS
Name            : digitalkaoz/versioneye-php
Id              : 1337
Key             : digitalkaoz_versioneye-php_1
Type            : composer
Public          : Yes
Outdated        : 7
Updated At      : 25.05.1981
+-----------------+--------+----------+---------+-----------+
| Name            | Stable | Outdated | Current | Requested |
+-----------------+--------+----------+---------+-----------+
| symfony/symfony | 1      | No       | 2.5.0   | 2.5.0     |
+-----------------+--------+----------+---------+-----------+

EOS
        );
    }
}
