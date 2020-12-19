<?php

namespace XLaravel\ModelSettingsBag;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SettingsBag
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    # Get the model's settings.
    public function all()
    {
        return $this->model->settings;
    }

    # Apply the model's settings.
    public function apply(array $settings = []): self
    {
        $this->model->settings = (array)$settings;
        $this->model->save();

        return $this;
    }

    # Return the value of the setting at the given path.
    public function get(string $path = null, $default = null)
    {
        return $path ? Arr::get($this->all(), $path, $default) : $this->all();
    }

    # Determine if the model has the given setting.
    public function has(string $path): bool
    {
        return (bool)Arr::has($this->all(), $path);
    }

    # Update the setting at given path to the given value.
    public function update(string $path = null, $value = []): self
    {
        if (func_num_args() < 2) {
            $value = $path;
            $path = null;
        }

        $settings = $this->all();

        Arr::set($settings, $path, $value);

        return $this->apply($settings);
    }

    # Delete the setting at the given path.
    public function delete(string $path = null): self
    {
        if (!$path) {
            return $this->update([]);
        }

        $settings = $this->all();

        Arr::forget($settings, $path);

        return $this->apply($settings);
    }
}
