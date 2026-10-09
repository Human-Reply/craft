<?php

namespace humanreply\livechat\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use humanreply\livechat\Plugin;

/**
 * The plugin's one setting: the site key setup hands over, like `rl_7hK2mQ9xW4`, or an environment variable that
 * holds it, like `$HUMAN_REPLY_SITE_KEY`.
 */
class Settings extends Model
{
    public string $siteKey = '';

    /** The key the site runs with: the setting, read through the environment, or null when it isn't a site key. */
    public function getParsedSiteKey(): ?string
    {
        $key = trim((string)App::parseEnv($this->siteKey));
        return preg_match(Plugin::SITE_KEY_PATTERN, $key) ? $key : null;
    }

    protected function defineBehaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => ['siteKey'],
            ],
        ];
    }

    protected function defineRules(): array
    {
        return [
            [['siteKey'], 'trim'],
            [['siteKey'], 'validateSiteKey'],
        ];
    }

    public function validateSiteKey(string $attribute): void
    {
        // Empty turns the chat off; an environment variable may be empty on this machine and set where it runs.
        if ($this->siteKey === '' || str_starts_with($this->siteKey, '$')) {
            return;
        }
        if (!preg_match(Plugin::SITE_KEY_PATTERN, $this->siteKey)) {
            $this->addError($attribute, 'That isn’t a site key. Copy yours from the last step of setup, like rl_7hK2mQ9xW4.');
        }
    }
}
