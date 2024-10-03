<?php

namespace Plastyk\Dashboard\Model;

use SilverStripe\Admin\AdminRootController;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

abstract class QuickLink
{
    use Extensible;
    use Injectable;
    use Configurable;

    /**
     * @var int $title Title of this quick link
     */
    private static $title = 'Link';

    /**
     * @var bool $url URL to link to
     */
    private static $url = '{$AdminURL}';

    /**
     * @var bool $icon Icon to display
     */
    private static $icon = 'fa-star';

    /**
     * @var int $sort Sort order of this quick link
     */
    private static $sort = 100;

    /**
     * @var bool $enabled If set to FALSE, this quick link will not display
     */
    private static $enabled = true;

    public function getTitle(): string
    {
        return Config::inst()->get($this::class, 'title');
    }

    public function getUrl(): string
    {
        $url = Config::inst()->get($this::class, 'url');

        return str_replace('{$AdminURL}', AdminRootController::admin_url(), $url);
    }

    public function getIcon(): string
    {
        return Config::inst()->get($this::class, 'icon');
    }

    public function getSort(): int
    {
        return Config::inst()->get($this::class, 'sort');
    }

    public function getEnabled(): bool
    {
        return Config::inst()->get($this::class, 'enabled');
    }

    public function canView($member = null): bool
    {
        return true;
    }

    public function toArray(): array
    {
        return [
            'Title' => $this->getTitle(),
            'Url' => $this->getUrl(),
            'Icon' => $this->getIcon(),
            'Sort' => $this->getSort(),
            'Enabled' => $this->getEnabled(),
        ];
    }
}
