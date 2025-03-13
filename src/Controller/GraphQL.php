<?php

namespace App\Controller;

use App\GraphQL\Resolver\CategoryResolver;
use App\GraphQL\Resolver\ProductResolver;
use App\GraphQL\Mutation\OrderMutation;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphQL {
    static public function handle() {
        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => array_merge(
                    CategoryResolver::getFields(),
                    ProductResolver::getFields()
                )
            ]);
            
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => OrderMutation::getFields()
            ]);
            
            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
            
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
            
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
            
            if (!$query) {
                throw new RuntimeException('No GraphQL query provided');
            }
            
            $rootValue = [];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'locations' => [['line' => $e->getLine(), 'column' => 0]],
                        'path' => ['query']
                    ]
                ]
            ];
        }

        // Set CORS headers to allow requests from any origin
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        // Handle OPTIONS requests for CORS preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
        
        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}