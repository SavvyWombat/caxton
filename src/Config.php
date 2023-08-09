<?php

namespace SavvyWombat\Caxton;

class Config
{
    protected static ?Config $instance = null;
    protected array $config = [];
    protected bool $extendable = true;
    protected bool $modifiable = false;

    public function __construct(array ...$sources)
    {
        foreach ($sources as $source) {
            $this->config = array_merge_recursive($this->config, $source);
        }
    }

    public function get(?string $key = '', mixed $default = null): mixed
    {
        if ($key === '') {
            return $this->config;
        }

        $keys = explode('.', $key);
        $tip = $this->config;

        foreach ( $keys as $key ) {
            if ( ! isset($tip[$key]) ) {
                return $default;
            }

            $tip = $tip[$key];
        }

        return $tip ?: $default;
    }

    public function set(string $key, mixed $value): void
    {
        if (! ($this->extendable || $this->modifiable) ) {
            throw new \Exception('Unable to set config');
        }

        if ( $key === '' ) {
            throw new \InvalidArgumentException('Config key must not be empty');
        }

        $keys = explode('.', $key);
        $lastKey = $keys[count($keys) - 1];
        $tip = &$this->config;

        foreach ( $keys as $key ) {
            if ( $key === $lastKey ) {
                if ( isset($tip[$key]) && $this->modifiable ) {
                    $tip[$key] = $value;
                    return;
                }

                if ( ! isset($tip[$key]) && $this->extendable ) {
                    $tip[$key] = $value;
                    return;
                }

                throw new \Exception('Unable to set config');
            }

            $tip = $tip[$key];
        }
    }

    public static function instance(?array ...$sources): self
    {
        if (! self::$instance) {
            self::$instance = new self(...$sources);
        }

        return self::$instance;
    }
}
