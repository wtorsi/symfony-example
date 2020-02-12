<?php

declare(strict_types=1);

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @mixin AbstractController
 */
trait FormTrait
{
    protected function createErrorResponse(?string $message = null, array $data = []): JsonResponse
    {
        return $this->response($message, array_merge([
            'success' => false,
        ], $data), 400);
    }

    protected function createSuccessResponse(?string $message = null, array $data = []): JsonResponse
    {
        return $this->response($message, array_merge([
            'success' => true,
        ], $data));
    }

    protected function createErrorFormResponse(FormInterface $form, ?string $message = null, array $data = []): JsonResponse
    {
        return $this->createErrorResponse($message, array_merge($data, [
            'errors' => $this->serializeFormErrors($form),
        ]));
    }

    protected function createSuccessRedirectResponse(string $url): JsonResponse
    {
        return $this->response(null, [
            'success' => true,
            'location' => $url,
        ], 302);
    }

    protected function createSuccessRedirectToRouteResponse(string $name, array $parameters = []): JsonResponse
    {
        return $this->createSuccessRedirectResponse($this->generateUrl($name, $parameters));
    }

    protected function createSuccessHtmlResponse(string $view, array $parameters = [], ?string $message = null): JsonResponse
    {
        return $this->response($message, [
            'success' => true,
            'html' => $this->renderView($view, $parameters),
        ]);
    }

    protected function createFormResponse(FormInterface $form, Request $request, callable $callable): JsonResponse
    {
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw $this->createSubmittedFormRequiredException(\get_class($form));
        }

        return $this->createSubmittedFormResponse($form, $request, $callable);
    }

    protected function createSubmittedFormResponse(FormInterface $form, Request $request, callable $callable): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotXmlHttpRequestException();
        }

        return $this->onFormSubmitted($form, $callable);
    }

    /**
     * @param callable $callable must return a response \Symfony\Component\HttpFoundation\Response
     * @return JsonResponse
     */
    protected function onFormSubmitted(FormInterface $form, callable $callable): JsonResponse
    {
        if (!$form->isValid()) {
            return $this->createErrorFormResponse($form);
        }

        $response = \call_user_func($callable);

        if (null === $response
            || !$response instanceof JsonResponse
            || !\is_subclass_of($response, Response::class, false)) {
            throw new \Exception\LogicException(sprintf('Passed closure must return %s, returned %s', Response::class, null === $response ? 'null' : \get_class($response)));
        }

        return $response;
    }

    protected function serializeFormErrors(FormInterface $form): array
    {
        return $this->_serializeFormErrors($form->getErrors(true, false));
    }

    private function response(?string $message = null, array $data = [], int $status = 200): JsonResponse
    {
        if (null !== $message && $this->container->has('translator')) {
            $params = \array_filter($data, function (string $key): bool {
                return (bool) \preg_match('/^\{\{.*\}\}$|^\%.*\%$/', $key);
            });

            $message = $this->container->get('translator')->trans($message, $params, 'flashes');
        }

        return $this->json(\array_merge([
            'message' => $message,
        ], $data), $status);
    }

    private function _serializeFormErrors(FormErrorIterator $iterator): array
    {
        $errors = [];
        foreach ($iterator as $formErrorIterator) {
            if ($formErrorIterator instanceof FormErrorIterator) {
                $name = $formErrorIterator->getForm()->getName();
                $errors[$name] = $this->_serializeFormErrors($formErrorIterator);
            } else {
                /* @var FormError $formErrorIterator */
                $errors[] = $formErrorIterator->getMessage();
            }
        }

        return $errors;
    }
}
