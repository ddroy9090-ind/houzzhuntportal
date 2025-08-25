<?php
declare(strict_types=1);

namespace App;

class RateLimiter
{
    private string $store;
    private int $limit;
    private int $window;

    public function __construct(string $store, int $limit, int $window)
    {
        $this->store = $store;
        $this->limit = $limit;
        $this->window = $window;
    }

    public function allow(string $ip): bool
    {
        $file = $this->store . '/rl_' . preg_replace('/[^a-zA-Z0-9]/', '', $ip) . '.json';
        $data = ['tokens' => $this->limit, 'updated' => time()];
        if (is_file($file)) {
            $data = json_decode((string)file_get_contents($file), true) ?: $data;
            $elapsed = time() - $data['updated'];
            $refill = ($this->limit / $this->window) * $elapsed;
            $data['tokens'] = min($this->limit, $data['tokens'] + $refill);
        }
        if ($data['tokens'] < 1) {
            file_put_contents($file, json_encode($data));
            return false;
        }
        $data['tokens'] -= 1;
        $data['updated'] = time();
        file_put_contents($file, json_encode($data));
        return true;
    }
}
