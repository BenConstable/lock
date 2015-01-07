<?php
namespace BenConstable\Lock;

use BenConstable\Lock\Exception\LockException;

/**
 * Simple lock class to obtain file locks.
 */
class Lock
{
    /**
     * Whether or not we currently have a lock.
     *
     * @var boolean
     */
    private $locked;

    /**
     * The file being wrapped by this lock.
     *
     * @var resource
     */
    private $fp;

    /**
     * The path to the resource to lock.
     *
     * @var string
     */
    private $filePath;

    /**
     * Constructor.
     *
     * @param  string $path Path to lockfile
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->locked   = false;
        $this->fp       = null;
    }

    /**
     * Destructor.
     *
     * Release lock at the last opportunity.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->release();
    }

    /**
     * Acquire a lock on the resource.
     *
     * @return \BenConstable\Lock\Lock This, for chaining
     *
     * @throws \BenConstable\Lock\Exception\LockException If lock could not be acquired
     */
    public function acquire()
    {
        if (!$this->locked) {
            $this->fp = @fopen($this->filePath, 'w');

            if (!$this->fp || !flock($this->fp, LOCK_EX | LOCK_NB)) {
                throw new LockException("Could not get lock on {$this->filePath}");
            } else {
                $this->locked = true;
            }
        }

        return $this;
    }

    /**
     * Release the lock on the resource, if we have one.
     *
     * @return \BenConstable\Lock\Lock This, for chaining
     */
    public function release()
    {
        if ($this->locked) {
            flock($this->fp, LOCK_UN);
            fclose($this->fp);

            $this->fp = null;
            $this->locked = false;
        }

        return $this;
    }
}
