<?php

namespace App\Core\Notify;

use App\Core\Notify\Sessions\SessionStore;

class NotifyConfig
{
    /**
     * Session storage.
     */
    protected $session;

    /**
     * Configuration options.
     *
     * @var array
     */
    protected $config;

    /**
     * Setting up the session
     *
     * @param SessionStore $session
     */
    public function __construct(SessionStore $session)
    {
        // $this->setDefaultConfig();
        $this->session = $session;
    }

    /**
     * The default configuration for alert
     *
     * @return void
     */
    protected function setDefaultConfig()
    {
        $this->config = [
            'title' => '',
            'text' => '',
            'timer' => config('notify.timer'),
            'width' => config('notify.width'),
            'heightAuto' => config('notify.height_auto'),
            'padding' => config('notify.padding'),
            'showConfirmButton' => config('notify.show_confirm_button'),
            'showCloseButton' => config('notify.show_close_button'),
            'timerProgressBar' => config('notify.timer_progress_bar'),
            'customClass' => [
                'container' => config('notify.customClass.container'),
                'popup' => config('notify.customClass.popup'),
                'header' => config('notify.customClass.header'),
                'title' => config('notify.customClass.title'),
                'closeButton' => config('notify.customClass.closeButton'),
                'icon' => config('notify.customClass.icon'),
                'image' => config('notify.customClass.image'),
                'content' => config('notify.customClass.content'),
                'input' => config('notify.customClass.input'),
                'actions' => config('notify.customClass.actions'),
                'confirmButton' => config('notify.customClass.confirmButton'),
                'cancelButton' => config('notify.customClass.cancelButton'),
                'footer' => config('notify.customClass.footer')
            ]
        ];
    }

    /**
     * The default configuration for middleware alert.
     *
     * @return $config
     */
    public function middleware()
    {
        unset($this->config['position'], $this->config['heightAuto'], $this->config['width'], $this->config['padding'], $this->config['showCloseButton']);

        if (!config('notify.middleware.autoClose')) {
            $this->removeTimer();
        } else {
            unset($this->config['timer']);
            $this->config['timer'] = config('notify.middleware.timer');
        }
        $this->config['position'] = config('notify.middleware.notify_position');
        $this->config['showCloseButton'] = config('notify.middleware.toast_close_button');

        $this->flash();

        return $this;
    }

    /**
     * Flash an alert message.
     *
     * @param  string $title
     * @param  string $text
     * @param  array  $icon
     * @return void
     */
    public function notify($title = '', $text = '', $icon = null)
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }

        $this->flash();

        return $this;
    }

    /**
     * Display a success typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     */
    public function success($title = '', $text = '')
    {
        $this->notify($title, $text, 'success');
        return $this;
    }

    /**
     * Display a info typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     */
    public function info($title = '', $text = '')
    {
        $this->notify($title, $text, 'info');
        return $this;
    }

    /**
     * Display a warning typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     */
    public function warning($title = '', $text = '')
    {
        $this->notify($title, $text, 'warning');
        return $this;
    }

    /**
     * Display a question typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     */
    public function question($title = '', $text = '')
    {
        $this->notify($title, $text, 'question');
        $this->showCancelButton();
        return $this;
    }

    /**
     * Display a error typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     */
    public function error($title = '', $text = '')
    {
        $this->notify($title, $text, 'error');
        return $this;
    }

    /**
     * Display a message with a custom image and CSS animation disabled.
     *
     * @param string $title
     * @param string $text
     * @param string $imageUrl
     * @param integer $imageWidth
     * @param integer $imageHeight
     * @param string $imageAlt
     */
    public function image($title, $text, $imageUrl, $imageWidth, $imageHeight, $imageAlt = null)
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        $this->config['imageUrl'] = $imageUrl;
        $this->config['imageWidth'] = $imageWidth;
        $this->config['imageHeight'] = $imageHeight;
        if (!is_null($imageAlt)) {
            $this->config['imageAlt'] = $imageAlt;
        } else {
            $this->config['imageAlt'] = $title;
        }
        $this->config['animation'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Display a html typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $icon
     */
    public function html($title = '', $code = '', $icon = '')
    {
        $this->config['title'] = $title;
        $this->config['html'] = $code;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }

        $this->flash();
        return $this;
    }

    /**
     * Display a toast message
     *
     * @param string $title
     * @param string $icon
     */
    public function toast($title = '', $icon = '', $position)
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['icon'] = $icon;
        $this->config['position'] = ($position ?: config('notify.notify_position'));
        $this->config['showCloseButton'] = true;
        $this->config['showConfirmButton'] = false;

        unset($this->config['heightAuto']);
        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to Toast
     *
     * @param string $position
     */
    public function toToast($position = null)
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = true;
        if (!empty($position) && isset($position)) {
            $this->config['position'] = $position;
        } else {
            $this->config['position'] = ($position ?: config('notify.notify_position'));
        }
        $this->config['showConfirmButton'] = false;
        unset($this->config['width'], $this->config['padding']);

        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to html
     *
     */
    public function toHtml()
    {
        $this->config['html'] = $this->config['text'];
        unset($this->config['text']);

        $this->flash();
        return $this;
    }

    /**
     * Add a custom image to alert
     *
     * @param string $imageUrl
     */
    public function addImage($imageUrl)
    {
        $this->config['imageUrl'] = $imageUrl;
        $this->config['showCloseButton'] = true;
        unset($this->config['icon']);

        $this->flash();
        return $this;
    }

    /**
     * Add footer section to notify()
     *
     * @param string $code
     */
    public function footer($code)
    {
        $this->config['footer'] = $code;

        $this->flash();
        return $this;
    }

    /**
     * positioned alert dialog
     *
     * @param string $position
     */
    public function position($position = null)
    {
        $this->config['position'] = ($position ?: config('notify.notify_position'));

        $this->flash();
        return $this;
    }

    /**
     * Modal window width
     * including paddings
     * (box-sizing: border-box).
     * Can be in px or %. The default width is 32rem
     *
     * @param string $width
     */
    public function width($width = '32rem')
    {
        $this->config['width'] = $width;

        $this->flash();
        return $this;
    }

    /**
     * Modal window padding.
     * The default padding is 1.25rem.
     *
     * @param string $padding
     */
    public function padding($padding = '1.25rem')
    {
        $this->config['padding'] = $padding;

        $this->flash();
        return $this;
    }

    /**
     * Modal window background
     * (CSS background property).
     * The default background is '#fff'.
     *
     * @param string $background
     */
    public function background($background = '#fff')
    {
        $this->config['background'] = $background;

        $this->flash();
        return $this;
    }

    /**
     * Set to false if you want to
     * focus the first element in tab
     * order instead of "Confirm"-button by default.
     *
     * @param boolean $focus
     */
    public function focusConfirm($focus = true)
    {
        $this->config['focusConfirm'] = $focus;
        unset($this->config['focusCancel']);

        $this->flash();
        return $this;
    }

    /**
     * Set to true if you want to focus the
     * "Cancel"-button by default.
     *
     * @param boolean $focus
     */
    public function focusCancel($focus = false)
    {
        $this->config['focusCancel'] = $focus;
        unset($this->config['focusConfirm']);

        $this->flash();
        return $this;
    }

    /**
     * Custom animation with [Animate.css](https://daneden.github.io/animate.css/)
     * CSS classes for animations when showing a popup (fade in):
     * CSS classes for animations when hiding a popup (fade out):
     *
     * @param string $showAnimation
     * @param string $hideAnimation
     */
    public function animation($showAnimation, $hideAnimation)
    {
        if (!config('notify.animation.enable')) {
            config(['notify.animation.enable' => true]);
        }
        $this->config['showClass'] = ['popup' => "animate__animated {$showAnimation}"];
        $this->config['hideClass'] = ['popup' => "animate__animated {$hideAnimation}"];

        $this->flash();
        return $this;
    }

    /**
     * Persistent the alert modal
     *
     * @param boolean $showConfirmBtn
     * @param boolean $showCloseBtn
     */
    public function persistent($showConfirmBtn = true, $showCloseBtn = false)
    {
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();
        if ($showConfirmBtn) {
            $this->showConfirmButton();
        }
        if ($showCloseBtn) {
            $this->showCloseButton();
        }

        $this->flash();
        return $this;
    }

    /**
     * auto close alert modal after
     * specifid time
     *
     * @param integer $milliseconds
     */
    public function autoClose($milliseconds = 5000)
    {
        $this->config['timer'] = ($milliseconds ?? config('notify.duration'));

        $this->flash();
        return $this;
    }

    /**
     * Display confirm button
     *
     * @param string $btnText
     * @param string $btnColor
     */
    public function showConfirmButton(string $btnText = 'Ok', string $btnColor = '#3085d6')
    {
        $this->config['showConfirmButton'] = true;
        $this->config['confirmButtonText'] = $btnText;
        $this->config['confirmButtonColor'] = $btnColor;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();

        $this->flash();
        return $this;
    }

    /**
     * Display cancel button
     *
     * @param string $btnText
     * @param string $btnColor
     */
    public function showCancelButton($btnText = 'Cancel', $btnColor = '#aaa')
    {
        $this->config['showCancelButton'] = true;
        $this->config['cancelButtonText'] = $btnText;
        $this->config['cancelButtonColor'] = $btnColor;
        $this->removeTimer();

        $this->flash();
        return $this;
    }

    /**
     * Display close button
     *
     * @param string $closeButtonAriaLabel
     */
    public function showCloseButton($closeButtonAriaLabel = 'aria-label')
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        $this->flash();
        return $this;
    }

    /**
     * Hide close button from alert or toast
     *
     */
    public function hideCloseButton()
    {
        $this->config['showCloseButton'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Apply default styling to buttons.
     * If you want to use your own classes (e.g. Bootstrap classes)
     * set this parameter to false.
     *
     * @param boolean $buttonsStyling
     */
    public function buttonsStyling($buttonsStyling)
    {
        $this->config['buttonsStyling'] = $buttonsStyling;

        $this->flash();
        return $this;
    }

    /**
     * Use any HTML inside icons (e.g. Font Awesome)
     *
     * @param string $iconHtml
     */
    public function iconHtml($iconHtml)
    {
        $this->config['iconHtml'] = $iconHtml;

        $this->flash();
        return $this;
    }

    /**
     *  If set to true, the timer will have a progress bar at the bottom of a popup.
     * Mostly, this feature is useful with toasts.
     *
     */
    public function timerProgressBar()
    {
        $this->config['timerProgressBar'] = true;

        $this->flash();
        return $this;
    }

    /**
     * Reverse buttons position
     *
     * @author Faber44 <https://github.com/Faber44>
     */
    public function reverseButtons()
    {
        $this->config['reverseButtons'] = true;

        $this->flash();
        return $this;
    }

    /**
     * Remove the timer from config option.
     *
     */
    protected function removeTimer()
    {
        if (array_key_exists('timer', $this->config)) {
            unset($this->config['timer']);
        }
    }

    /**
     * Flash the config options for alert.
     *
     */
    public function flash()
    {
        foreach ($this->config as $key => $value) {
            $this->session->flash("notify.config.{$key}", $value);
        }
        $this->session->flash('notify.config', $this->buildConfig());
    }

    /**
     * Build Flash config options for flashing.
     *
     */
    public function buildConfig()
    {
        $config = $this->config;
        return json_encode($config);
    }
}
