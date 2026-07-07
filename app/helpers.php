<?php

if (! function_exists('env')) {
    /**
     * Get an environment variable or return the default.
     * This is a lightweight fallback for projects that load before Laravel helpers.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false) {
            return $default;
        }

        if ($value === null) {
            return $default;
        }

        return $value;
    }
}
