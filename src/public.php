<?php

declare(strict_types=1);

use Dompdf\Dompdf;
use App\Service\CustomApi;
use App\Service\ContractGenerator;
use Ubnt\UcrmPluginSdk\Security\PermissionNames;
use Ubnt\UcrmPluginSdk\Service\UcrmSecurity;

chdir(__DIR__);

require_once __DIR__ . '/vendor/autoload.php';

// Loading the ".env" file.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve API connection.
$api = new CustomApi();
$contractGenerator = new ContractGenerator($api);

// Ensure that user is logged in and has permission to view the page.
$security = UcrmSecurity::create();
$user = $security->getUser();

if (!$user || $user->isClient || !$user->hasViewPermission(PermissionNames::BILLING_INVOICES)) {
    \App\Http::forbidden();
}

// Process the generate document request.
if (array_key_exists('generate', $_GET)) {
    $parameter = [
        'id' => $_GET['generate']
    ];

    $count = 0;

    if (array_key_exists('template', $_GET)) {

        switch ($_GET['template']) {
            case '0':
                var_dump('You need to select a template!');
                die();
                break;

            case 'urban': {
                    foreach ($_GET['generate'] as $clientId) {
                        try {
                            $client = $api::doRequest("clients/$clientId") ?: [];
                            $contacts = $api::doRequest("clients/$clientId/contacts") ?: [];
                            $services = $api::doRequest("clients/services?clientId=$clientId&statuses[]=1") ?: [];

                            $templatePath = __DIR__ . "/templates/contracts/urban.php";
                            $generatedDocument = $contractGenerator->generateDocumentTemplate($templatePath, $client, $services, $contacts);


                            // Initialize Dompdf class.
                            $PDF = new Dompdf();

                            $pdfOptions = $PDF->getOptions();
                            $pdfOptions->set('isRemoteEnabled', true);
                            $pdfOptions->set('isHtml5ParserEnabled', true);
                            $PDF->setOptions($pdfOptions);

                            $PDF->loadHtml($generatedDocument);
                            $PDF->setPaper('A4', 'portrait');
                            $PDF->render();

                            $pdfAttachment = $PDF->output();

                            $fileName = "Contract U.N.S (client ID: #$clientId)";
                            $encoding = "base64";
                            $type = "application/pdf";

                            $fileEncoding = base64_encode($pdfAttachment);

                            $contractGenerator->generateDocument(intval($clientId), $fileName, $fileEncoding);

                            $count++;
                        } catch (\Exception $e) {
                            var_dump($e->getMessage());
                        }
                    }

                    break;
                }

            case 'zerosapte': {
                    foreach ($_GET['generate'] as $clientId) {
                        try {
                            $client = $api::doRequest("clients/$clientId") ?: [];
                            $contacts = $api::doRequest("clients/$clientId/contacts") ?: [];
                            $services = $api::doRequest("clients/services?clientId=$clientId&statuses[]=1") ?: [];

                            $templatePath = __DIR__ . "/templates/contracts/zero-sapte.php";
                            $generatedDocument = $contractGenerator->generateDocumentTemplate($templatePath, $client, $services, $contacts);


                            // Initialize Dompdf class.
                            $PDF = new Dompdf();

                            $pdfOptions = $PDF->getOptions();
                            $pdfOptions->set('isRemoteEnabled', true);
                            $pdfOptions->set('isHtml5ParserEnabled', true);
                            $PDF->setOptions($pdfOptions);

                            $PDF->loadHtml($generatedDocument);
                            $PDF->setPaper('A4', 'portrait');
                            $PDF->render();

                            $pdfAttachment = $PDF->output();

                            $fileName = "Contract 07S (client ID: #$clientId)";
                            $encoding = "base64";
                            $type = "application/pdf";

                            $fileEncoding = base64_encode($pdfAttachment);

                            $contractGenerator->generateDocument(intval($clientId), $fileName, $fileEncoding);

                            $count++;
                        } catch (\Exception $e) {
                            var_dump($e->getMessage());
                        }
                    }

                    break;
                }

            default: {
                    var_dump('You need to select a template!');
                    die();
                    break;
                }
        }
    }

    var_dump("Generated contract documents for $count clients.");
}


// Render the page.
$clients = $api::doRequest("clients");

if ($clients) {
    $contractGenerator->generateView($clients);
} else {
    echo "No clients found.";
}
