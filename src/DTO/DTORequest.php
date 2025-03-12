<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTORequest
{

    private array $validated = [];
    private array $violations = [];
    private \ReflectionClass $reflection;

    public function __construct(
        private ValidatorInterface $validator,
        private RequestStack $requestStack,
    ) {
        $this->reflection = new \ReflectionClass($this);

        $this->validate();

        if (count($this->violations) > 0) {
            $this->handleValidationErrors();
        }
    }

    public function getRequestParams(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        $bodyParams = json_decode($request->getContent(), true) ?? [];
        $queryParams = $request->query->all();

        return array_merge($queryParams, $bodyParams);
    }

    protected function validate(): void
    {
        $requestParams = $this->getRequestParams();
        $objectProperties = $this->reflection->getProperties();

        foreach ($requestParams as $param => $value) {
            if (property_exists($this, $param)) {
                $violations = $this->validator->validatePropertyValue($this, $param, $value);

                if (count($violations) > 0) {
                    $this->prepareErrors($violations, $param);
                    $this->validated[$param] = null;

                    continue;
                }

                $reflectionProperty = $this->reflection->getProperty($param);
                $reflectionProperty->setValue($this, $value);

                $this->validated[$param] = $value;
            }
        }

        foreach ($objectProperties as $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if (!array_key_exists($property, $this->validated)) {
                $violations = $this->validator->validateProperty($this, $property);

                if (count($violations) > 0) {
                    $this->prepareErrors($violations, $property);
                }
            }
        }
    }

    public function validated(): array
    {
        return $this->validated;
    }

    public function prepareErrors(ConstraintViolationListInterface $violations, string $property): void
    {
        foreach ($violations as $violation) {
            $this->violations[$property][] = [
                'message' => $violation->getMessage(),
            ];
        }
    }

    private function handleValidationErrors(): void
    {
        $response = new JsonResponse(['errors' => $this->violations], 422);
        $response->send();

        exit;
    }
}