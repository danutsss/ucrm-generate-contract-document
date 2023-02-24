<?php

declare(strict_types=1);

namespace App\Service;

chdir(__DIR__);

use App\Service\CustomApi;
use Ubnt\UcrmPluginSdk\Service\UcrmOptionsManager;

class ContractGenerator
{
    /**
     * @var CustomApi
     */
    private $ucrmApi;

    public function __construct(CustomApi $ucrmApi)
    {
        $this->ucrmApi = new CustomApi();
    }

    public function generateView(array $clients): void
    {
        $renderer = new TemplateRenderer();
        $optionsManager = UcrmOptionsManager::create();
        $renderer->render(
            __DIR__ . "/../../templates/document-generator.php",
            [
                'clients' => $clients,
                'ucrmPublicUrl' => $optionsManager->loadOptions()->ucrmPublicUrl,
            ]
        );
    }

    public function generateDocumentTemplate($templatePath, $client, array $services, array $contacts)
    {
        ob_start();
        include($templatePath);

        $getContents = ob_get_contents();

        ob_end_clean();

        return $getContents;
    }

    public function generateDocument(int $clientId, string $fileName, $file): void
    {
        try {
            $this->ucrmApi::doRequest("documents", "POST", [
                'clientId' => $clientId,
                'name' => $fileName,
                'file' => $file
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
