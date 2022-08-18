<?php
declare(strict_types=1);

namespace SodasHelper;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\HttpsEnforcerMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for SodasHelper
 */
class Plugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        /** Add TinyMCE options **/
        /** Requires https://github.com/CakeDC/TinyMCE **/
        Configure::write('TinyMCE.settings', [
            'script' => 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.1.2/tinymce.min.js'
        ]);
        Configure::write('TinyMCE.editorOptions', [
            'selector' => '.tinymce',
            'plugins' => 'image lists link anchor charmap autolink code emoticons fullscreen media quickbars preview',
            'toolbar' => 'blocks | bold italic underline strikethrough align bullist numlist | link image media charmap emoticons | code fullscreen preview',
            'menubar' => false,
            'extended_valid_elements' => "style,link[href|rel]",
            'custom_elements' => "style,link,~link",
            'image_uploadtab' => true,
            'images_upload_url' => '/webroot/upload',
            'branding' => false,
            'browser_spellcheck' => true,
            'setup' => [
                'function' => "(editor) => {editor.on('init', () => {editor.getContainer().style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';});editor.on('focus', () => {editor.getContainer().style.boxShadow = '0 0 0 .2rem rgba(0, 123, 255, .25)';editor.getContainer().style.borderColor = '#80bdff';});editor.on('blur', () => {editor.getContainer().style.boxShadow = '';editor.getContainer().style.borderColor = '';});}"
            ]
        ]);
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin(
            'SodasHelper',
            ['path' => '/sodas-helper'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
        parent::routes($routes);
    }

    private function isSecure()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443;
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $options = ['httponly' => true];
        if ($this->isSecure()) {
            $options = array_merge($options, ['secure' => true]);
        }
        $securityHeaders = new SecurityHeadersMiddleware();
        $securityHeaders
            ->setCrossDomainPolicy('none')
            ->setReferrerPolicy()
            ->setXFrameOptions()
            ->setXssProtection()
            ->noOpen()
            ->noSniff();

        // Add your middlewares here
        $middlewareQueue
            ->add(new HttpsEnforcerMiddleware([
                    'disableOnDebug' => false,
                    'headers' => ['X-Https-Upgrade' => 1],
                    'hsts' => [
                        'maxAge' => YEAR,
                        'includeSubdomains' => true,
                        'preload' => true,
                    ],
                ]))
            ->add($securityHeaders)
            ->add(new CsrfProtectionMiddleware($options));

        return $middlewareQueue;
    }
}
