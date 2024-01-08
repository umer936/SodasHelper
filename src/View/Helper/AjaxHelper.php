<?php
declare(strict_types=1);

namespace SodasHelper\View\Helper;

use Cake\View\Helper;

/**
 * Ajax helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 *
 * USAGE example:
 *
 * echo $this->Ajax->updateOtherDiv(
 * ['action' => 'showHistory', $subtab1],
 * 'ShowHistoryBtn',
 * 'show_history'
 * );
 */
class AjaxHelper extends Helper
{
    protected array $helpers = ['Html', 'Url'];

    protected array $_config;

    /**
     * Just need: Button id (spinner in the button), url, success (id of div to update, and ), error
     */
    public function updateOtherDiv($url, $buttonId, $divToUpdate, $postData = null, $divToError = null)
    {
        $this->setHeaders();

        if (is_array($url)) {
            $url = $this->Url->build($url);
        }

        /** this function is built in to PHP 8. Needs to be defined for PHP < 8 */
        if (!function_exists('str_starts_with')) {
            function str_starts_with($haystack, $needle): bool
            {
                return strpos($haystack, $needle) === 0;
            }
        }

        if (!str_starts_with($buttonId, '#')) {
            $buttonId = "#$buttonId";
        }

        if (!str_starts_with($divToUpdate, '#')) {
            $divToUpdate = "#$divToUpdate";
        }

        if (isset($divToError) && !str_starts_with($divToError, '#')) {
            $divToError = "#$divToError";
        }

        $fetchOptions = [];
        if (isset($postData)) {
            $fetchOptions['body'] = "document.querySelector('$postData')";
        }
        $fetchOptions = array_merge_recursive($this->getConfig(), $fetchOptions);
        $fetchOptions = json_encode($fetchOptions);
        $script = "
                function checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response
        }
    }
            function parseData(response) {
        let type = response.headers.get('Content-Type');
        if (type.includes('json')) {
            return response.json();
        } else {
            return response.text()
        }
         }
         
         
//         https://ghinda.net/article/script-tags/
//         https://stackoverflow.com/questions/1197575/can-scripts-be-inserted-with-innerhtml
//  https://stackoverflow.com/questions/2592092/executing-script-elements-inserted-with-innerhtml
         function success(response) {
           const scriptEl = document.createRange().createContextualFragment(response);
           const successDiv = document.querySelector('$divToUpdate');
           successDiv.innerHTML = '';
         successDiv.append(scriptEl);
        }
         ";

        $script .= "
        document.querySelector('$buttonId').addEventListener('click', function () {
        fetch(
            '$url', 
            $fetchOptions
        )
        .then(checkStatus)
        .then(parseData)
        .then(success);
        });
        ";

//        .catch(settings.error);";

        return $this->Html->scriptBlock($script);
    }

    private function setHeaders(): void
    {
        $this->setConfig('headers.X-CSRF-Token', $this->getView()->getRequest()->getAttribute('csrfToken'));
        $this->setConfig('headers.X-Requested-With', "XMLHttpRequest");
    }


}
