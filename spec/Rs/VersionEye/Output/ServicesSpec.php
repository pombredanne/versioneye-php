<?php

namespace spec\Rs\VersionEye\Output;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\BufferedOutput;

class ServicesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\VersionEye\Output\Services');
    }

    public function it_prints_a_boolean_on_follow()
    {
        $output = new BufferedOutput();

        $this->ping($output, ['success' => true, 'message' => 'pong']);

        expect($output->fetch())->toBe(<<<EOS
pong

EOS
        );
    }
}
