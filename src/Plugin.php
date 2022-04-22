<?php
declare(strict_types=1);

namespace SodasHelper;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
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
        Configure::write('TinyMCE.settings', ['script' => 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.0.1/tinymce.min.js']);
        Configure::write('TinyMCE.editorOptions', [
            'selector' => '.tinymce',
            'plugins' => 'image lists link anchor charmap autolink',
            'toolbar' => 'blocks | bold italic underline strikethrough bullist numlist | link image charmap',
            'menubar' => false,
            'image_uploadtab' => true,
            'images_upload_url' => '/',
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

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Add your middlewares here

        return $middlewareQueue;
    }
}
