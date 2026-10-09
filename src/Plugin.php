<?php

namespace humanreply\livechat;

use Craft;
use craft\base\Model;
use craft\events\TemplateEvent;
use craft\web\View;
use humanreply\livechat\models\Settings;
use yii\base\Event;

/**
 * Human Reply Live Chat: live chat on every page of a Craft site, answered from Telegram.
 *
 * With a site key in its settings, every page the site renders gets the same tag any website pastes, just before
 * </body>: `<script src="https://humanreply.app/embed.js" data-site="rl_…" defer></script>`. The control panel, Live
 * Preview and console requests get nothing, and without a key nothing is added at all.
 *
 * @method Settings getSettings()
 */
class Plugin extends \craft\base\Plugin
{
    /** A site key as Human Reply hands it over at the end of setup. */
    public const SITE_KEY_PATTERN = '/^rl_[A-Za-z0-9_-]{8,32}$/';

    /** Where the chat is served from. */
    public const ORIGIN = 'https://humanreply.app';

    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();

        Event::on(View::class, View::EVENT_BEFORE_RENDER_PAGE_TEMPLATE, function(TemplateEvent $event) {
            if ($event->templateMode !== View::TEMPLATE_MODE_SITE) {
                return;
            }
            $request = Craft::$app->getRequest();
            if ($request->getIsConsoleRequest() || !$request->getIsSiteRequest() || $request->getIsPreview() || $request->getIsLivePreview()) {
                return;
            }
            $siteKey = $this->getSettings()->getParsedSiteKey();
            if ($siteKey === null) {
                return;
            }
            Craft::$app->getView()->registerJsFile(self::ORIGIN . '/embed.js', [
                'position' => View::POS_END,
                'data-site' => $siteKey,
                'defer' => true,
            ], 'human-reply');
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('human-reply-live-chat/_settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
