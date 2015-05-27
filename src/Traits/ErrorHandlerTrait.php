<?php

namespace Mongoob\Traits;

trait ErrorHandlerTrait
{
    /**
     * Last error.
     *
     * @var array
     */
    protected $error = [];

    /**
     * Gets error.
     *
     * @return array
     */
    public function getErrorInfo()
    {
        return $this->error;
    }

    /**
     * Checks if there was an error.
     *
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * Sets error.
     *
     * @param int $code
     * @param string $message
     * 
     * @return void
     */
    protected function setError($code, $message = '')
    {
        $this->error = ['code' => $code, 'error' => $message];
    }

    /**
     * Clears information about last error.
     * 
     * @return void
     */
    protected function clearError()
    {
        $this->error = [];
    }
}
