<?php
namespace verbb\formie\gql\types;

use Craft;
use craft\gql\base\ObjectType;
use craft\gql\GqlEntityRegistry;
use craft\helpers\Json;

use GraphQL\Type\Definition\Type;

class FormIntegrationsType extends ObjectType
{
    // Static Methods
    // =========================================================================

    public static function getName(): string
    {
        return 'FormIntegrationsType';
    }

    public static function getType()
    {
        return GqlEntityRegistry::getEntity(self::getName()) ?: GqlEntityRegistry::createEntity(self::getName(), new self([
            'name' => self::getName(),
            'fields' => [
                'name' => [
                    'name' => 'name',
                    'type' => Type::string(),
                ],
                'handle' => [
                    'name' => 'handle',
                    'type' => Type::string(),
                ],
                'enabled' => [
                    'name' => 'enabled',
                    'type' => Type::boolean(),
                ],
                'settings' => [
                    'name' => 'settings',
                    'type' => Type::string(),
                    'resolve' => function ($source, $arguments) {
                        $json = Json::decode(Json::encode($source));

                        // Cleanup some settings that don't need to be included
                        unset($json['cache'], $json['formId'], $json['optInField'], $json['type'], $json['sortOrder'], $json['uid'], $json['referrer'], $json['dateCreated'], $json['dateUpdated']);

                        // Remove all field mapping (different for each provider)
                        foreach ($json as $key => $value) {
                            if (str_contains($key, 'mapTo') || str_contains($key, 'FieldMapping')) {
                                unset($json[$key]);
                                continue;
                            }

                            // Parse any .env variables
                            if (is_string($value) && str_contains($value, '$')) {
                                $json[$key] = Craft::parseEnv($value);
                            }
                        }

                        return Json::encode($json);
                    },
                ],
            ],
        ]));
    }
}
