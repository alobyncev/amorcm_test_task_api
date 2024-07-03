<?php

namespace App\Services;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use App\Errors\LeadsChunkError;
use App\OAuth\ApiClientService;
use App\OAuth\OAuthToken;

class LeadService
{
    /**
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMApiException
     */
    function create(): void
    {
        $accessToken = OAuthToken::getToken();

        $apiClient = (new ApiClientService())->getApiClient($accessToken);

        $leadsService = $apiClient->leads();

        $countLeads = getenv('COUNT_LEADS');

        $leadsSelectField = $apiClient
            ->customFields(EntityTypesInterface::LEADS)
            ->addOne(
                (new CustomFieldService())
                    ->makeSelectField()
            );

        $countChunks = (int)($countLeads / 50);

        $leadsCollectionArray = [];
        for ($i = 0; $i < $countChunks; $i++) {
            $leadsCollection = new LeadsCollection();

            for ($j = 0; $j < 50; $j++) {
                $contact = new ContactModel();
                $contact->setName("ContactName$i.$j");
                $company = new CompanyModel();
                $company->setName("CompanyName$i.$j");
                $lead = new LeadModel();
                $lead->setName("LeadName$i.$j")
                    ->setContacts((new ContactsCollection())->add($contact))
                    ->setCompany($company);

                // указываем "Второй вариант" по коду энама
                $variant = (new SelectCustomFieldValuesModel())
                    ->setFieldId($leadsSelectField->getId())
                    ->setValues((new SelectCustomFieldValueCollection())->add(
                        (new SelectCustomFieldValueModel())->setEnumCode('second')
                    ));

                $leadCustomFieldsValues = new CustomFieldsValuesCollection();
                $leadCustomFieldsValues->add($variant);
                $lead->setCustomFieldsValues($leadCustomFieldsValues);

                $leadsCollection->add($lead);
            }
            try {
                $leadsService->addComplex($leadsCollection);
            } catch (AmoCRMApiException $e) {
                $e->setDescription($i);
                (new LeadsChunkError())->printError($e);
                die;
            }
            $leadsCollectionArray[] = $leadsCollection;
        }
        echo 'Added leads: ';
        echo '<pre>';
        var_dump($leadsCollectionArray);
        echo PHP_EOL;
    }
}