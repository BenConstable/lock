<?php

namespace spec\BenConstable\Lock;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use BenConstable\Lock\Lock,
    BenConstable\Lock\Exception\LockException;

class LockSpec extends ObjectBehavior
{
    private $lockTestPath;

    function let()
    {
        $this->lockTestPath = __DIR__ . '/../../../data';
    }

    function it_acquires_a_lock_on_an_existing_file()
    {
        $this->beConstructedWith($this->lockTestPath . '/test-lock.txt');

        $this->acquire()
            ->shouldBe($this);

        $this->release();
    }

    function it_returns_the_same_lock_when_locking_more_than_once()
    {
        $this->beConstructedWith($this->lockTestPath . '/test-lock.txt');

        $this->acquire()
            ->shouldBe($this);

        $this->acquire()
            ->shouldBe($this);

        $this->release();
    }

    function it_fails_to_aquire_a_lock_on_an_already_locked_file()
    {
        $lock = new Lock($this->lockTestPath . '/test-lock.txt');
        $lock->acquire();

        $this->beConstructedWith($this->lockTestPath . '/test-lock.txt');

        $this
            ->shouldThrow('BenConstable\Lock\Exception\LockException')
            ->during('acquire');
    }

    function it_fails_to_acquire_a_lock_on_a_non_existing_file()
    {
        $this->beConstructedWith($this->lockTestPath . '/test-lock-not-exists.txt');

        $this
            ->shouldThrow('BenConstable\Lock\Exception\LockException')
            ->during('acquire');
    }

    function it_releases_a_lock_so_it_can_be_locked_by_something_else()
    {
        $this->beConstructedWith($this->lockTestPath . '/test-lock.txt');

        $this->acquire();

        try {
            $lock = new Lock($this->lockTestPath . '/test-lock.txt');
            $lock->acquire();
        } catch (LockException $e) {}

        $this->release();

        $lock->acquire();
    }
}
