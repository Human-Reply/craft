# Human Reply Live Chat for Craft CMS

Live chat on every page of your Craft site, answered by your team from Telegram. Visitors write in a chat bubble on your site, each conversation opens as a topic in your team's Telegram group, and the replies land back on the page live.

Setup guide: [humanreply.app/craft/setup-guide](https://humanreply.app/craft/setup-guide)

## Requirements

Craft CMS 5.0 or later, PHP 8.2 or later.

## Add it to your site

1. Set up your team at [humanreply.app/start](https://humanreply.app/start). Choose **Other** and type your site's address. The last step gives you your site key, like `rl_7hK2mQ9xW4`.
2. Install the plugin from the Plugin Store in your control panel, or with Composer:

   ```sh
   composer require human-reply/craft-live-chat
   php craft plugin/install human-reply-live-chat
   ```
3. In **Settings** › **Plugins** › **Human Reply Live Chat**, paste your site key and save.
4. Deploy, then open your site once. The setup page turns green when the chat loads.

Production sites usually don't allow admin changes, so set the key where you develop and deploy it with your project config, or keep it in your environment instead: put `$HUMAN_REPLY_SITE_KEY` in the field and `HUMAN_REPLY_SITE_KEY=rl_7hK2mQ9xW4` in each environment's `.env`. A `config/human-reply-live-chat.php` returning `['siteKey' => '…']` also works.

## What it adds

One script tag just before `</body>` on every page your site renders, which loads the chat from humanreply.app:

```html
<script src="https://humanreply.app/embed.js" data-site="rl_7hK2mQ9xW4" defer></script>
```

Nothing is added to the control panel or to previews, and without a site key nothing is added at all.

How the chat looks, what it says and when your team is available are set in your dashboard at humanreply.app, so changing them needs no deploy.

## More

- Privacy: [humanreply.app/privacy](https://humanreply.app/privacy)
- Support: [hello@send.humanreply.app](mailto:hello@send.humanreply.app)
